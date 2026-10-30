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
        <section class="seller-chat-card">
            <aside class="seller-chat-list">
                <div class="seller-chat-list-heading">
                    <div>
                        <h2>Hộp thư</h2>
                        <span>{{ $conversations->count() }} cuộc trò chuyện</span>
                    </div>
                    <i class="bi bi-chat-square-text" aria-hidden="true"></i>
                </div>

                <div class="seller-chat-conversations" id="sellerConversationItems">
                    @forelse ($conversations as $conversation)
                        <a class="seller-chat-list-item {{ $activeConversation?->conversation_id === $conversation->conversation_id ? 'active' : '' }}"
                           href="{{ route('seller.chat.show', $conversation) }}"
                           data-conversation-list-id="{{ $conversation->conversation_id }}">
                            <span class="seller-chat-list-avatar" aria-hidden="true">
                                <i class="bi bi-person"></i>
                            </span>

                            {{-- Giữ các class tương thích với HTML do chat.js tạo --}}
                            <span class="min-w-0 flex-grow-1">
                                <span class="d-flex justify-content-between gap-2">
                                    <strong class="text-truncate"
                                            title="{{ $conversation->customer?->email ?? 'Khách hàng' }}">
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
                        <div class="seller-chat-empty-list">
                            <i class="bi bi-chat-dots" aria-hidden="true"></i>
                            <strong>Chưa có cuộc trò chuyện</strong>
                            <span>Tin nhắn từ khách hàng sẽ xuất hiện ở đây.</span>
                        </div>
                    @endforelse
                </div>
            </aside>

            <section class="seller-chat-thread" aria-label="Nội dung cuộc trò chuyện">
                @if ($activeConversation)
                    <header class="seller-chat-thread-header">
                        <span class="seller-chat-thread-avatar" aria-hidden="true">
                            <i class="bi bi-person"></i>
                        </span>

                        <div class="seller-chat-thread-info">
                            <strong title="{{ $activeConversation->customer?->email ?? 'Khách hàng' }}">
                                {{ $activeConversation->customer?->email ?? 'Khách hàng' }}
                            </strong>

                            @if ($activeConversation->product)
                                <span class="seller-chat-product">
                                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                                    <span>Hỏi về: {{ $activeConversation->product->product_name }}</span>
                                </span>
                            @else
                                <span class="seller-chat-product">
                                    Cuộc trò chuyện với khách hàng
                                </span>
                            @endif
                        </div>
                    </header>

                    <div class="seller-chat-messages"
                         id="sellerChatMessages"
                         aria-live="polite"
                         aria-label="Tin nhắn">
                        @foreach ($initialMessages as $message)
                            <div class="seller-chat-message {{ $message->sender_account_id === auth()->id() ? 'mine' : '' }}"
                                 data-message-id="{{ $message->message_id }}">
                                <span>{{ $message->body }}</span>
                                <small>{{ $message->created_at?->format('H:i') }}</small>
                            </div>
                        @endforeach
                    </div>

                    <div class="alert alert-danger d-none seller-chat-error"
                         id="sellerChatError"
                         role="alert"></div>

                    <form class="seller-chat-compose" id="sellerChatForm">
                        <label class="visually-hidden" for="sellerChatInput">Tin nhắn</label>

                        <textarea class="form-control"
                                  id="sellerChatInput"
                                  rows="1"
                                  maxlength="2000"
                                  placeholder="Nhập tin nhắn..."
                                  required></textarea>

                        <button class="btn btn-primary"
                                type="submit"
                                id="sellerChatSend"
                                aria-label="Gửi tin nhắn">
                            <i class="bi bi-send-fill" aria-hidden="true"></i>
                            <span class="visually-hidden">Gửi</span>
                        </button>
                    </form>
                @else
                    <div class="seller-chat-placeholder">
                        <span class="seller-chat-placeholder-icon">
                            <i class="bi bi-chat-dots" aria-hidden="true"></i>
                        </span>
                        <strong>Chọn một cuộc trò chuyện</strong>
                        <span>Chọn khách hàng trong hộp thư để xem và trả lời tin nhắn.</span>
                    </div>
                @endif
            </section>
        </section>
    </div>

    <style>
        .seller-chat-page {
            --chat-panel: var(--seller-card-bg, #fff);
            --chat-page: var(--seller-page-bg, #f4f7fa);
            --chat-text: var(--seller-text, #0f172a);
            --chat-muted: var(--seller-text-muted, #64748b);
            --chat-border: var(--seller-border, #e2e8f0);
            --chat-primary: var(--seller-primary, #1a94ff);
            --chat-primary-soft: var(--seller-primary-light, #ebf5ff);

            padding-top: 8px;
            color: var(--chat-text);
        }

        .seller-chat-card {
            display: grid;
            grid-template-columns: minmax(270px, 320px) minmax(0, 1fr);
            height: clamp(540px, calc(100vh - 180px), 760px);
            min-height: 540px;
            overflow: hidden;
            background: var(--chat-panel);
            border: 1px solid var(--chat-border);
            border-radius: 14px;
            box-shadow: var(--seller-shadow, 0 4px 18px rgb(15 23 42 / 5%));
        }

        .seller-chat-list {
            display: flex;
            min-width: 0;
            flex-direction: column;
            overflow: hidden;
            background: var(--chat-panel);
            border-right: 1px solid var(--chat-border);
        }

        .seller-chat-list-heading {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: space-between;
            min-height: 72px;
            padding: 14px 17px;
            background: var(--chat-panel);
            border-bottom: 1px solid var(--chat-border);
        }

        .seller-chat-list-heading h2 {
            margin: 0;
            color: var(--chat-text);
            font-size: .98rem;
            font-weight: 750;
        }

        .seller-chat-list-heading span {
            display: block;
            margin-top: 4px;
            color: var(--chat-muted);
            font-size: .75rem;
        }

        .seller-chat-list-heading > i {
            color: var(--chat-primary);
            font-size: 1.15rem;
        }

        .seller-chat-conversations {
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .seller-chat-list-item {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 76px;
            padding: 12px 14px;
            color: var(--chat-text);
            text-decoration: none;
            border-bottom: 1px solid var(--chat-border);
            border-left: 3px solid transparent;
            transition: background-color .16s ease, border-color .16s ease;
        }

        .seller-chat-list-item:hover {
            color: var(--chat-text);
            background: var(--chat-page);
        }

        .seller-chat-list-item.active {
            color: var(--chat-text);
            background: var(--chat-primary-soft);
            border-left-color: var(--chat-primary);
        }

        .seller-chat-list-avatar,
        .seller-chat-thread-avatar {
            display: grid;
            flex: 0 0 auto;
            place-items: center;
            color: var(--chat-primary);
            background: var(--chat-primary-soft);
            border: 1px solid var(--chat-border);
            border-radius: 50%;
        }

        .seller-chat-list-avatar {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        /* Áp dụng cho cả danh sách ban đầu và danh sách được chat.js render lại. */
        .seller-chat-list-item > .min-w-0.flex-grow-1 {
            display: block;
            min-width: 0;
            flex: 1 1 auto;
        }

        .seller-chat-list-item .d-flex.justify-content-between.gap-2 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            min-width: 0;
        }

        .seller-chat-list-item strong {
            min-width: 0;
            color: inherit;
            font-size: .82rem;
            font-weight: 700;
        }

        .seller-chat-list-item small {
            color: var(--chat-muted) !important;
            font-size: .72rem;
        }

        .seller-chat-list-item .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .seller-chat-list-item .badge {
            flex: 0 0 auto;
        }

        .seller-chat-empty-list {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            padding: 36px 18px;
            color: var(--chat-muted);
            text-align: center;
        }

        .seller-chat-empty-list i {
            color: var(--chat-primary);
            font-size: 1.8rem;
        }

        .seller-chat-empty-list strong {
            color: var(--chat-text);
            font-size: .86rem;
        }

        .seller-chat-empty-list span {
            font-size: .75rem;
            line-height: 1.5;
        }

        .seller-chat-thread {
            display: flex;
            min-width: 0;
            min-height: 0;
            flex-direction: column;
            background: var(--chat-panel);
        }

        .seller-chat-thread-header {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            gap: 11px;
            min-height: 72px;
            padding: 12px 18px;
            background: var(--chat-panel);
            border-bottom: 1px solid var(--chat-border);
        }

        .seller-chat-thread-avatar {
            width: 42px;
            height: 42px;
            font-size: 1.05rem;
        }

        .seller-chat-thread-info {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 4px;
        }

        .seller-chat-thread-info > strong {
            overflow: hidden;
            color: var(--chat-text);
            font-size: .9rem;
            font-weight: 750;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .seller-chat-product {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 6px;
            color: var(--chat-muted);
            font-size: .74rem;
        }

        .seller-chat-product i {
            flex: 0 0 auto;
            color: var(--chat-primary);
        }

        .seller-chat-product span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .seller-chat-messages {
            display: flex;
            flex: 1 1 auto;
            min-height: 0;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
            padding: 20px;
            background: var(--chat-page);
            overscroll-behavior: contain;
            scroll-behavior: smooth;
        }

        .seller-chat-message {
            display: flex;
            max-width: min(76%, 540px);
            flex-direction: column;
            align-self: flex-start;
            padding: 10px 13px 7px;
            color: var(--chat-text) !important;
            background: var(--chat-panel);
            border: 1px solid var(--chat-border);
            border-radius: 4px 14px 14px;
            box-shadow: var(--seller-shadow, 0 2px 8px rgb(15 23 42 / 5%));
            overflow-wrap: anywhere;
            line-height: 1.5;
        }

        .seller-chat-message > span {
            color: var(--chat-text) !important;
            white-space: pre-wrap;
        }

        .seller-chat-message.mine {
            align-self: flex-end;
            color: #fff !important;
            background: var(--chat-primary);
            border-color: var(--chat-primary);
            border-radius: 14px 4px 14px 14px;
        }

        .seller-chat-message.mine > span {
            color: #fff !important;
        }

        .seller-chat-message > small,
        .seller-chat-message > time {
            margin-top: 4px;
            color: var(--chat-muted) !important;
            font-size: .68rem;
            text-align: right;
        }

        .seller-chat-message.mine > small,
        .seller-chat-message.mine > time {
            color: rgb(255 255 255 / 82%) !important;
        }

        .seller-chat-error {
            flex: 0 0 auto;
            margin: 8px 12px 0;
            border-radius: 9px;
        }

        .seller-chat-compose {
            display: flex;
            flex: 0 0 auto;
            align-items: flex-end;
            gap: 9px;
            padding: 12px 14px;
            background: var(--chat-panel);
            border-top: 1px solid var(--chat-border);
        }

        .seller-chat-compose textarea {
            min-height: 44px;
            max-height: 120px;
            resize: vertical;
            color: var(--chat-text);
            background: var(--chat-panel);
            border: 1px solid var(--chat-border);
            border-radius: 10px;
            box-shadow: none;
        }

        .seller-chat-compose textarea::placeholder {
            color: var(--chat-muted);
            opacity: .85;
        }

        .seller-chat-compose textarea:focus {
            color: var(--chat-text);
            background: var(--chat-panel);
            border-color: var(--chat-primary);
            box-shadow: 0 0 0 3px rgb(26 148 255 / 14%);
        }

        .seller-chat-compose .btn {
            display: grid;
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            place-items: center;
            padding: 0;
            border-radius: 10px;
        }

        .seller-chat-placeholder {
            display: flex;
            max-width: 380px;
            flex: 1 1 auto;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: auto;
            padding: 28px;
            color: var(--chat-muted);
            text-align: center;
            line-height: 1.55;
        }

        .seller-chat-placeholder-icon {
            display: grid;
            width: 64px;
            height: 64px;
            margin-bottom: 6px;
            place-items: center;
            color: var(--chat-primary);
            background: var(--chat-primary-soft);
            border: 1px solid var(--chat-border);
            border-radius: 18px;
            font-size: 1.7rem;
        }

        .seller-chat-placeholder strong {
            color: var(--chat-text);
            font-size: .95rem;
        }

        .seller-chat-placeholder > span:last-child {
            font-size: .8rem;
        }

        @media (max-width: 767.98px) {
            .seller-chat-page {
                padding-top: 2px;
            }

            .seller-chat-card {
                display: flex;
                height: auto;
                min-height: 0;
                flex-direction: column;
            }

            .seller-chat-list {
                max-height: 32vh;
                border-right: 0;
                border-bottom: 1px solid var(--chat-border);
            }

            .seller-chat-thread {
                height: 58vh;
                min-height: 380px;
            }

            .seller-chat-messages {
                padding: 14px;
            }

            .seller-chat-message {
                max-width: 88%;
            }

            .seller-chat-compose {
                padding: 9px;
            }
        }
    </style>

    <script src="{{ asset('assets/seller/chat/chat.js') }}" defer></script>
@endsection
