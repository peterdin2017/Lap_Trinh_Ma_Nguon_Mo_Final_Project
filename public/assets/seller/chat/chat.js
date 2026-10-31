(() => {
    const page = document.querySelector('.seller-chat-page');
    if (!page) return;

    const list = document.getElementById('sellerConversationItems');
    const box = document.getElementById('sellerChatMessages');
    const form = document.getElementById('sellerChatForm');
    const input = document.getElementById('sellerChatInput');
    const sendButton = document.getElementById('sellerChatSend');
    const errorBox = document.getElementById('sellerChatError');
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const unreadBadges = document.querySelectorAll('[data-seller-chat-unread]');

    let lastId = box
        ? Math.max(
            0,
            ...Array.from(box.querySelectorAll('[data-message-id]'))
                .map((element) => Number(element.dataset.messageId))
        )
        : 0;

    let messagesLoading = false;
    let listLoading = false;

    function appendMessage(message) {
        if (!box || Number(message.id) <= lastId) return;

        const element = document.createElement('div');
        element.className = 'seller-chat-message' + (message.mine ? ' mine' : '');
        element.dataset.messageId = message.id;

        const body = document.createElement('span');
        body.textContent = message.body;

        const time = document.createElement('small');
        time.textContent = message.created_at || '';

        element.append(body, time);
        box.append(element);

        lastId = Number(message.id);
        box.scrollTop = box.scrollHeight;
    }

    async function request(url, method = 'GET', body = null) {
        const headers = {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        if (body) {
            headers['Content-Type'] = 'application/json';
        }

        if (token) {
            headers['X-CSRF-TOKEN'] = token;
        }

        const response = await fetch(url, {
            method,
            headers,
            body: body ? JSON.stringify(body) : undefined,
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const validationMessage = Object.values(data.errors || {}).flat()[0];

            throw new Error(
                validationMessage || data.message || 'Không thể cập nhật hộp thư.'
            );
        }

        return data;
    }

    function renderConversationList(conversations) {
        if (!list) return;

        const activeId = page.dataset.conversationId || '';
        const fragment = document.createDocumentFragment();

        if (conversations.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'p-4 text-center text-secondary small';
            empty.textContent = 'Chưa có cuộc trò chuyện nào.';
            fragment.append(empty);
            list.replaceChildren(fragment);
            return;
        }

        conversations.forEach((conversation) => {
            const link = document.createElement('a');
            link.className = 'seller-chat-list-item' +
                (String(conversation.id) === activeId ? ' active' : '');
            link.href = conversation.url;

            const avatar = document.createElement('span');
            avatar.className = 'seller-chat-list-avatar';

            const icon = document.createElement('i');
            icon.className = 'bi bi-person';
            icon.setAttribute('aria-hidden', 'true');
            avatar.append(icon);

            const details = document.createElement('span');
            details.className = 'min-w-0 flex-grow-1';

            const heading = document.createElement('span');
            heading.className = 'd-flex justify-content-between gap-2';

            const customer = document.createElement('strong');
            customer.className = 'text-truncate';
            customer.textContent = conversation.customer;

            const time = document.createElement('small');
            time.className = 'text-secondary';
            time.textContent = conversation.last_message_at || '';

            heading.append(customer, time);

            const preview = document.createElement('span');
            preview.className = 'd-flex justify-content-between gap-2 mt-1';

            const lastMessage = document.createElement('small');
            lastMessage.className = 'text-secondary text-truncate';
            lastMessage.textContent = conversation.last_message;
            preview.append(lastMessage);

            if (conversation.unread_count > 0) {
                const unread = document.createElement('span');
                unread.className = 'badge rounded-pill text-bg-primary';
                unread.textContent = String(conversation.unread_count);
                preview.append(unread);
            }

            details.append(heading, preview);
            link.append(avatar, details);
            fragment.append(link);
        });

        list.replaceChildren(fragment);
    }

    function renderUnreadTotal(total) {
        const count = Math.max(0, Number(total) || 0);

        unreadBadges.forEach((badge) => {
            badge.textContent = count > 99 ? '99+' : String(count);
            badge.classList.toggle('d-none', count === 0);
            badge.setAttribute('aria-label', `${count} tin nhắn chưa xem`);
        });
    }

    async function pollConversations() {
        if (!list || listLoading || !page.dataset.conversationsUrl) return;

        listLoading = true;

        try {
            const data = await request(page.dataset.conversationsUrl);
            renderUnreadTotal(data.unread_total);
            renderConversationList(data.conversations || []);
        } catch (error) {
            // Giữ danh sách hiện tại nếu request tạm thời bị lỗi.
            console.error(error);
        } finally {
            listLoading = false;
        }
    }

    async function pollMessages() {
        if (!box || messagesLoading || !page.dataset.messagesUrl) return;

        messagesLoading = true;

        try {
            const url = page.dataset.messagesUrl + '?after_id=' + lastId;
            const data = await request(url);

            (data.messages || []).forEach(appendMessage);
            errorBox?.classList.add('d-none');
        } catch (error) {
            if (errorBox) {
                errorBox.textContent = error.message;
                errorBox.classList.remove('d-none');
            }
        } finally {
            messagesLoading = false;
        }
    }

    if (form && input && sendButton) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const body = input.value.trim();
            if (!body || !page.dataset.sendUrl) return;

            sendButton.disabled = true;

            try {
                const data = await request(page.dataset.sendUrl, 'POST', { body });

                appendMessage(data.message);
                input.value = '';
                errorBox?.classList.add('d-none');
                pollConversations();
            } catch (error) {
                if (errorBox) {
                    errorBox.textContent = error.message;
                    errorBox.classList.remove('d-none');
                }
            } finally {
                sendButton.disabled = false;
                input.focus();
            }
        });
    }

    if (box) {
        box.scrollTop = box.scrollHeight;
    }

    pollConversations();
    pollMessages();

    window.setInterval(pollConversations, 3000);
    window.setInterval(pollMessages, 3000);
})();
