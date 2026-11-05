<?php

namespace App\Http\Controllers\Seller\Chat;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function index(Request $request): View
    {
        $account = $request->user();
        abort_unless($account instanceof Account && $account->seller, 403);

        $conversations = $this->conversationsFor($account);

        return view('seller.chat.index', [
            'conversations' => $conversations,
            'activeConversation' => null,
            'initialMessages' => collect(),
        ]);
    }

    public function updates(Request $request): JsonResponse
    {
        $account = $request->user();
        abort_unless($account instanceof Account && $account->seller, 403);

        $conversations = $this->conversationsFor($account);
        $unreadTotal = (int) $conversations->sum('unread_count');

        return response()->json([
            'unread_total' => $unreadTotal,
            'shop_name' => $account->seller?->shop_name ?? 'Seller Center',
            'conversations' => $conversations->map(
                fn (Conversation $conversation) => [
                    'id' => $conversation->conversation_id,
                    'customer' => $conversation->customer?->email ?? 'Khách hàng',
                    'last_message' => $conversation->latestMessage?->body
                        ?? 'Chưa có tin nhắn',
                    'latest_message_id' => $conversation->latestMessage?->message_id,
                    'latest_message_from_customer' => $conversation->latestMessage
                        ? (int) $conversation->latestMessage->sender_account_id === (int) $conversation->customer_account_id
                        : false,
                    'last_message_at' => $conversation->last_message_at?->format('H:i'),
                    'unread_count' => (int) $conversation->unread_count,
                    'url' => route('seller.chat.show', $conversation),
                ]
            )->values(),
        ]);
    }

    public function show(Request $request, int $conversationId): View
    {
        $account = $request->user();
        abort_unless($account instanceof Account && $account->seller, 403);

        $conversations = $this->conversationsFor($account);

        $activeConversation = $conversations->firstWhere(
            'conversation_id',
            $conversationId
        );

        abort_unless($activeConversation, 404);

        $activeConversation->messages()
            ->where('sender_account_id', '!=', $account->account_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $initialMessages = $activeConversation->messages()
            ->orderBy('message_id')
            ->limit(100)
            ->get();

        return view('seller.chat.index', compact(
            'conversations',
            'activeConversation',
            'initialMessages'
        ));
    }

    private function conversationsFor(Account $account): Collection
    {
        return Conversation::query()
            ->where('seller_id', $account->seller->seller_id)
            ->with(['customer', 'latestMessage'])
            ->withCount([
                'messages as unread_count' => fn ($query) => $query
                    ->whereNull('read_at')
                    ->where('sender_account_id', '!=', $account->account_id),
            ])
            ->orderByDesc('last_message_at')
            ->get();
    }
}
