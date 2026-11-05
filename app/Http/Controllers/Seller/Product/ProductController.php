<?php

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Product\SellerProductFilterRequest;
use App\Http\Requests\Seller\Product\StoreProductRequest;
use App\Http\Requests\Seller\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Throwable;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm của Seller hiện đăng nhập.
     */
    public function index(SellerProductFilterRequest $request): View|JsonResponse
    {
        $seller = $this->currentSeller();
        $filters = $request->validated();

        $status = (string) ($filters['status'] ?? 'all');
        $search = trim((string) ($filters['q'] ?? ''));
        $categoryId = $filters['category'] ?? null;
        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;

        if ($from && $to && $from > $to) {
            [$from, $to] = [$to, $from];
        }

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
                fn($query) => $query->where('category_id', $categoryId)
            )
            ->when($from, fn($query) => $query->whereDate('published_at', '>=', $from))
            ->when($to, fn($query) => $query->whereDate('published_at', '<=', $to)
            );

        match ($status) {
            'active' => $productsQuery
                ->where('status', 'active')
                ->where('stock_quantity', '>', 0),

            'draft' => $productsQuery->where('status', 'draft'),

            // DB không có trạng thái out_of_stock; suy ra từ tồn kho.
            'out_of_stock' => $productsQuery->where('stock_quantity', 0),

            'hidden' => $productsQuery->whereIn('status', ['hidden', 'locked']),

            default => null,
        };

        $products = $productsQuery
            ->latest('product_id')
            ->paginate(10)
            ->withQueryString();

        $productsVersion = hash(
            'sha256',
            json_encode([
                'products' => $products->getCollection()
                    ->map(fn (Product $product): array => [
                        (int) $product->product_id,
                        (string) $product->status,
                        (int) $product->stock_quantity,
                        $product->updated_at?->getTimestamp(),
                    ])
                    ->values()
                    ->all(),
                'total' => (int) $products->total(),
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
        );

        if ($request->boolean('refresh')) {
            return response()->json(['version' => $productsVersion]);
        }

        $categories = Category::query()
            ->orderBy('category_name')
            ->get(['category_id', 'category_name']);

        return view('seller.products.index', compact(
            'products',
            'productsVersion',
            'categories',
            'status',
            'search',
            'categoryId',
            'from',
            'to'
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
     *
     * Tạo sản phẩm trước để lấy product_id, sau đó lưu ảnh vào thư mục:
     * products/{seller_id}/{product_id}/
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $seller = $this->currentSeller();
        $data = $request->validated();
        $variants = $data['variants'] ?? [];
        $status = $data['status'];

        unset(
            $data['image'],
            $data['images'],
            $data['variants'],
            $data['variants_present'],
            $data['status']
        );

        if (array_key_exists('description', $data)) {
            $data['description'] = $this->normalizeDescription(
                $data['description']
            );
        }

        if ($variants !== []) {
            $data['price'] = min(array_column($variants, 'price'));
            $data['stock_quantity'] = array_sum(
                array_column($variants, 'stock_quantity')
            );
        }

        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $request,
                $seller,
                $data,
                $status,
                &$variants,
                &$storedPaths
            ): void {
                // Tạo sản phẩm trước để lấy product_id.
                $product = new Product();
                $product->fill($data);
                $product->seller_id = $seller->seller_id;
                $product->status = $status;
                $product->published_at = $status === 'active' ? now() : null;
                $product->save();

                // Lưu ảnh chính.
                if ($request->hasFile('image')) {
                    $mainImagePath = $this->storeConvertedImage(
                        $request->file('image'),
                        (int) $seller->seller_id,
                        (int) $product->product_id
                    );

                    $product->image_url = $mainImagePath;
                    $product->save();

                    $storedPaths[] = $mainImagePath;
                }

                // Lưu ảnh phụ.
                $extraImagePaths = [];

                foreach ($request->file('images', []) as $image) {
                    $path = $this->storeConvertedImage(
                        $image,
                        (int) $seller->seller_id,
                        (int) $product->product_id
                    );

                    $extraImagePaths[] = $path;
                    $storedPaths[] = $path;
                }

                // Lưu ảnh riêng cho từng mẫu mã.
                foreach ($variants as $index => &$variant) {
                    $variantImage = $request->file(
                        "variants.{$index}.image"
                    );

                    if ($variantImage) {
                        $path = $this->storeConvertedImage(
                            $variantImage,
                            (int) $seller->seller_id,
                            (int) $product->product_id
                        );

                        $variant['image_url'] = $path;
                        $storedPaths[] = $path;
                    }
                }
                unset($variant);

                $this->insertGalleryImages($product, $extraImagePaths);
                $this->insertVariants($product, $variants);
            });
        } catch (Throwable $exception) {
            // Transaction thất bại thì xóa các file đã lưu lên disk.
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
        $pricingMode = $data['pricing_mode'] ?? 'single';
        $variants = $data['variants'] ?? [];
        $removeIds = $data['remove_image_ids'] ?? [];
        $newImages = $request->file('images', []);
        $status = $data['status'] ?? $productModel->status;
        $variantsPresent = (bool) ($data['variants_present'] ?? false);

        // Đồng bộ mẫu mã khi đang dùng mẫu mã hoặc form có gửi cờ variants_present.
        $variantsWereSubmitted = $pricingMode === 'variants'
            || $variantsPresent
            || array_key_exists('variants', $data);

        unset(
            $data['image'],
            $data['images'],
            $data['remove_image_ids'],
            $data['variants'],
            $data['variants_present'],
            $data['pricing_mode'],
            $data['status']
        );

        if ($pricingMode === 'variants') {
            if ($variants === []) {
                throw ValidationException::withMessages([
                    'variants' => 'Vui lòng thêm ít nhất một mẫu mã.',
                ]);
            }

            // Giá sản phẩm là giá thấp nhất trong các mẫu mã.
            $data['price'] = min(array_column($variants, 'price'));

            // Tồn kho sản phẩm là tổng tồn kho của các mẫu mã.
            $data['stock_quantity'] = array_sum(
                array_column($variants, 'stock_quantity')
            );
        }

        if (array_key_exists('description', $data)) {
            $data['description'] = $this->normalizeDescription(
                $data['description']
            );
        }

        $removeIds = collect($removeIds)
            ->filter(fn($id) => is_numeric($id))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $imagesToRemove = $productModel->images
            ->whereIn('image_id', $removeIds)
            ->values();

        if ($imagesToRemove->count() !== count($removeIds)) {
            return back()
                ->withErrors([
                    'images' => 'Danh sách ảnh cần xóa không hợp lệ.',
                ])
                ->withInput();
        }

        $remainingImageCount = $productModel->images->count()
            - $imagesToRemove->count();

        if ($remainingImageCount + count($newImages) > 8) {
            return back()
                ->withErrors([
                    'images' => 'Sản phẩm chỉ được có tối đa 8 ảnh phụ.',
                ])
                ->withInput();
        }

        $oldPathsToDelete = $imagesToRemove->pluck('image_url')->all();
        $newPaths = [];
        $newGalleryPaths = [];

        try {
            // Ảnh mẫu mã mới. Không chọn ảnh mới thì giữ ảnh hiện tại.
            foreach ($variants as $index => &$variant) {
                $variantImage = $request->file(
                    "variants.{$index}.image"
                );

                if ($variantImage) {
                    $path = $this->storeConvertedImage(
                        $variantImage,
                        (int) $seller->seller_id,
                        (int) $productModel->product_id
                    );

                    $variant['image_url'] = $path;
                    $newPaths[] = $path;
                }
            }
            unset($variant);

            // Ảnh chính mới.
            if ($request->hasFile('image')) {
                $newMainImagePath = $this->storeConvertedImage(
                    $request->file('image'),
                    (int) $seller->seller_id,
                    (int) $productModel->product_id
                );

                $newPaths[] = $newMainImagePath;

                if ($productModel->image_url) {
                    $oldPathsToDelete[] = $productModel->image_url;
                }

                $data['image_url'] = $newMainImagePath;
            }

            // Ảnh phụ mới.
            foreach ($newImages as $image) {
                $path = $this->storeConvertedImage(
                    $image,
                    (int) $seller->seller_id,
                    (int) $productModel->product_id
                );

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
                if ($status === 'active' && ! $productModel->published_at) {
                    $productModel->published_at = now();
                }
                $productModel->save();

                foreach ($imagesToRemove as $image) {
                    $image->delete();
                }

                $this->insertGalleryImages(
                    $productModel,
                    $newGalleryPaths
                );

                if ($variantsWereSubmitted) {
                    $oldPathsToDelete = array_merge(
                        $oldPathsToDelete,
                        $this->syncVariantsSafely(
                            $productModel,
                            $variants
                        )
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

        if ($productModel->status === 'active' && ! $productModel->published_at) {
            $productModel->published_at = now();
        }

        $productModel->save();

        $label = $productModel->status === 'active' ? 'Đang bán' : 'Đã ẩn';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'status' => $productModel->status,
                'label' => $label,
            ]);
        }

        return back()->with(
            'success',
            "Đã đổi trạng thái sản phẩm: {$label}."
        );
    }

    /**
     * Đồng bộ biến thể; giữ ảnh cũ nếu không tải ảnh mới.
     * Trả về đường dẫn ảnh cũ cần xóa sau khi transaction thành công.
     */
    private function syncVariantsSafely(
        Product $product,
        array $variants
    ): array {
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
                fn($query) => $query->whereNotIn(
                    'variant_id',
                    $keptIds
                )
            )
            ->get();

        foreach ($variantsToRemove as $variant) {
            $isInCart = DB::table('cart_items')
                ->where('variant_id', $variant->variant_id)
                ->exists();

            $isInOrder = DB::table('order_items')
                ->where(
                    'product_variant_id',
                    $variant->variant_id
                )
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

        return array_values(
            array_unique(array_filter($pathsToDelete))
        );
    }

    private function insertGalleryImages(
        Product $product,
        array $paths
    ): void {
        $nextSortOrder = (int) (
            $product->images()->max('sort_order') ?? 0
        ) + 1;

        foreach ($paths as $path) {
            $product->images()->create([
                'image_url' => $path,
                'sort_order' => $nextSortOrder++,
            ]);
        }
    }

    private function insertVariants(
        Product $product,
        array $variants
    ): void {
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
     * Chuyển mô tả về văn bản thường, giữ lại ngắt dòng cơ bản.
     */
    private function normalizeDescription(?string $description): ?string
    {
        if ($description === null) {
            return null;
        }

        $description = html_entity_decode(
            $description,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        $description = preg_replace(
            '~<\s*(br\s*/?|/p|/div|/li)\s*>~i',
            "\n",
            $description
        );

        $description = strip_tags($description);
        $description = preg_replace('/[ \t]+/u', ' ', $description);
        $description = preg_replace(
            '/\n{3,}/u',
            "\n\n",
            trim($description)
        );

        return $description;
    }

    /**
     * Chuẩn hóa ảnh tải lên thành PNG hoặc JPG rồi lưu vào:
     * products/{seller_id}/{product_id}/{seller_id}-{product_id}-{random}.{extension}
     */
    private function storeConvertedImage(
        UploadedFile $file,
        int $sellerId,
        int $productId
    ): string {
        $image = ImageManager::gd()->read($file->getPathname());
        $isPng = $file->getMimeType() === 'image/png';

        $extension = $isPng ? 'png' : 'jpg';
        $encodedImage = $isPng
            ? $image->toPng()
            : $image->toJpeg(85);

        $directory = "products/{$sellerId}/{$productId}";
        $disk = Storage::disk('public');

        // Sinh số ngẫu nhiên mới nếu tên file đã tồn tại.
        do {
            $randomNumber = random_int(100000, 999999);
            $filename = "{$sellerId}-{$productId}-{$randomNumber}.{$extension}";
            $path = "{$directory}/{$filename}";
        } while ($disk->exists($path));

        $disk->put($path, (string) $encodedImage);

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
                'children' => fn($query) => $query->orderBy(
                    'category_name'
                ),
            ])
            ->orderBy('category_name')
            ->get();
    }
}
