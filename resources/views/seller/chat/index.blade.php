@extends('seller.layouts.app')

@section('title', 'Tin nhắn - Seller Center')

@section('content')
    <div class="seller-chat-page"
         data-conversations-url="{{ route('seller.chat.conversations.updates') }}"
         @if ($activeConversation)
             data-conversation-id="{{ $activeConversation->conversation_id }}"
             data-messages-url="{{ route('seller.chat.messages.index', $activeConversation) }}"
             data-send-url="{{ route('seller.chat.messages.store', $activeConversation) }}"
         @endif>
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Tin nhắn</h1>
            <p class="text-secondary mb-0">Trao đổi với khách hàng của cửa hàng.</p>
        </div>

        <section class="card seller-chat-card">
            <div class="row g-0">
                <aside class="col-12 col-md-4 seller-chat-list">
                    <div class="p-3 border-bottom fw-bold">Hộp thư</div>

                    <div id="sellerConversationItems">
                        @forelse ($conversations as $conversation)
                            <a class="seller-chat-list-item {{ $activeConversation?->conversation_id === $conversation->conversation_id ? 'active' : '' }}"
                               href="{{ route('seller.chat.show', $conversation) }}"
                               data-conversation-list-id="{{ $conversation->conversation_id }}">
                                <span class="seller-chat-list-avatar">
                                    <i class="bi bi-person" aria-hidden="true"></i>
                                </span>

                                <span class="min-w-0 flex-grow-1">
                                    <span class="d-flex justify-content-between gap-2">
                                        <strong class="text-truncate">
                                            {{ $conversation->customer?->email ?? 'Khách hàng' }}
                                        </strong>
                                        <small class="text-secondary">
                                            {{ $conversation->last_message_at?->format('H:i') }}
                                        </small>
                                    </span>

                                    <span class="d-flex justify-content-between gap-2 mt-1">
                                        <small class="text-secondary text-truncate">
                                            {{ $conversation->latestMessage?->body ?? 'Chưa có tin nhắn' }}
                                        </small>

                                        @if ($conversation->unread_count > 0)
                                            <span class="badge rounded-pill text-bg-primary">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif
                                    </span>
                                </span>
                            </a>
                        @empty
                            <div class="p-4 text-center text-secondary small">
                                Chưa có cuộc trò chuyện nào.
                            </div>
                        @endforelse
                    </div>
                </aside>

                <div class="col-12 col-md-8 d-flex flex-column seller-chat-thread">
                    @if ($activeConversation)
                        <header class="p-3 border-bottom">
                            <strong>
                                {{ $activeConversation->customer?->email ?? 'Khách hàng' }}
                            </strong>

                            @if ($activeConversation->product)
                                <div class="small text-secondary">
                                    Hỏi về: {{ $activeConversation->product->product_name }}
                                </div>
                            @endif
                        </header>

                        <div class="seller-chat-messages flex-grow-1"
                             id="sellerChatMessages"
                             aria-live="polite">
                            @foreach ($initialMessages as $message)
                                <div class="seller-chat-message {{ $message->sender_account_id === auth()->id() ? 'mine' : '' }}"
                                     data-message-id="{{ $message->message_id }}">
                                    <span>{{ $message->body }}</span>
                                    <small>{{ $message->created_at?->format('H:i') }}</small>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-danger d-none m-2"
                             id="sellerChatError"
                             role="alert"></div>

                        <form class="seller-chat-compose" id="sellerChatForm">
                            <label class="visually-hidden" for="sellerChatInput">Tin nhắn</label>

                            <textarea class="form-control"
                                      id="sellerChatInput"
                                      rows="2"
                                      maxlength="2000"
                                      placeholder="Nhập tin nhắn..."
                                      required></textarea>

                            <button class="btn btn-primary"
                                    type="submit"
                                    id="sellerChatSend">
                                <i class="bi bi-send-fill" aria-hidden="true"></i>
                                <span class="visually-hidden">Gửi</span>
                            </button>
                        </form>
                    @else
                        <div class="seller-chat-placeholder m-auto text-center text-secondary p-4">
                            <i class="bi bi-chat-dots display-5 d-block mb-2"></i>
                            Chọn một cuộc trò chuyện để xem và trả lời tin nhắn.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <style>
        .seller-chat-card {
            min-height: 560px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .seller-chat-list {
            border-right: 1px solid #e2e8f0;
        }

        .seller-chat-list-item {
            display: flex;
            gap: 10px;
            padding: 14px;
            color: #0f172a;
            text-decoration: none;
            border-bottom: 1px solid #f1f5f9;
        }

        .seller-chat-list-item:hover,
        .seller-chat-list-item.active {
            background: #ebf5ff;
        }

        .seller-chat-list-avatar {
            display: grid;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            place-items: center;
            border-radius: 50%;
            background: #ebf5ff;
            color: #1a94ff;
        }

        .seller-chat-thread {
            min-height: 560px;
        }

        .seller-chat-messages {
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow-y: auto;
            padding: 18px;
            background: #f4f7fa;
        }

        .seller-chat-message {
            display: flex;
            flex-direction: column;
            align-self: flex-start;
            max-width: 78%;
            padding: 9px 12px;
            border-radius: 12px;
            background: #fff;
            overflow-wrap: anywhere;
        }

        .seller-chat-message.mine {
            align-self: flex-end;
            background: #1a94ff;
            color: #fff;
        }

        .seller-chat-message small {
            margin-top: 4px;
            font-size: .68rem;
            opacity: .72;
            text-align: right;
        }

        .seller-chat-compose {
            display: flex;
            align-items: end;
            gap: 8px;
            padding: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .seller-chat-compose textarea {
            resize: none;
        }

        .seller-chat-compose .btn {
            min-width: 46px;
            height: 42px;
        }

        @media (max-width: 767.98px) {
            .seller-chat-card,
            .seller-chat-thread {
                min-height: 70vh;
            }

            .seller-chat-list {
                max-height: 30vh;
                overflow-y: auto;
                border-right: 0;
                border-bottom: 1px solid #e2e8f0;
            }
        }
    </style>

    <script src="{{ asset('assets/seller/chat/chat.js') }}" defer></script>
@endsection
