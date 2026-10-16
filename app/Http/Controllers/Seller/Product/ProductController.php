<?php

namespace App\Http\Controllers\Seller\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Product\StoreProductRequest;
use App\Http\Requests\Seller\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Throwable;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm của Seller đang đăng nhập.
     */
    public function index(Request $request): View
    {
        $seller = $request->user()->seller;

        $products = Product::query()
            ->with('category')
            ->where('seller_id', $seller->seller_id)
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Hiển thị form thêm sản phẩm.
     */
    public function create(): View
    {
        $categories = Category::query()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('category_name')
            ->get();

        return view('seller.products.create', compact('categories'));
    }

    /**
     * Tạo sản phẩm mới, lưu ảnh chính, ảnh phụ và biến thể.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $seller = $request->user()->seller;
        $data = $request->validated();
        $storedPaths = [];

        // Lấy biến thể ra riêng để không đưa vào bảng products.
        $variants = $data['variants'] ?? [];

        unset(
            $data['image'],
            $data['images'],
            $data['variants'],
            $data['variants_present']
        );

        // Giá sản phẩm là giá thấp nhất trong các biến thể.
        // Tồn kho sản phẩm là tổng tồn của các biến thể.
        if ($variants !== []) {
            $data['price'] = min(array_column($variants, 'price'));
            $data['stock_quantity'] = array_sum(
                array_column($variants, 'stock_quantity')
            );
        }

        try {
            // Lưu ảnh chính.
            $mainImagePath = $this->storeConvertedImage(
                $request->file('image')
            );
            $storedPaths[] = $mainImagePath;

            // Lưu các ảnh phụ.
            $extraImagePaths = [];

            foreach ($request->file('images', []) as $image) {
                $path = $this->storeConvertedImage($image);

                $extraImagePaths[] = $path;
                $storedPaths[] = $path;
            }

            $data['image_url'] = $mainImagePath;
            $data['seller_id'] = $seller->seller_id;

            DB::transaction(function () use (
                $data,
                $extraImagePaths,
                $variants
            ): void {
                $product = Product::create($data);

                // Tạo bản ghi cho ảnh phụ.
                if ($extraImagePaths !== []) {
                    $now = now();
                    $imageRows = [];

                    foreach ($extraImagePaths as $index => $path) {
                        $imageRows[] = [
                            'product_id' => $product->product_id,
                            'image_url' => $path,
                            'sort_order' => $index + 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    DB::table('product_images')->insert($imageRows);
                }

                // Tạo các biến thể nếu sản phẩm có mẫu mã.
                if ($variants !== []) {
                    DB::table('product_variants')->insert(
                        $this->makeVariantRows($product, $variants)
                    );
                }
            });
        } catch (Throwable $exception) {
            // Xóa file vừa tải lên nếu lưu database thất bại.
            Storage::disk('public')->delete($storedPaths);

            throw $exception;
        }

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Đã thêm sản phẩm thành công.');
    }

    /**
     * Hiển thị form sửa sản phẩm của Seller đang đăng nhập.
     */
    public function edit(Request $request, int $product): View
    {
        $seller = $request->user()->seller;

        $product = Product::query()
            ->with(['category', 'images', 'variants'])
            ->where('seller_id', $seller->seller_id)
            ->findOrFail($product);

        $categories = Category::query()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('category_name')
            ->get();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật thông tin, ảnh và danh sách biến thể sản phẩm.
     */
    public function update(
        UpdateProductRequest $request,
        int $product
    ): RedirectResponse {
        $seller = $request->user()->seller;

        // Chỉ cho phép Seller sửa sản phẩm thuộc cửa hàng của mình.
        $product = Product::query()
            ->with('images')
            ->where('seller_id', $seller->seller_id)
            ->findOrFail($product);

        if ($product->status === 'locked') {
            return back()->withErrors([
                'product' => 'Sản phẩm đang bị quản trị viên khóa, không thể chỉnh sửa.',
            ]);
        }

        $data = $request->validated();

        $removeIds = $data['remove_image_ids'] ?? [];
        $newImages = $request->file('images', []);
        $variants = $data['variants'] ?? [];

        // Chỉ thay danh sách biến thể nếu form có gửi trường này.
        $variantsWereSubmitted = $request->boolean('variants_present')
            || array_key_exists('variants', $data);

        unset(
            $data['image'],
            $data['images'],
            $data['remove_image_ids'],
            $data['variants'],
            $data['variants_present']
        );

        if ($variants !== []) {
            $data['price'] = min(array_column($variants, 'price'));
            $data['stock_quantity'] = array_sum(
                array_column($variants, 'stock_quantity')
            );
        }

        // Chỉ xử lý ảnh phụ thuộc sản phẩm đang sửa.
        $imagesToRemove = $product->images
            ->whereIn('image_id', $removeIds)
            ->values();

        if ($imagesToRemove->count() !== count(array_unique($removeIds))) {
            return back()
                ->withErrors([
                    'images' => 'Danh sách ảnh cần xóa không hợp lệ.',
                ])
                ->withInput();
        }

        // Tối đa 8 ảnh phụ sau khi cập nhật.
        $remainingImageCount = $product->images->count()
            - $imagesToRemove->count();

        if ($remainingImageCount + count($newImages) > 8) {
            return back()
                ->withErrors([
                    'images' => 'Sản phẩm chỉ được có tối đa 8 ảnh phụ.',
                ])
                ->withInput();
        }

        $newPaths = [];
        $oldPathsToDelete = $imagesToRemove
            ->pluck('image_url')
            ->all();

        try {
            // Thay ảnh chính nếu Seller tải ảnh mới lên.
            if ($request->hasFile('image')) {
                $newMainImage = $this->storeConvertedImage(
                    $request->file('image')
                );

                $newPaths[] = $newMainImage;

                if ($product->image_url) {
                    $oldPathsToDelete[] = $product->image_url;
                }

                $data['image_url'] = $newMainImage;
            }

            DB::transaction(function () use (
                $product,
                $data,
                $imagesToRemove,
                $newImages,
                $variants,
                $variantsWereSubmitted,
                &$newPaths
            ): void {
                $product->update($data);

                // Xóa các bản ghi ảnh phụ đã chọn.
                foreach ($imagesToRemove as $image) {
                    $image->delete();
                }

                // Thêm ảnh phụ mới.
                $nextSortOrder = (int) (
                    $product->images()->max('sort_order') ?? 0
                ) + 1;

                $now = now();
                $imageRows = [];

                foreach ($newImages as $image) {
                    $path = $this->storeConvertedImage($image);
                    $newPaths[] = $path;

                    $imageRows[] = [
                        'product_id' => $product->product_id,
                        'image_url' => $path,
                        'sort_order' => $nextSortOrder++,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($imageRows !== []) {
                    DB::table('product_images')->insert($imageRows);
                }

                // Thay toàn bộ danh sách biến thể bằng danh sách từ form.
                if ($variantsWereSubmitted) {
                    DB::table('product_variants')
                        ->where('product_id', $product->product_id)
                        ->delete();

                    if ($variants !== []) {
                        DB::table('product_variants')->insert(
                            $this->makeVariantRows($product, $variants)
                        );
                    }
                }
            });
        } catch (Throwable $exception) {
            // Xóa các file mới nếu cập nhật database thất bại.
            Storage::disk('public')->delete($newPaths);

            throw $exception;
        }

        // Xóa ảnh cũ sau khi cập nhật database thành công.
        Storage::disk('public')->delete(
            array_filter($oldPathsToDelete)
        );

        return redirect()
            ->route('seller.products.index')
            ->with('success', 'Đã cập nhật sản phẩm thành công.');
    }

    /**
     * Ẩn sản phẩm hoặc bật bán lại sản phẩm đã ẩn.
     */
    public function toggleVisibility(
        Request $request,
        int $product
    ): RedirectResponse {
        $seller = $request->user()->seller;

        $product = Product::query()
            ->where('seller_id', $seller->seller_id)
            ->findOrFail($product);

        // Seller không được đổi trạng thái sản phẩm bị Admin khóa.
        if ($product->status === 'locked') {
            return back()->withErrors([
                'product' => 'Sản phẩm bị quản trị viên khóa, bạn không thể thay đổi trạng thái.',
            ]);
        }

        $product->status = $product->status === 'active'
            ? 'hidden'
            : 'active';

        $product->save();

        return back()->with(
            'success',
            $product->status === 'hidden'
                ? 'Đã ẩn sản phẩm.'
                : 'Đã hiện lại sản phẩm.'
        );
    }

    /**
     * Chuẩn hóa ảnh:
     * PNG đầu vào được lưu thành PNG; định dạng khác được lưu thành JPG.
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

    /**
     * Chuẩn bị dữ liệu để ghi vào bảng product_variants.
     */
    private function makeVariantRows(Product $product, array $variants): array
    {
        $now = now();
        $rows = [];

        foreach ($variants as $variant) {
            $rows[] = [
                'product_id' => $product->product_id,
                'variant_name' => $variant['variant_name'],
                'price' => $variant['price'],
                'stock_quantity' => $variant['stock_quantity'],
                'image_url' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $rows;
    }
}
