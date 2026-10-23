<div class="offcanvas offcanvas-end store-chat-panel"
     tabindex="-1"
     id="storeChatPanel"
     aria-labelledby="storeChatTitle"
     data-conversation-url="{{ route('store.chat.conversations.store') }}"
     data-login-url="{{ route('login.form') }}">

    <div class="offcanvas-header store-chat-header">
        <div class="d-flex align-items-center gap-2 min-w-0">
            <span class="store-chat-avatar">
                <i class="bi bi-shop" aria-hidden="true"></i>
            </span>

            <div class="min-w-0">
                <h2 class="h6 fw-bold mb-0 text-truncate" id="storeChatTitle">
                    Chat với Shop
                </h2>
                <span class="small text-secondary">Thường phản hồi trong ngày</span>
            </div>
        </div>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Đóng chat"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-0">
        <div class="store-chat-context d-none" id="storeChatContext"></div>
        <div class="store-chat-error d-none" id="storeChatError" role="alert"></div>

        <div class="store-chat-messages flex-grow-1"
             id="storeChatMessages"
             aria-live="polite">
            <div class="store-chat-empty">Chọn Chat với Shop để bắt đầu trò chuyện.</div>
        </div>

        <form class="store-chat-compose" id="storeChatForm">
            @csrf

            <label class="visually-hidden" for="storeChatInput">
                Nội dung tin nhắn
            </label>

            <textarea class="form-control"
                      id="storeChatInput"
                      rows="2"
                      maxlength="2000"
                      placeholder="Nhập tin nhắn..."
                      disabled></textarea>

            <button class="btn btn-primary"
                    id="storeChatSend"
                    type="submit"
                    disabled>
                <i class="bi bi-send-fill" aria-hidden="true"></i>
                <span class="visually-hidden">Gửi</span>
            </button>
        </form>
    </div>
</div>

<style>
    .store-chat-panel {
        --bs-offcanvas-width: min(420px, 100vw);
    }

    .store-chat-header {
        border-bottom: 1px solid #e2e8f0;
    }

    .store-chat-avatar {
        display: grid;
        width: 40px;
        height: 40px;
        place-items: center;
        border-radius: 50%;
        background: #ebf5ff;
        color: #1a94ff;
        font-size: 1.2rem;
    }

    .store-chat-messages {
        display: flex;
        flex-direction: column;
        gap: 10px;
        overflow-y: auto;
        padding: 16px;
        background: #f4f7fa;
    }

    .store-chat-empty {
        margin: auto;
        color: #64748b;
        font-size: .9rem;
        text-align: center;
    }

    .store-chat-bubble {
        max-width: 82%;
        padding: 9px 12px;
        border-radius: 14px;
        background: #fff;
        color: #0f172a;
        box-shadow: 0 1px 2px #0f172a12;
        overflow-wrap: anywhere;
    }

    .store-chat-bubble.mine {
        align-self: flex-end;
        background: #1a94ff;
        color: #fff;
    }

    .store-chat-time {
        display: block;
        margin-top: 3px;
        font-size: .68rem;
        opacity: .72;
        text-align: right;
    }

    .store-chat-compose {
        display: flex;
        align-items: end;
        gap: 8px;
        padding: 12px;
        border-top: 1px solid #e2e8f0;
        background: #fff;
    }

    .store-chat-compose textarea {
        resize: none;
    }

    .store-chat-compose .btn {
        min-width: 44px;
        height: 42px;
    }

    .store-chat-error {
        padding: 8px 12px;
        color: #b42318;
        background: #fef3f2;
        font-size: .85rem;
    }

    .store-chat-context {
        padding: 8px 16px;
        border-bottom: 1px solid #e2e8f0;
        color: #64748b;
        font-size: .8rem;
    }
</style>
