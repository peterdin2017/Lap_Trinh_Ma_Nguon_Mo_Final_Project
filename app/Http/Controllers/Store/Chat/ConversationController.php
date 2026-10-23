<?php

namespace App\Http\Controllers\Store\Chat;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConversationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $account = $this->customer($request);

        $data = $request->validate([
            'seller_id' => ['required', 'integer', 'exists:sellers,seller_id'],
            'product_id' => ['nullable', 'integer', 'exists:products,product_id'],
        ]);

        $seller = Seller::query()
            ->where('seller_id', $data['seller_id'])
            ->where('status', 'active')
            ->firstOrFail();

        if (! empty($data['product_id'])) {
            $validProduct = Product::query()
                ->where('product_id', $data['product_id'])
                ->where('seller_id', $seller->seller_id)
                ->where('status', 'active')
                ->exists();

            if (! $validProduct) {
                throw ValidationException::withMessages([
                    'product_id' => 'Sản phẩm không thuộc cửa hàng này hoặc hiện không khả dụng.',
                ]);
            }
        }

        $conversation = Conversation::query()->firstOrCreate(
            [
                'customer_account_id' => $account->account_id,
                'seller_id' => $seller->seller_id,
            ],
            [
                'product_id' => $data['product_id'] ?? null,
                'status' => 'open',
            ]
        );

        if (! $conversation->product_id && ! empty($data['product_id'])) {
            $conversation->update(['product_id' => $data['product_id']]);
        }

        return response()->json([
            'conversation_id' => $conversation->conversation_id,
            'shop_name' => $seller->shop_name,
            'messages_url' => route('store.chat.messages.index', $conversation),
            'send_url' => route('store.chat.messages.store', $conversation),
        ]);
    }

    private function customer(Request $request): Account
    {
        $accountId = $request->session()->get('account_id');
        $account = is_numeric($accountId)
            ? Account::query()->whereKey((int) $accountId)->first()
            : null;

        abort_unless(
            $account && $account->role === 'user' && $account->status === 'active',
            401,
            'Vui lòng đăng nhập tài khoản khách hàng để chat.'
        );

        return $account;
    }
}
