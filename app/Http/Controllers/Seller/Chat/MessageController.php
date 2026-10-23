<?php

namespace App\Http\Controllers\Seller\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Models\Account;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageController extends Controller
{
    public function index(Request $request, int $conversationId): JsonResponse
    {
        [$account, $conversation] = $this->sellerConversation(
            $request,
            $conversationId
        );

        $afterId = max(0, (int) $request->query('after_id', 0));

        $messages = $conversation->messages()
            ->where('message_id', '>', $afterId)
            ->orderBy('message_id')
            ->get();

        $conversation->messages()
            ->where('sender_account_id', '!=', $account->account_id)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);

        return response()->json([
            'messages' => $messages->map(fn ($message) => [
                'id' => $message->message_id,
                'body' => $message->body,
                'mine' => $message->sender_account_id === $account->account_id,
                'created_at' => $message->created_at?->format('H:i'),
            ]),
        ]);
    }

    public function store(
        SendMessageRequest $request,
        int $conversationId
    ): JsonResponse {
        [$account, $conversation] = $this->sellerConversation(
            $request,
            $conversationId
        );

        abort_unless($conversation->status === 'open', 409, 'Cuộc trò chuyện đã đóng.');

        $message = $conversation->messages()->create([
            'sender_account_id' => $account->account_id,
            'body' => trim($request->validated('body')),
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        return response()->json([
            'message' => [
                'id' => $message->message_id,
                'body' => $message->body,
                'mine' => true,
                'created_at' => $message->created_at?->format('H:i'),
            ],
        ], 201);
    }

    private function sellerConversation(
        Request $request,
        int $conversationId
    ): array {
        $account = $request->user();
        abort_unless($account instanceof Account && $account->seller, 403);

        $conversation = Conversation::query()
            ->whereKey($conversationId)
            ->where('seller_id', $account->seller->seller_id)
            ->firstOrFail();

        return [$account, $conversation];
    }
}
