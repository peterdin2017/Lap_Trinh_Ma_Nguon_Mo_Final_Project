<?php

namespace Tests\Feature\Store;

use App\Models\Account;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Seller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    private Seller $shop;
    private Category $root;
    private Category $child;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shop = $this->makeShop('shop@example.com', 'active');
        $this->root = Category::create(['category_name' => 'Điện thoại & Phụ kiện']);
        $this->child = Category::create(['category_name' => 'Ốp lưng', 'parent_id' => $this->root->category_id]);
    }

    /* ------------------------------ Hiển thị ------------------------------ */

    public function test_list_only_shows_active_products_of_active_shops(): void
    {
        $this->makeProduct(['product_name' => 'Hàng đang bán']);
        $this->makeProduct(['product_name' => 'Hàng đã ẩn', 'status' => 'hidden']);
        $this->makeProduct(['product_name' => 'Hàng bị khóa', 'status' => 'locked']);
        $this->makeProduct(['product_name' => 'Hàng shop chờ duyệt'], $this->makeShop('p@example.com', 'pending'));
        $this->makeProduct(['product_name' => 'Hàng shop bị đình chỉ'], $this->makeShop('s@example.com', 'suspended'));

        $this->get(route('store.products.index'))
            ->assertOk()
            ->assertSee('Hàng đang bán')
            ->assertDontSee('Hàng đã ẩn')
            ->assertDontSee('Hàng bị khóa')
            ->assertDontSee('Hàng shop chờ duyệt')
            ->assertDontSee('Hàng shop bị đình chỉ');
    }

    public function test_detail_returns_404_for_hidden_locked_or_inactive_shop(): void
    {
        $hidden = $this->makeProduct(['status' => 'hidden']);
        $locked = $this->makeProduct(['status' => 'locked']);
        $pending = $this->makeProduct([], $this->makeShop('p@example.com', 'pending'));
        $ok = $this->makeProduct(['product_name' => 'Sản phẩm bình thường']);

        $this->get(route('store.products.show', $hidden->product_id))->assertNotFound();
        $this->get(route('store.products.show', $locked->product_id))->assertNotFound();
        $this->get(route('store.products.show', $pending->product_id))->assertNotFound();
        $this->get(route('store.products.show', $ok->product_id))->assertOk()->assertSee('Sản phẩm bình thường');
    }

    /* --------------------------- Tìm kiếm / lọc --------------------------- */

    public function test_search_matches_name_and_brand_and_escapes_wildcards(): void
    {
        $this->makeProduct(['product_name' => 'Tai nghe Bluetooth', 'brand' => 'Sonic']);
        $this->makeProduct(['product_name' => 'Chuột không dây', 'brand' => 'Logi']);
        $this->makeProduct(['product_name' => 'Giảm 50% hôm nay', 'brand' => 'Promo']);

        $this->get(route('store.products.index', ['q' => 'bluetooth']))
            ->assertSee('Tai nghe Bluetooth')->assertDontSee('Chuột không dây');

        $this->get(route('store.products.index', ['q' => 'Logi']))
            ->assertSee('Chuột không dây')->assertDontSee('Tai nghe Bluetooth');

        // '%' là ký tự thường, không phải wildcard.
        $this->get(route('store.products.index', ['q' => '%']))
            ->assertSee('Giảm 50% hôm nay')->assertDontSee('Tai nghe Bluetooth');
    }

    public function test_selecting_parent_category_includes_child_category_products(): void
    {
        $this->makeProduct(['product_name' => 'Ốp lưng con', 'category_id' => $this->child->category_id]);
        $this->makeProduct(['product_name' => 'Điện thoại cha', 'category_id' => $this->root->category_id]);
        $other = Category::create(['category_name' => 'Đồng hồ']);
        $this->makeProduct(['product_name' => 'Đồng hồ khác', 'category_id' => $other->category_id]);

        $this->get(route('store.products.index', ['category' => $this->root->category_id]))
            ->assertSee('Ốp lưng con')->assertSee('Điện thoại cha')->assertDontSee('Đồng hồ khác');

        $this->get(route('store.products.index', ['category' => $this->child->category_id]))
            ->assertSee('Ốp lưng con')->assertDontSee('Điện thoại cha');
    }

    public function test_category_descendants_survive_corrupt_cycle(): void
    {
        // Dữ liệu sai: cha trỏ ngược về con. Không được lặp vô hạn.
        DB::table('categories')->where('category_id', $this->root->category_id)
            ->update(['parent_id' => $this->child->category_id]);

        $ids = app(\App\Services\Store\ProductCatalog::class)->descendantIds($this->root->category_id);

        $this->assertEqualsCanonicalizing([$this->root->category_id, $this->child->category_id], $ids);
    }

    public function test_price_filter_and_sort(): void
    {
        $this->makeProduct(['product_name' => 'Rẻ', 'price' => 100000]);
        $this->makeProduct(['product_name' => 'Giữa', 'price' => 500000]);
        $this->makeProduct(['product_name' => 'Đắt', 'price' => 900000]);

        $this->get(route('store.products.index', ['min_price' => 400000, 'max_price' => 600000]))
            ->assertSee('Giữa')->assertDontSee('Rẻ')->assertDontSee('Đắt');

        $asc = $this->get(route('store.products.index', ['sort' => 'price_asc']))
            ->viewData('products')->pluck('product_name')->all();
        $this->assertSame(['Rẻ', 'Giữa', 'Đắt'], $asc);

        $desc = $this->get(route('store.products.index', ['sort' => 'price_desc']))
            ->viewData('products')->pluck('product_name')->all();
        $this->assertSame(['Đắt', 'Giữa', 'Rẻ'], $desc);

        $best = $this->makeProduct(['product_name' => 'Bán chạy', 'sold_count' => 99]);
        $first = $this->get(route('store.products.index', ['sort' => 'best_selling']))
            ->viewData('products')->first();
        $this->assertSame($best->product_id, $first->product_id);
    }

    public function test_reversed_price_range_is_swapped_and_bad_input_rejected(): void
    {
        $this->makeProduct(['product_name' => 'Giữa', 'price' => 500000]);

        $this->get(route('store.products.index', ['min_price' => 900000, 'max_price' => 100000]))
            ->assertOk()->assertSee('Giữa');

        $this->get(route('store.products.index', ['min_price' => -5]))->assertSessionHasErrors('min_price');
        $this->get(route('store.products.index', ['sort' => 'bogus']))->assertSessionHasErrors('sort');
    }

    public function test_pagination_keeps_filters(): void
    {
        for ($i = 1; $i <= 14; $i++) {
            $this->makeProduct(['product_name' => "Áo số {$i}"]);
        }

        $this->get(route('store.products.index', ['q' => 'Áo', 'sort' => 'price_asc']))
            ->assertOk()
            ->assertSee('sort=price_asc', false)
            ->assertSee('page=2', false);
    }

    public function test_header_search_form_and_category_links_are_wired(): void
    {
        $this->makeProduct();

        $this->get(route('store.home'))
            ->assertOk()
            ->assertSee(route('store.products.index'), false)
            ->assertSee('category=' . $this->root->category_id, false)
            ->assertSee('name="q"', false);
    }

    /* ------------------------ Trang chi tiết / mua hàng ------------------------ */

    public function test_detail_requires_choosing_variant_and_shows_both_buy_buttons(): void
    {
        $product = $this->makeProduct(['price' => 8990000, 'stock_quantity' => 4]);
        $product->variants()->create(['variant_name' => 'Đen 128GB', 'price' => 8990000, 'stock_quantity' => 4]);
        $product->variants()->create(['variant_name' => 'Trắng 512GB', 'price' => 11990000, 'stock_quantity' => 0]);

        $response = $this->get(route('store.products.show', $product->product_id))->assertOk();

        $response->assertSee('Đen 128GB')
            ->assertSee('8.990.000₫ - 11.990.000₫')      // chưa chọn mẫu: hiện khoảng giá
            ->assertSee('Chọn mẫu mã để xem tình trạng')
            ->assertSee('name="intent" value="cart"', false)
            ->assertSee('name="intent" value="buy_now"', false)
            ->assertSee('id="selectedVariantId"', false);

        // Mẫu hết hàng bị khóa (disabled), mẫu còn hàng thì không.
        $html = $response->getContent();
        $this->assertMatchesRegularExpression('/data-variant-id="\d+"[^>]*data-stock="0"[^>]*disabled/s', $html);
    }

    public function test_variant_id_posted_by_the_form_is_the_one_added_to_cart(): void
    {
        // Hồi quy cho lỗi cũ: JS không cập nhật variant_id nên luôn thêm mẫu đầu tiên.
        $account = $this->makeBuyer('buyer@example.com');
        $product = $this->makeProduct(['stock_quantity' => 8]);
        $first = $product->variants()->create(['variant_name' => 'A', 'price' => 100000, 'stock_quantity' => 5]);
        $second = $product->variants()->create(['variant_name' => 'B', 'price' => 120000, 'stock_quantity' => 3]);

        $this->withSession(['account_id' => $account->account_id])
            ->post(route('store.cart.items.store'), [
                'product_id' => $product->product_id,
                'variant_id' => $second->variant_id,
                'quantity' => 2,
                'intent' => 'buy_now',
            ])->assertRedirect(route('store.cart.index'));

        $this->assertDatabaseHas('cart_items', ['variant_id' => $second->variant_id, 'quantity' => 2]);
        $this->assertDatabaseMissing('cart_items', ['variant_id' => $first->variant_id]);
    }

    public function test_guest_buying_is_sent_to_login(): void
    {
        $product = $this->makeProduct();

        $this->from(route('store.products.show', $product->product_id))
            ->post(route('store.cart.items.store'), [
                'product_id' => $product->product_id, 'quantity' => 1, 'intent' => 'cart',
            ])
            ->assertRedirect(route('login.form'));
    }

    /* ------------------------------ Đánh giá ------------------------------ */

    public function test_only_buyers_of_delivered_orders_can_review_once_and_rating_is_updated(): void
    {
        $this->createOrderTables();
        $product = $this->makeProduct();
        $buyer = $this->makeBuyer('buyer@example.com');
        $stranger = $this->makeBuyer('stranger@example.com');

        $orderId = DB::table('orders')->insertGetId(['account_id' => $buyer->account_id, 'status' => 'shipping']);
        DB::table('order_items')->insert(['order_id' => $orderId, 'product_id' => $product->product_id]);
        $url = route('store.products.reviews.store', $product->product_id);

        // Đơn chưa giao: chưa được đánh giá.
        $this->withSession(['account_id' => $buyer->account_id])
            ->post($url, ['rating' => 5])->assertSessionHasErrors('review');

        DB::table('orders')->where('order_id', $orderId)->update(['status' => 'delivered']);

        // Người chưa mua: bị chặn.
        $this->withSession(['account_id' => $stranger->account_id])
            ->post($url, ['rating' => 5])->assertSessionHasErrors('review');

        // Điểm sao không hợp lệ.
        $this->withSession(['account_id' => $buyer->account_id])
            ->post($url, ['rating' => 9])->assertSessionHasErrors('rating');

        // Người mua đã nhận hàng: được đánh giá, products.rating_* được cập nhật.
        $this->withSession(['account_id' => $buyer->account_id])
            ->post($url, ['rating' => 4, 'comment' => 'Tốt'])->assertSessionHas('review_success');

        $this->assertSame(1, Review::count());
        $product->refresh();
        $this->assertSame(1, (int) $product->rating_count);
        $this->assertEquals(4.0, (float) $product->rating_avg);

        // Cùng dòng hàng, đánh giá lần 2: bị chặn.
        $this->withSession(['account_id' => $buyer->account_id])
            ->post($url, ['rating' => 1])->assertSessionHasErrors('review');
        $this->assertSame(1, Review::count());

        // Hiển thị nhận xét trên trang chi tiết.
        $this->get(route('store.products.show', $product->product_id))->assertSee('Tốt');
    }

    public function test_guest_cannot_post_review(): void
    {
        $product = $this->makeProduct();

        $this->post(route('store.products.reviews.store', $product->product_id), ['rating' => 5])
            ->assertRedirect(route('login.form'));
        $this->assertSame(0, Review::count());
    }

    /* ------------------------------- Helper ------------------------------- */

    private function makeShop(string $email, string $status): Seller
    {
        $account = Account::create([
            'email' => $email,
            'password_hash' => 'secret123',
            'role' => 'seller',
            'status' => 'active',
            'is_verified' => true,
        ]);

        return Seller::create([
            'account_id' => $account->account_id,
            'shop_name' => 'Shop ' . $email,
            'status' => $status,
        ]);
    }

    private function makeBuyer(string $email): Account
    {
        return Account::create([
            'email' => $email,
            'password_hash' => 'secret123',
            'role' => 'user',
            'status' => 'active',
            'is_verified' => true,
        ]);
    }

    /** status/sold_count không nằm trong $fillable nên dùng forceCreate. */
    private function makeProduct(array $attrs = [], ?Seller $shop = null): Product
    {
        static $n = 0;
        $n++;

        return Product::forceCreate(array_merge([
            'seller_id' => ($shop ?? $this->shop)->seller_id,
            'category_id' => $this->root->category_id,
            'product_name' => "Sản phẩm {$n}",
            'brand' => 'Brand',
            'price' => 100000,
            'stock_quantity' => 10,
            'status' => 'active',
        ], $attrs));
    }

    /** Bảng tối thiểu theo giả định schema của Người 2. */
    private function createOrderTables(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->bigIncrements('order_id');
            $t->unsignedBigInteger('account_id');
            $t->string('status');
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->bigIncrements('order_item_id');
            $t->unsignedBigInteger('order_id');
            $t->unsignedBigInteger('product_id');
        });
    }
}
