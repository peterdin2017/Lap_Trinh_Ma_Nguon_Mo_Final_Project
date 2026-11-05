<?php

namespace App\Http\Controllers\Store\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StartConversationRequest;
use App\Models\Account;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ConversationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $account = $this->customer($request);

        $conversations = Conversation::query()
            ->where('customer_account_id', $account->account_id)
            ->with(['seller', 'product', 'latestMessage'])
            ->withCount([
                'messages as unread_count' => fn ($query) => $query
                    ->where('sender_account_id', '!=', $account->account_id)
                    ->whereNull('read_at'),
            ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('conversation_id')
            ->get()
            ->map(fn (Conversation $conversation) => [
                'id' => $conversation->conversation_id,
                'shop_name' => $conversation->seller?->shop_name ?? 'Cửa hàng',
                'shop_avatar_url' => $this->avatarUrl($conversation->seller?->avatar),
                'shop_url' => $conversation->seller
                    ? route('store.shops.show', $conversation->seller->seller_id)
                    : null,
                'product_name' => $conversation->product?->product_name,
                'last_message' => $conversation->latestMessage?->body
                    ?? 'Bắt đầu cuộc trò chuyện',
                'last_message_at' => $conversation->last_message_at?->format('d/m H:i') ?? '',
                'unread_count' => (int) $conversation->unread_count,
                'messages_url' => route('store.chat.messages.index', $conversation),
                'send_url' => route('store.chat.messages.store', $conversation),
            ])
            ->values();

        return response()->json([
            'conversations' => $conversations,
            'unread_total' => (int) $conversations->sum('unread_count'),
        ]);
    }

    public function store(StartConversationRequest $request): JsonResponse
    {
        $account = $this->customer($request);
        $data = $request->validated();

        $seller = Seller::query()
            ->where('seller_id', $data['seller_id'])
            ->availableForStore()
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
            $conversation->update([
                'product_id' => $data['product_id'],
            ]);
        }

        return response()->json([
            'conversation_id' => $conversation->conversation_id,
            'shop_name' => $seller->shop_name,
            'shop_avatar_url' => $this->avatarUrl($seller->avatar),
            'shop_url' => route('store.shops.show', $seller->seller_id),
            'messages_url' => route('store.chat.messages.index', $conversation),
            'send_url' => route('store.chat.messages.store', $conversation),
        ]);
    }

    private function avatarUrl(?string $avatar): ?string
    {
        if (! $avatar) {
            return null;
        }

        if (filter_var($avatar, FILTER_VALIDATE_URL)) {
            return $avatar;
        }

        return asset('storage/' . ltrim($avatar, '/'));
    }

    private function customer(Request $request): Account
    {
        $accountId = $request->session()->get('account_id');

        $account = is_numeric($accountId)
            ? Account::query()->whereKey((int) $accountId)->first()
            : null;

        abort_unless(
            $account
                && $account->role === 'user'
                && $account->status === 'active',
            401,
            'Vui lòng đăng nhập tài khoản khách hàng để chat.'
        );

        return $account;
    }
}
