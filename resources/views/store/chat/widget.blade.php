<div class="store-chat-widget"
     id="storeChatWidget"
     data-list-url="{{ route('store.chat.conversations.index') }}"
     data-create-url="{{ route('store.chat.conversations.store') }}"
     data-login-url="{{ route('login.form') }}">
    <button class="store-chat-launcher" id="storeChatLauncher" type="button"
            aria-expanded="false" aria-controls="storeChatPanel">
        <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
        <span>Chat</span>
        <span class="store-chat-launcher-badge d-none" id="storeChatUnreadTotal">0</span>
    </button>

    <section class="store-chat-panel d-none" id="storeChatPanel"
             aria-label="Trò chuyện với cửa hàng" aria-hidden="true">
        <header class="store-chat-topbar">
            <div class="store-chat-topbar-title">
                <strong>Chat <span class="store-chat-header-unread d-none" id="storeChatHeaderUnread"></span></strong>
                <small id="storeChatPanelSubtitle">Trao đổi với các cửa hàng</small>
            </div>
            <div class="store-chat-topbar-actions">
                <button class="store-chat-icon-button" id="storeChatCollapse" type="button" aria-label="Thu nhỏ cửa sổ chat" title="Thu nhỏ cửa sổ">
                    <i class="bi bi-arrows-angle-contract" aria-hidden="true"></i>
                </button>
                <button class="store-chat-icon-button" id="storeChatMinimize" type="button" aria-label="Ẩn chat" title="Ẩn chat">
                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                </button>
            </div>
        </header>

        <div class="store-chat-layout">
            <aside class="store-chat-inbox" id="storeChatInbox">
                <label class="store-chat-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input id="storeChatSearch" type="search" maxlength="100"
                           placeholder="Tìm cửa hàng hoặc tin nhắn" autocomplete="off">
                </label>
                <div class="store-chat-conversation-list" id="storeChatConversationList" aria-live="polite">
                    <div class="store-chat-state">Đang tải cuộc trò chuyện…</div>
                </div>
            </aside>

            <main class="store-chat-thread d-none" id="storeChatThread">
                <div class="store-chat-thread-heading">
                    <button class="store-chat-back" id="storeChatBack" type="button" aria-label="Quay lại danh sách">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    </button>
                    <span class="store-chat-shop-avatar"><i class="bi bi-shop" aria-hidden="true"></i></span>
                    <span class="store-chat-shop-title">
                        <strong id="storeChatShopName">Cửa hàng</strong>
                        <small id="storeChatProductContext"></small>
                    </span>
                </div>
                <div class="store-chat-error d-none" id="storeChatError" role="alert"></div>
                <div class="store-chat-messages" id="storeChatMessages" aria-live="polite">
                    <div class="store-chat-state">Chọn cuộc trò chuyện để xem tin nhắn.</div>
                </div>
                <form class="store-chat-compose" id="storeChatForm">
                    @csrf
                    <textarea id="storeChatInput" rows="1" maxlength="2000"
                              placeholder="Nhập tin nhắn…" aria-label="Nội dung tin nhắn" disabled></textarea>
                    <button id="storeChatSend" type="submit" aria-label="Gửi tin nhắn" disabled>
                        <i class="bi bi-send-fill" aria-hidden="true"></i>
                    </button>
                </form>
            </main>

            <div class="store-chat-welcome" id="storeChatWelcome">
                <i class="bi bi-chat-heart" aria-hidden="true"></i>
                <strong>Trò chuyện với cửa hàng</strong>
                <span>Các cuộc trò chuyện của bạn sẽ hiển thị tại đây.</span>
            </div>
        </div>
    </section>
</div>

