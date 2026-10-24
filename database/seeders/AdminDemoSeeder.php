<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];
        foreach (range(1, 6) as $i) {
            $email = "demo.customer{$i}@example.com";
            $account = Account::firstOrCreate(
                ['email' => $email, 'role' => 'user'],
                ['password_hash' => Hash::make('Customer@123456'), 'status' => 'active', 'is_verified' => true]
            );
            $users[] = $account;
            DB::table('users')->updateOrInsert(
                ['account_id' => $account->account_id],
                ['full_name' => "Khách hàng mẫu {$i}", 'phone' => '0900000'.str_pad((string)$i, 3, '0', STR_PAD_LEFT), 'updated_at' => now(), 'created_at' => $account->created_at ?? now()]
            );
        }

        foreach ([
            ['pending.two@example.com', 'Cửa hàng đang chờ duyệt 2', 'pending'],
            ['pending.three@example.com', 'Cửa hàng đang chờ duyệt 3', 'pending'],
            ['rejected.demo@example.com', 'Cửa hàng bị từ chối', 'rejected'],
            ['suspended.demo@example.com', 'Cửa hàng tạm đình chỉ', 'suspended'],
        ] as [$email, $name, $status]) {
            $account = Account::firstOrCreate(
                ['email' => $email, 'role' => 'seller'],
                ['password_hash' => Hash::make('Seller@123456'), 'status' => 'active', 'is_verified' => true]
            );
            Seller::firstOrCreate(
                ['account_id' => $account->account_id],
                ['shop_name' => $name, 'status' => $status, 'reject_reason' => $status === 'rejected' ? 'Thiếu giấy tờ xác minh' : null]
            );
        }

        $products = Product::where('status', 'active')->orderBy('product_id')->limit(5)->get();
        foreach ($products as $i => $product) {
            if (!isset($users[$i])) {
                break;
            }
            $exists = DB::table('product_reports')->where('product_id', $product->product_id)
                ->where('account_id', $users[$i]->account_id)->exists();
            if (!$exists) {
                DB::table('product_reports')->insert([
                    'product_id' => $product->product_id,
                    'account_id' => $users[$i]->account_id,
                    'reason' => ['wrong_info', 'fake', 'copyright', 'other', 'prohibited'][$i],
                    'description' => 'Báo cáo thử nghiệm dành cho trang quản trị.',
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $shop = Seller::where('status', 'active')->first();
        $product = $products->first();
        if ($shop && $product && $shop->seller_id === $product->seller_id) {
            foreach (['pending', 'processing', 'shipping', 'completed', 'cancelled'] as $i => $status) {
                $code = 'DEMO-ADMIN-'.str_pad((string)($i + 1), 3, '0', STR_PAD_LEFT);
                $order = DB::table('orders')->where('order_code', $code)->first();
                if (!$order) {
                    $subtotal = (float)$product->price;
                    $id = DB::table('orders')->insertGetId([
                        'order_code' => $code,
                        'account_id' => $users[$i]->account_id,
                        'seller_id' => $shop->seller_id,
                        'shipping_name' => 'Khách hàng mẫu '.($i + 1),
                        'shipping_phone' => '0900000000',
                        'shipping_address' => 'Địa chỉ kiểm thử, TP. Hồ Chí Minh',
                        'payment_method' => 'cod',
                        'payment_status' => $status === 'completed' ? 'paid' : 'pending',
                        'order_status' => $status,
                        'subtotal' => $subtotal,
                        'shipping_fee' => 0,
                        'discount_amount' => 0,
                        'total_amount' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    DB::table('order_items')->insert([
                        'order_id' => $id,
                        'product_id' => $product->product_id,
                        'product_name' => $product->product_name,
                        'price' => $subtotal,
                        'quantity' => 1,
                        'total_price' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
