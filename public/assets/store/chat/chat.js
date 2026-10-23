(() => {
    const panel = document.getElementById('storeChatPanel');
    if (!panel) return;

    const title = document.getElementById('storeChatTitle');
    const messagesBox = document.getElementById('storeChatMessages');
    const errorBox = document.getElementById('storeChatError');
    const contextBox = document.getElementById('storeChatContext');
    const form = document.getElementById('storeChatForm');
    const input = document.getElementById('storeChatInput');
    const sendButton = document.getElementById('storeChatSend');
    const token = form.querySelector('[name="_token"]').value;

    let conversation = null;
    let lastMessageId = 0;
    let pollTimer = null;
    let loading = false;

    function showError(message) {
        errorBox.textContent = message;
        errorBox.classList.toggle('d-none', !message);
    }

    function appendMessage(message) {
        if (Number(message.id) <= lastMessageId) return;

        messagesBox.querySelector('.store-chat-empty')?.remove();

        const bubble = document.createElement('div');
        bubble.className = 'store-chat-bubble' + (message.mine ? ' mine' : '');

        const body = document.createElement('span');
        body.textContent = message.body;

        const time = document.createElement('small');
        time.className = 'store-chat-time';
        time.textContent = message.created_at || '';

        bubble.append(body, time);
        messagesBox.append(bubble);

        lastMessageId = Number(message.id);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    async function request(url, method = 'GET', body = null) {
        const response = await fetch(url, {
            method,
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: body ? JSON.stringify(body) : undefined,
        });

        const data = await response.json().catch(() => ({}));

        if (response.status === 401) {
            window.location.href = panel.dataset.loginUrl;
            throw new Error('Vui lòng đăng nhập để chat với Shop.');
        }

        if (!response.ok) {
            const validationMessage = Object.values(data.errors || {}).flat()[0];
            throw new Error(
                validationMessage || data.message || 'Không thể kết nối chat.'
            );
        }

        return data;
    }

    async function loadMessages() {
        if (!conversation || loading) return;

        loading = true;

        try {
            const url = conversation.messages_url + '?after_id=' + lastMessageId;
            const data = await request(url);

            data.messages.forEach(appendMessage);
            showError('');
        } catch (error) {
            showError(error.message);
        } finally {
            loading = false;
        }
    }

    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest('[data-chat-start]');
        if (!trigger) return;

        event.preventDefault();
        showError('');

        title.textContent = trigger.dataset.shopName || 'Chat với Shop';
        contextBox.textContent = trigger.dataset.productId
            ? 'Đang trao đổi về sản phẩm này'
            : '';
        contextBox.classList.toggle('d-none', !trigger.dataset.productId);

        bootstrap.Offcanvas.getOrCreateInstance(panel).show();

        try {
            const data = await request(
                panel.dataset.conversationUrl,
                'POST',
                {
                    seller_id: Number(trigger.dataset.sellerId),
                    product_id: trigger.dataset.productId
                        ? Number(trigger.dataset.productId)
                        : null,
                }
            );

            conversation = {
                messages_url: data.messages_url,
                send_url: data.send_url,
            };

            title.textContent =
                data.shop_name || trigger.dataset.shopName || 'Chat với Shop';

            lastMessageId = 0;
            messagesBox.replaceChildren();
            input.disabled = false;
            sendButton.disabled = false;

            await loadMessages();

            window.clearInterval(pollTimer);
            pollTimer = window.setInterval(loadMessages, 3000);
            input.focus();
        } catch (error) {
            showError(error.message);
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const body = input.value.trim();
        if (!conversation || !body) return;

        sendButton.disabled = true;

        try {
            const data = await request(conversation.send_url, 'POST', { body });
            appendMessage(data.message);
            input.value = '';
            showError('');
        } catch (error) {
            showError(error.message);
        } finally {
            sendButton.disabled = false;
            input.focus();
        }
    });

    panel.addEventListener('hidden.bs.offcanvas', () => {
        window.clearInterval(pollTimer);
    });
})();