<style>
    .store-chat-widget { --chat-primary: #0878d9; --chat-primary-hover: #0562bf; --chat-primary-light: #eaf4ff; position: fixed; right: 22px; bottom: 20px; z-index: 1080; font-family: inherit; }
    .store-chat-launcher { position: relative; display: flex; align-items: center; gap: 9px; min-height: 52px; padding: 0 22px; border: 0; border-radius: 12px 12px 4px 4px; color: #fff; background: #0878d9; box-shadow: 0 5px 20px #0f172a30; font-size: 16px; font-weight: 700; cursor: pointer; }
    .store-chat-launcher:hover { background: #0562bf; }
    .store-chat-launcher > i { font-size: 21px; }
    .store-chat-launcher-badge { position: absolute; top: -8px; right: -8px; display: grid; min-width: 22px; height: 22px; padding: 0 5px; place-items: center; border: 2px solid #fff; border-radius: 999px; background: #dc3545; color: white; font-size: 11px; }
    .store-chat-panel { position: absolute; right: 0; bottom: 64px; display: flex; width: min(790px, calc(100vw - 32px)); height: min(570px, calc(100dvh - 115px)); flex-direction: column; overflow: hidden; border: 1px solid #e5e7eb; border-radius: 14px; background: #fff; box-shadow: 0 14px 45px #10182835; }
    .store-chat-panel.is-collapsed { width: min(320px, calc(100vw - 24px)); height: min(570px, calc(100dvh - 115px)); }
    .store-chat-panel.is-collapsed .store-chat-layout { display: flex; }
    .store-chat-panel.is-collapsed .store-chat-inbox { width: 100%; flex-basis: 100%; border-right: 0; }
    .store-chat-panel.is-collapsed .store-chat-thread,
    .store-chat-panel.is-collapsed .store-chat-welcome { display: none !important; }
    .store-chat-panel.is-collapsed .store-chat-topbar-title small { display: none; }
    .store-chat-header-unread { color: #dc3545; font-size: 12px; font-weight: 600; }
    .store-chat-panel.d-none, .store-chat-thread.d-none, .store-chat-welcome.d-none { display: none !important; }
    .store-chat-topbar { display: flex; min-height: 64px; align-items: center; justify-content: space-between; padding: 12px 17px; border-bottom: 1px solid #eaecf0; }
    .store-chat-topbar-title { display: flex; flex-direction: column; gap: 2px; color: #101828; }
    .store-chat-topbar-title strong { font-size: 17px; }
    .store-chat-topbar-title small { color: #667085; font-size: 12px; }
    .store-chat-topbar-actions { display: flex; align-items: center; gap: 4px; }
    .store-chat-icon-button, .store-chat-back { display: grid; width: 36px; height: 36px; flex: 0 0 36px; place-items: center; border: 0; border-radius: 50%; color: #475467; background: transparent; }
    .store-chat-icon-button:hover, .store-chat-back:hover { background: #f2f4f7; }
    .store-chat-layout { display: flex; min-height: 0; flex: 1; }
    .store-chat-inbox { display: flex; width: 300px; flex: 0 0 300px; flex-direction: column; border-right: 1px solid #eaecf0; }
    .store-chat-search { display: flex; height: 42px; align-items: center; gap: 8px; margin: 12px; padding: 0 11px; border: 1px solid #d0d5dd; border-radius: 8px; color: #98a2b3; }
    .store-chat-search input { width: 100%; border: 0; outline: 0; color: #344054; font: inherit; font-size: 13px; }
    .store-chat-conversation-list { min-height: 0; flex: 1; overflow-y: auto; }
    .store-chat-conversation { display: flex; width: 100%; align-items: center; gap: 11px; padding: 13px 14px; border: 0; border-bottom: 1px solid #f2f4f7; background: #fff; color: #344054; text-align: left; cursor: pointer; }
    .store-chat-conversation:hover, .store-chat-conversation.active { background: #eaf4ff; }
    .store-chat-conversation-avatar, .store-chat-shop-avatar { display: grid; width: 42px; height: 42px; flex: 0 0 42px; place-items: center; border-radius: 50%; background: #eaf4ff; color: #0878d9; font-size: 18px; }
    .store-chat-conversation-body { min-width: 0; flex: 1; }
    .store-chat-conversation-top, .store-chat-conversation-bottom { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .store-chat-conversation-name { overflow: hidden; color: #101828; font-size: 13px; font-weight: 650; text-overflow: ellipsis; white-space: nowrap; }
    .store-chat-conversation-time, .store-chat-conversation-preview { color: #667085; font-size: 11px; }
    .store-chat-conversation-preview { display: block; overflow: hidden; margin-top: 4px; text-overflow: ellipsis; white-space: nowrap; }
    .store-chat-unread { display: grid; min-width: 19px; height: 19px; padding: 0 5px; place-items: center; border-radius: 999px; background: #0878d9; color: white; font-size: 10px; }
    .store-chat-thread { min-width: 0; flex: 1; flex-direction: column; }
    .store-chat-thread:not(.d-none) { display: flex; }
    .store-chat-thread-heading { display: flex; min-height: 62px; align-items: center; gap: 10px; padding: 9px 14px; border-bottom: 1px solid #eaecf0; }
    .store-chat-thread-heading .store-chat-back { display: none; }
    .store-chat-shop-avatar { width: 38px; height: 38px; flex-basis: 38px; }
    .store-chat-shop-title { display: flex; min-width: 0; flex-direction: column; gap: 3px; }
    .store-chat-shop-title strong { overflow: hidden; color: #101828; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; }
    .store-chat-shop-title small { overflow: hidden; color: #667085; font-size: 11px; text-overflow: ellipsis; white-space: nowrap; }
    .store-chat-messages { display: flex; min-height: 0; flex: 1; flex-direction: column; gap: 9px; overflow-y: auto; padding: 16px; background: #f8fafc; }
    .store-chat-state { padding: 28px 16px; color: #98a2b3; font-size: 13px; text-align: center; }
    .store-chat-bubble { display: flex; max-width: 82%; flex-direction: column; align-self: flex-start; gap: 3px; padding: 9px 12px; border-radius: 13px 13px 13px 4px; background: #fff; color: #344054; box-shadow: 0 1px 2px #10182812; overflow-wrap: anywhere; font-size: 13px; }
    .store-chat-bubble.mine { align-self: flex-end; border-radius: 13px 13px 4px 13px; background: #0878d9; color: #fff; }
    .store-chat-time { align-self: flex-end; font-size: 10px; opacity: .7; }
    .store-chat-compose { display: flex; align-items: flex-end; gap: 8px; padding: 11px; border-top: 1px solid #eaecf0; }
    .store-chat-compose textarea { min-height: 40px; max-height: 100px; flex: 1; resize: none; border: 1px solid #d0d5dd; border-radius: 9px; padding: 9px 11px; outline: 0; font: inherit; font-size: 13px; }
    .store-chat-compose textarea:focus { border-color: #0878d9; box-shadow: 0 0 0 3px #0878d920; }
    .store-chat-compose button { display: grid; width: 40px; height: 40px; flex: 0 0 40px; place-items: center; border: 0; border-radius: 9px; background: #0878d9; color: #fff; }
    .store-chat-compose button:disabled { opacity: .45; }
    .store-chat-error { padding: 8px 12px; color: #b42318; background: #fef3f2; font-size: 12px; }
    .store-chat-error.d-none { display: none; }
    .store-chat-welcome { display: flex; flex: 1; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 24px; color: #667085; text-align: center; }
    .store-chat-welcome i { color: #0878d9; font-size: 38px; }
    .store-chat-welcome strong { color: #344054; font-size: 14px; }
    .store-chat-welcome span { max-width: 230px; font-size: 12px; }
    @media (max-width: 640px) {
        .store-chat-widget { right: 12px; bottom: 12px; }
        .store-chat-panel { position: fixed; inset: 0; width: 100vw; height: 100dvh; border: 0; border-radius: 0; }
        .store-chat-inbox { width: 100%; flex-basis: 100%; border-right: 0; }
        .store-chat-layout:has(.store-chat-thread:not(.d-none)) .store-chat-inbox,
        .store-chat-layout:has(.store-chat-thread:not(.d-none)) .store-chat-welcome { display: none; }
        .store-chat-thread-heading .store-chat-back { display: grid; }
    }
</style>
