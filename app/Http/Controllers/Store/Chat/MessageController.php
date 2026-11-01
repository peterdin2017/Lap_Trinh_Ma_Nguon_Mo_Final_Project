<?php

namespace App\Http\Controllers\Store\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ConversationMessagesRequest;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Models\Account;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageController extends Controller
{
    public function index(ConversationMessagesRequest $request, int $conversationId): JsonResponse
    {
        $account = $this->customer($request);

        $conversation = Conversation::query()
            ->whereKey($conversationId)
            ->where('customer_account_id', $account->account_id)
            ->firstOrFail();

        $afterId = (int) ($request->validated('after_id') ?? 0);

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
        $account = $this->customer($request);

        $conversation = Conversation::query()
            ->whereKey($conversationId)
            ->where('customer_account_id', $account->account_id)
            ->where('status', 'open')
            ->firstOrFail();

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
