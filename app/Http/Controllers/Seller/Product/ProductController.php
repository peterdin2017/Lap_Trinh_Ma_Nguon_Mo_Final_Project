<?php

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Product\StoreProductRequest;
use App\Http\Requests\Seller\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Throwable;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm của Seller hiện đăng nhập.
     */
    public function index(Request $request): View
    {
        $seller = $this->currentSeller();

        $status = (string) $request->query('status', 'all');
        $search = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category');

        $productsQuery = Product::query()
            ->with('category')
            ->where('seller_id', $seller->seller_id)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('product_name', 'like', "%{$search}%");

                    // Schema hiện tại không có SKU, dùng product_id để tra cứu mã.
                    if (is_numeric($search)) {
                        $query->orWhere('product_id', (int) $search);
                    }
                });
            })
            ->when(
                $categoryId,
                fn ($query) => $query->where('category_id', $categoryId)
            );

        match ($status) {
            'active' => $productsQuery
                ->where('status', 'active')
                ->where('stock_quantity', '>', 0),

            // DB không có trạng thái out_of_stock; suy ra từ tồn kho.
            'out_of_stock' => $productsQuery->where('stock_quantity', 0),

            'hidden' => $productsQuery->whereIn('status', ['hidden', 'locked']),

            default => null,
        };

        $products = $productsQuery
            ->latest('product_id')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('category_name')
            ->get(['category_id', 'category_name']);

        return view('seller.products.index', compact(
            'products',
            'categories',
            'status',
            'search',
            'categoryId'
        ));
    }

    /**
     * Form tạo sản phẩm.
     */
    public function create(): View
    {
        $this->currentSeller();

        $categories = $this->categoryTree();

        return view('seller.products.create', compact('categories'));
    }

    /**
     * Lưu sản phẩm mới.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $seller = $this->currentSeller();
        $data = $request->validated();
        $variants = $data['variants'] ?? [];
        $status = $data['status'] ?? 'active';

        unset(
            $data['image'],
            $data['images'],
            $data['variants'],
            $data['variants_present'],
            $data['status']
        );

        if ($variants !== []) {
            $data['price'] = min(array_column($variants, 'price'));
            $data['stock_quantity'] = array_sum(
                array_column($variants, 'stock_quantity')
            );
        }

        $storedPaths = [];

        try {
            // Lưu ảnh riêng cho từng mẫu mã.
            foreach ($variants as $index => &$variant) {
                $variantImage = $request->file("variants.{$index}.image");

                if ($variantImage) {
                    $path = $this->storeConvertedImage($variantImage);
                    $variant['image_url'] = $path;
                    $storedPaths[] = $path;
                }
            }
            unset($variant);

            $mainImagePath = null;

            if ($request->hasFile('image')) {
                $mainImagePath = $this->storeConvertedImage(
                    $request->file('image')
                );

                $storedPaths[] = $mainImagePath;
            }

            $extraImagePaths = [];

            foreach ($request->file('images', []) as $image) {
                $path = $this->storeConvertedImage($image);
                $extraImagePaths[] = $path;
                $storedPaths[] = $path;
            }

            DB::transaction(function () use (
                $seller,
                $data,
                $status,
                $variants,
                $mainImagePath,
                $extraImagePaths
            ): void {
                $product = new Product();
                $product->fill($data);

                $product->seller_id = $seller->seller_id;
                $product->status = $status;
                $product->image_url = $mainImagePath;
                $product->save();

                $this->insertGalleryImages($product, $extraImagePaths);
                $this->insertVariants($product, $variants);
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($storedPaths);

            throw $exception;
        }

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Đã thêm sản phẩm thành công.');
    }

    /**
     * Form sửa sản phẩm.
     */
    public function edit(int $product): View
    {
        $seller = $this->currentSeller();

        $product = Product::query()
            ->with(['category', 'images', 'variants'])
            ->where('seller_id', $seller->seller_id)
            ->findOrFail($product);

        $categories = $this->categoryTree();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật sản phẩm, ảnh và biến thể.
     */
    public function update(
        UpdateProductRequest $request,
        int $product
    ): RedirectResponse {
        $seller = $this->currentSeller();

        $productModel = Product::query()
            ->with(['images', 'variants'])
            ->where('seller_id', $seller->seller_id)
            ->findOrFail($product);

        if ($productModel->status === 'locked') {
            return back()
                ->withErrors([
                    'product' => 'Sản phẩm đang bị quản trị viên khóa, không thể chỉnh sửa.',
                ])
                ->withInput();
        }

        $data = $request->validated();
        $variants = $data['variants'] ?? [];
        $removeIds = $data['remove_image_ids'] ?? [];
        $newImages = $request->file('images', []);
        $status = $data['status'] ?? $productModel->status;

        $variantsWereSubmitted = $request->boolean('variants_present')
            || array_key_exists('variants', $data);

        unset(
            $data['image'],
            $data['images'],
            $data['remove_image_ids'],
            $data['variants'],
            $data['variants_present'],
            $data['status']
        );

        if ($variantsWereSubmitted && $variants !== []) {
            $data['price'] = min(array_column($variants, 'price'));
            $data['stock_quantity'] = array_sum(
                array_column($variants, 'stock_quantity')
            );
        }

        $removeIds = collect($removeIds)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $imagesToRemove = $productModel->images
            ->whereIn('image_id', $removeIds)
            ->values();

        if ($imagesToRemove->count() !== count($removeIds)) {
            return back()
                ->withErrors(['images' => 'Danh sách ảnh cần xóa không hợp lệ.'])
                ->withInput();
        }

        $remainingImageCount = $productModel->images->count()
            - $imagesToRemove->count();

        if ($remainingImageCount + count($newImages) > 8) {
            return back()
                ->withErrors(['images' => 'Sản phẩm chỉ được có tối đa 8 ảnh phụ.'])
                ->withInput();
        }

        $oldPathsToDelete = $imagesToRemove->pluck('image_url')->all();
        $newPaths = [];
        $newGalleryPaths = [];

        try {
            // Lưu ảnh mẫu mã mới. Không có file mới thì giữ ảnh cũ.
            foreach ($variants as $index => &$variant) {
                $variantImage = $request->file("variants.{$index}.image");

                if ($variantImage) {
                    $path = $this->storeConvertedImage($variantImage);
                    $variant['image_url'] = $path;
                    $newPaths[] = $path;
                }
            }
            unset($variant);

            if ($request->hasFile('image')) {
                $newMainImagePath = $this->storeConvertedImage(
                    $request->file('image')
                );

                $newPaths[] = $newMainImagePath;

                if ($productModel->image_url) {
                    $oldPathsToDelete[] = $productModel->image_url;
                }

                $data['image_url'] = $newMainImagePath;
            }

            foreach ($newImages as $image) {
                $path = $this->storeConvertedImage($image);
                $newGalleryPaths[] = $path;
                $newPaths[] = $path;
            }

            DB::transaction(function () use (
                $productModel,
                $data,
                $status,
                $imagesToRemove,
                $newGalleryPaths,
                $variants,
                $variantsWereSubmitted,
                &$oldPathsToDelete
            ): void {
                $productModel->fill($data);
                $productModel->status = $status;
                $productModel->save();

                foreach ($imagesToRemove as $image) {
                    $image->delete();
                }

                $this->insertGalleryImages($productModel, $newGalleryPaths);

                if ($variantsWereSubmitted) {
                    $oldPathsToDelete = array_merge(
                        $oldPathsToDelete,
                        $this->syncVariantsSafely($productModel, $variants)
                    );
                }
            });
        } catch (Throwable $exception) {
            // Giao dịch thất bại thì xóa những file vừa tải lên.
            Storage::disk('public')->delete($newPaths);

            throw $exception;
        }

        // Chỉ xóa file cũ sau khi cập nhật database thành công.
        Storage::disk('public')->delete(
            array_values(array_unique(array_filter($oldPathsToDelete)))
        );

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Đã cập nhật sản phẩm thành công.');
    }

    /**
     * Xóa sản phẩm nếu chưa có trong đơn hàng; nếu đã có thì chuyển sang ẩn.
     */
    public function destroy(int $product): RedirectResponse
    {
        $productModel = $this->ownedProduct($product)
            ->load(['images', 'variants']);

        $hasOrders = DB::table('order_items')
            ->where('product_id', $productModel->product_id)
            ->exists();

        if ($hasOrders) {
            $productModel->status = 'hidden';
            $productModel->save();

            return redirect()
                ->route('seller.products.index')
                ->with(
                    'success',
                    'Sản phẩm đã có trong đơn hàng nên được chuyển sang trạng thái ẩn.'
                );
        }

        $paths = collect([$productModel->image_url])
            ->merge($productModel->images->pluck('image_url'))
            ->merge($productModel->variants->pluck('image_url'))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $productModel->delete();

        Storage::disk('public')->delete($paths);

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Đã xóa sản phẩm thành công.');
    }

    /**
     * AJAX bật/tắt active và hidden.
     */
    public function toggleVisibility(
        Request $request,
        int $product
    ): JsonResponse|RedirectResponse {
        $productModel = $this->ownedProduct($product);

        if ($productModel->status === 'locked') {
            $message = 'Sản phẩm bị quản trị viên khóa, bạn không thể thay đổi trạng thái.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->withErrors(['product' => $message]);
        }

        $productModel->status = $productModel->status === 'active'
            ? 'hidden'
            : 'active';

        $productModel->save();

        $label = $productModel->status === 'active' ? 'Đang bán' : 'Đã ẩn';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status' => $productModel->status,
                'label' => $label,
            ]);
        }

        return back()->with('success', "Đã đổi trạng thái sản phẩm: {$label}.");
    }

    /**
     * Đồng bộ biến thể; giữ ảnh cũ nếu không tải ảnh mới.
     * Trả về đường dẫn ảnh cũ cần xóa sau khi transaction thành công.
     */
    private function syncVariantsSafely(Product $product, array $variants): array
    {
        $keptIds = [];
        $pathsToDelete = [];

        foreach ($variants as $variantData) {
            $variantId = $variantData['variant_id'] ?? null;
            $newImagePath = $variantData['image_url'] ?? null;

            if ($variantId) {
                $variant = $product->variants()
                    ->whereKey((int) $variantId)
                    ->first();

                if (! $variant) {
                    throw ValidationException::withMessages([
                        'variants' => 'Mẫu mã không thuộc sản phẩm này.',
                    ]);
                }

                $attributes = [
                    'variant_name' => $variantData['variant_name'],
                    'price' => $variantData['price'],
                    'stock_quantity' => $variantData['stock_quantity'],
                ];

                if ($newImagePath) {
                    if ($variant->image_url) {
                        $pathsToDelete[] = $variant->image_url;
                    }

                    $attributes['image_url'] = $newImagePath;
                }

                $variant->update($attributes);
            } else {
                $variant = $product->variants()->create([
                    'variant_name' => $variantData['variant_name'],
                    'price' => $variantData['price'],
                    'stock_quantity' => $variantData['stock_quantity'],
                    'image_url' => $newImagePath,
                ]);
            }

            $keptIds[] = (int) $variant->variant_id;
        }

        $variantsToRemove = $product->variants()
            ->when(
                $keptIds !== [],
                fn ($query) => $query->whereNotIn('variant_id', $keptIds)
            )
            ->get();

        foreach ($variantsToRemove as $variant) {
            $isInCart = DB::table('cart_items')
                ->where('variant_id', $variant->variant_id)
                ->exists();

            $isInOrder = DB::table('order_items')
                ->where('product_variant_id', $variant->variant_id)
                ->exists();

            if ($isInCart || $isInOrder) {
                throw ValidationException::withMessages([
                    'variants' => "Không thể xóa mẫu mã “{$variant->variant_name}” vì đang được giỏ hàng hoặc đơn hàng tham chiếu.",
                ]);
            }

            if ($variant->image_url) {
                $pathsToDelete[] = $variant->image_url;
            }

            $variant->delete();
        }

        return array_values(array_unique(array_filter($pathsToDelete)));
    }

    private function insertGalleryImages(Product $product, array $paths): void
    {
        $nextSortOrder = (int) ($product->images()->max('sort_order') ?? 0) + 1;

        foreach ($paths as $path) {
            $product->images()->create([
                'image_url' => $path,
                'sort_order' => $nextSortOrder++,
            ]);
        }
    }

    private function insertVariants(Product $product, array $variants): void
    {
        foreach ($variants as $variant) {
            $product->variants()->create([
                'variant_name' => $variant['variant_name'],
                'price' => $variant['price'],
                'stock_quantity' => $variant['stock_quantity'],
                'image_url' => $variant['image_url'] ?? null,
            ]);
        }
    }

    /**
     * Chuẩn hóa ảnh tải lên thành PNG hoặc JPG.
     */
    private function storeConvertedImage(
        \Illuminate\Http\UploadedFile $file
    ): string {
        $image = ImageManager::gd()->read($file->getPathname());
        $isPng = $file->getMimeType() === 'image/png';

        $extension = $isPng ? 'png' : 'jpg';
        $encodedImage = $isPng
            ? $image->toPng()
            : $image->toJpeg(85);

        $path = 'products/' . Str::uuid() . '.' . $extension;

        Storage::disk('public')->put($path, (string) $encodedImage);

        return $path;
    }

    private function currentSeller(): Seller
    {
        $seller = request()->user()?->seller;

        abort_unless(
            $seller instanceof Seller,
            403,
            'Tài khoản hiện tại chưa liên kết với cửa hàng Seller.'
        );

        return $seller;
    }

    private function ownedProduct(int $id): Product
    {
        $seller = $this->currentSeller();

        return Product::query()
            ->where('seller_id', $seller->seller_id)
            ->findOrFail($id);
    }

    private function categoryTree()
    {
        return Category::query()
            ->whereNull('parent_id')
            ->with([
                'children' => fn ($query) => $query->orderBy('category_name'),
            ])
            ->orderBy('category_name')
            ->get();
    }
}
