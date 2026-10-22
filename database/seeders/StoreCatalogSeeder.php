<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Seeder;

/**
 * Dữ liệu mẫu để thử Store:
 *   php artisan db:seed --class=StoreCatalogSeeder
 * Gồm cả sản phẩm hidden/locked và shop pending để kiểm tra quy tắc ẩn.
 */
class StoreCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $phone = Category::firstOrCreate(['category_name' => 'Điện Thoại & Phụ Kiện', 'parent_id' => null]);
        $smartphone = Category::firstOrCreate(['category_name' => 'Điện thoại', 'parent_id' => $phone->category_id]);
        $case = Category::firstOrCreate(['category_name' => 'Ốp lưng', 'parent_id' => $phone->category_id]);
        $laptop = Category::firstOrCreate(['category_name' => 'Máy Tính & Laptop', 'parent_id' => null]);
        $fashion = Category::firstOrCreate(['category_name' => 'Thời Trang Nam', 'parent_id' => null]);
        $watch = Category::firstOrCreate(['category_name' => 'Đồng Hồ', 'parent_id' => null]);

        $activeShop = $this->shop('demo.seller@example.com', 'Demo Mobile Store', 'active');
        $pendingShop = $this->shop('pending.seller@example.com', 'Shop Chờ Duyệt', 'pending');

        // Có phân loại: products.price = giá thấp nhất, stock_quantity = tổng tồn (cùng quy ước với Seller).
        $phoneProduct = $this->product($activeShop, $smartphone, 'Điện thoại Xphone 12', 'Xphone', 8990000, 13, 'active', 120);
        foreach ([
            ['Đen 128GB', 8990000, 10],
            ['Xanh 256GB', 9990000, 3],
            ['Trắng 512GB', 11990000, 0], // hết hàng
        ] as [$name, $price, $stock]) {
            $phoneProduct->variants()->firstOrCreate(
                ['variant_name' => $name],
                ['price' => $price, 'stock_quantity' => $stock]
            );
        }

        // Không phân loại.
        $this->product($activeShop, $case, 'Ốp lưng chống sốc Xphone 12', 'Armor', 149000, 50, 'active', 340);
        $this->product($activeShop, $laptop, 'Laptop Zbook 14', 'Zbook', 15990000, 5, 'active', 12);
        $this->product($activeShop, $fashion, 'Áo sơ mi nam Oxford', 'Oxford', 299000, 40, 'active', 80);
        $this->product($activeShop, $watch, 'Đồng hồ Classic 40mm', 'Classic', 1250000, 0, 'active', 7); // hết hàng

        // Phải KHÔNG xuất hiện ở Store.
        $this->product($activeShop, $watch, 'Đồng hồ đã ẩn', 'Classic', 900000, 5, 'hidden');
        $this->product($activeShop, $watch, 'Đồng hồ bị khóa', 'Classic', 900000, 5, 'locked');
        $this->product($pendingShop, $watch, 'Đồng hồ của shop chờ duyệt', 'Classic', 900000, 5, 'active');

        // Thêm dữ liệu để thử phân trang.
        for ($i = 1; $i <= 20; $i++) {
            $this->product($activeShop, $fashion, "Áo thun nam mẫu {$i}", 'Basic', 100000 + $i * 10000, 20, 'active', $i);
        }
    }

    private function shop(string $email, string $name, string $status): Seller
    {
        $account = Account::firstOrCreate(
            ['email' => $email, 'role' => 'seller'],
            ['password_hash' => 'password123', 'status' => 'active', 'is_verified' => true]
        );

        return Seller::firstOrCreate(
            ['account_id' => $account->account_id],
            ['shop_name' => $name, 'status' => $status]
        );
    }

    private function product(Seller $seller, Category $category, string $name, string $brand, int $price, int $stock, string $status, int $sold = 0): Product
    {
        $product = Product::firstOrCreate(
            ['seller_id' => $seller->seller_id, 'product_name' => $name],
            [
                'category_id' => $category->category_id,
                'brand' => $brand,
                'description' => "Mô tả mẫu cho {$name}.\nGiao hàng toàn quốc.",
                'price' => $price,
                'stock_quantity' => $stock,
            ]
        );

        // status/sold_count không nằm trong $fillable nên cập nhật riêng.
        $product->forceFill([
            'status' => $status,
            'sold_count' => $sold,
            'locked_reason' => $status === 'locked' ? 'Vi phạm chính sách (dữ liệu mẫu)' : null,
            'locked_at' => $status === 'locked' ? now() : null,
        ])->save();

        return $product;
    }
}
