(() => {
    const widget = document.getElementById("storeChatWidget");
    if (!widget) return;

    const panel = document.getElementById("storeChatPanel");
    const launcher = document.getElementById("storeChatLauncher");
    const minimizeButton = document.getElementById("storeChatMinimize");
    const collapseButton = document.getElementById("storeChatCollapse");
    const backButton = document.getElementById("storeChatBack");
    const list = document.getElementById("storeChatConversationList");
    const search = document.getElementById("storeChatSearch");
    const thread = document.getElementById("storeChatThread");
    const welcome = document.getElementById("storeChatWelcome");
    const title = document.getElementById("storeChatShopName");
    const shopAvatar = document.getElementById("storeChatShopAvatar");
    const productContext = document.getElementById("storeChatProductContext");
    const messagesBox = document.getElementById("storeChatMessages");
    const errorBox = document.getElementById("storeChatError");
    const form = document.getElementById("storeChatForm");
    const input = document.getElementById("storeChatInput");
    const sendButton = document.getElementById("storeChatSend");
    const unreadTotal = document.getElementById("storeChatUnreadTotal");
    const headerUnread = document.getElementById("storeChatHeaderUnread");

    const token =
        form?.querySelector('[name="_token"]')?.value ||
        document.querySelector('meta[name="csrf-token"]')?.content;

    let conversations = [];
    let activeConversation = null;
    let lastMessageId = 0;
    let listLoadingPromise = null;
    let messagesLoading = false;

    function setError(message = "") {
        if (!errorBox) return;

        errorBox.textContent = message;
        errorBox.classList.toggle("d-none", !message);
    }

    function setPanelOpen(open) {
        panel.classList.toggle("d-none", !open);

        if (open) {
            panel.classList.remove("is-collapsed");
        }

        panel.setAttribute("aria-hidden", String(!open));
        launcher.setAttribute("aria-expanded", String(open));

        if (open) {
            loadConversations();

            if (activeConversation) {
                input.focus();
            }
        }
    }

    function updateUnreadBadge(total) {
        const count = Number(total) || 0;

        unreadTotal.textContent = count > 99 ? "99+" : String(count);
        unreadTotal.classList.toggle("d-none", count < 1);

        headerUnread.textContent =
            count > 99 ? "(99+)" : count ? `(${count})` : "";
        headerUnread.classList.toggle("d-none", count < 1);
    }

    async function request(url, method = "GET", body = null) {
        const headers = {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        };

        if (body !== null) {
            headers["Content-Type"] = "application/json";
        }

        if (token) {
            headers["X-CSRF-TOKEN"] = token;
        }

        const response = await fetch(url, {
            method,
            headers,
            credentials: "same-origin",
            body: body === null ? undefined : JSON.stringify(body),
        });

        const data = await response.json().catch(() => ({}));

        if (response.status === 401) {
            window.location.assign(widget.dataset.loginUrl);
            throw new Error("Vui lòng đăng nhập để sử dụng chat.");
        }

        if (!response.ok) {
            const validationMessage = Object.values(
                data.errors || {},
            ).flat()[0];
            throw new Error(
                validationMessage || data.message || "Không thể kết nối chat.",
            );
        }

        return data;
    }

    function addShopFallback(container) {
        const icon = document.createElement("i");
        icon.className = "bi bi-shop";
        icon.setAttribute("aria-hidden", "true");
        container.replaceChildren(icon);
    }

    function renderShopAvatar(container, avatarUrl, className) {
        if (!container) return;

        container.classList.add(className);

        if (!avatarUrl) {
            addShopFallback(container);
            return;
        }

        const image = document.createElement("img");
        image.src = avatarUrl;
        image.alt = "";
        image.loading = "lazy";

        image.addEventListener(
            "error",
            () => {
                addShopFallback(container);
            },
            { once: true },
        );

        container.replaceChildren(image);
    }

    function renderMessages(messages, reset = false) {
        if (reset) {
            messagesBox.replaceChildren();
            lastMessageId = 0;
        }

        if (!messages.length && lastMessageId === 0) {
            const empty = document.createElement("div");
            empty.className = "store-chat-state";
            empty.textContent = "Hãy gửi lời chào để bắt đầu trò chuyện.";
            messagesBox.replaceChildren(empty);
            return;
        }

        messages.forEach((message) => {
            const id = Number(message.id);

            if (!id || id <= lastMessageId) return;

            messagesBox.querySelector(".store-chat-state")?.remove();

            const bubble = document.createElement("div");
            bubble.className =
                "store-chat-bubble" + (message.mine ? " mine" : "");

            const body = document.createElement("span");
            body.textContent = message.body || "";

            const time = document.createElement("small");
            time.className = "store-chat-time";
            time.textContent = message.created_at || "";

            bubble.append(body, time);
            messagesBox.append(bubble);
            lastMessageId = id;
        });

        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    function filteredConversations() {
        const query = search.value.trim().toLocaleLowerCase("vi");

        if (!query) {
            return conversations;
        }

        return conversations.filter((conversation) =>
            `${conversation.shop_name} ${conversation.last_message} ${conversation.product_name || ""}`
                .toLocaleLowerCase("vi")
                .includes(query),
        );
    }

    function renderConversationList() {
        const visible = filteredConversations();
        const fragment = document.createDocumentFragment();

        if (!visible.length) {
            const empty = document.createElement("div");
            empty.className = "store-chat-state";
            empty.textContent = conversations.length
                ? "Không tìm thấy cuộc trò chuyện phù hợp."
                : "Chưa có cuộc trò chuyện. Hãy nhấn “Chat với Shop” tại một cửa hàng.";
            fragment.append(empty);
            list.replaceChildren(fragment);
            return;
        }

        visible.forEach((conversation) => {
            const button = document.createElement("button");
            button.type = "button";
            button.className =
                "store-chat-conversation" +
                (String(activeConversation?.id) === String(conversation.id)
                    ? " active"
                    : "");
            button.dataset.conversationId = String(conversation.id);

            const avatar = document.createElement("span");
            avatar.className = "store-chat-conversation-avatar";
            renderShopAvatar(
                avatar,
                conversation.shop_avatar_url,
                "store-chat-conversation-avatar",
            );

            const body = document.createElement("span");
            body.className = "store-chat-conversation-body";

            const top = document.createElement("span");
            top.className = "store-chat-conversation-top";

            const name = document.createElement("strong");
            name.className = "store-chat-conversation-name";
            name.textContent = conversation.shop_name || "Cửa hàng";

            const time = document.createElement("small");
            time.className = "store-chat-conversation-time";
            time.textContent = conversation.last_message_at || "";

            top.append(name, time);

            const bottom = document.createElement("span");
            bottom.className = "store-chat-conversation-bottom";

            const preview = document.createElement("small");
            preview.className = "store-chat-conversation-preview";
            preview.textContent =
                conversation.last_message || "Bắt đầu cuộc trò chuyện";

            bottom.append(preview);

            if (Number(conversation.unread_count) > 0) {
                const badge = document.createElement("span");
                badge.className = "store-chat-unread";
                badge.textContent =
                    Number(conversation.unread_count) > 99
                        ? "99+"
                        : String(conversation.unread_count);
                bottom.append(badge);
            }

            body.append(top, bottom);
            button.append(avatar, body);
            button.addEventListener("click", () =>
                openConversation(conversation),
            );
            fragment.append(button);
        });

        list.replaceChildren(fragment);
    }

    async function loadConversations() {
        if (listLoadingPromise) return listLoadingPromise;

        listLoadingPromise = (async () => {
            try {
                const data = await request(widget.dataset.listUrl);
                conversations = data.conversations || [];
                updateUnreadBadge(data.unread_total);
                renderConversationList();

                if (activeConversation) {
                    const refreshed = conversations.find(
                        (conversation) =>
                            String(conversation.id) ===
                            String(activeConversation.id),
                    );

                    if (refreshed) {
                        activeConversation = refreshed;
                        title.textContent = refreshed.shop_name || "Cửa hàng";
                        title.href = refreshed.shop_url || "#";
                        title.setAttribute(
                            "aria-label",
                            `Mở trang ${refreshed.shop_name || "cửa hàng"}`,
                        );

                        renderShopAvatar(
                            shopAvatar,
                            refreshed.shop_avatar_url,
                            "store-chat-shop-avatar",
                        );
                    }
                }
            } catch (error) {
                if (!conversations.length) {
                    list.textContent = error.message;
                    list.classList.add("store-chat-state");
                }
            }
        })();

        try {
            await listLoadingPromise;
        } finally {
            listLoadingPromise = null;
        }
    }

    async function openConversation(conversation) {
        activeConversation = conversation;
        title.textContent = conversation.shop_name || "Cửa hàng";
        title.href = conversation.shop_url || "#";
        title.setAttribute(
            "aria-label",
            `Mở trang ${conversation.shop_name || "cửa hàng"}`,
        );

        renderShopAvatar(
            shopAvatar,
            conversation.shop_avatar_url,
            "store-chat-shop-avatar",
        );

        productContext.textContent = conversation.product_name
            ? `Trao đổi về: ${conversation.product_name}`
            : "Tin nhắn riêng với cửa hàng";

        welcome.classList.add("d-none");
        thread.classList.remove("d-none");
        input.disabled = false;
        sendButton.disabled = false;

        setError();
        renderConversationList();
        await loadMessages(true);
        input.focus();
    }

    async function loadMessages(reset = false) {
        if (
            !activeConversation ||
            messagesLoading ||
            panel.classList.contains("d-none")
        ) {
            return;
        }

        messagesLoading = true;

        if (reset) {
            messagesBox.innerHTML =
                '<div class="store-chat-state">Đang tải tin nhắn…</div>';
            lastMessageId = 0;
        }

        try {
            const url = `${activeConversation.messages_url}?after_id=${lastMessageId}`;
            const data = await request(url);

            renderMessages(data.messages || [], reset);
            setError();
            loadConversations();
        } catch (error) {
            setError(error.message);
        } finally {
            messagesLoading = false;
        }
    }

    launcher.addEventListener("click", () => {
        setPanelOpen(panel.classList.contains("d-none"));
    });

    minimizeButton.addEventListener("click", () => setPanelOpen(false));

    collapseButton.addEventListener("click", () => {
        const collapsed = panel.classList.toggle("is-collapsed");

        collapseButton.setAttribute(
            "aria-label",
            collapsed ? "Mở rộng khung chat" : "Thu gọn về danh sách chat",
        );
        collapseButton.title = collapsed ? "Mở rộng chat" : "Thu gọn chat";
        collapseButton.innerHTML = collapsed
            ? '<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>'
            : '<i class="bi bi-arrows-angle-contract" aria-hidden="true"></i>';
    });

    backButton.addEventListener("click", () => {
        activeConversation = null;
        thread.classList.add("d-none");
        welcome.classList.remove("d-none");
        renderConversationList();
    });

    search.addEventListener("input", renderConversationList);

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && !panel.classList.contains("d-none")) {
            setPanelOpen(false);
        }
    });

    document.addEventListener("click", async (event) => {
        const trigger = event.target.closest("[data-chat-start]");
        if (!trigger) return;

        event.preventDefault();
        setPanelOpen(true);
        setError();
        trigger.disabled = true;

        try {
            const data = await request(widget.dataset.createUrl, "POST", {
                seller_id: Number(trigger.dataset.sellerId),
                product_id: trigger.dataset.productId
                    ? Number(trigger.dataset.productId)
                    : null,
            });

            let conversation = conversations.find(
                (item) => String(item.id) === String(data.conversation_id),
            ) || {
                id: data.conversation_id,
                shop_name:
                    data.shop_name || trigger.dataset.shopName || "Cửa hàng",
                shop_avatar_url: data.shop_avatar_url || null,
                shop_url: data.shop_url || "#",
                product_name: trigger.dataset.productId
                    ? "Sản phẩm đang xem"
                    : null,
                messages_url: data.messages_url,
                send_url: data.send_url,
                unread_count: 0,
            };

            if (
                !conversations.some(
                    (item) => String(item.id) === String(conversation.id),
                )
            ) {
                conversations.unshift(conversation);
            }
            renderConversationList();

            // Open from the create response immediately. The initial inbox refresh
            // may still be running and can return the pre-create empty list.
            await openConversation(conversation);
            void loadConversations();
        } catch (error) {
            // Error area sits outside the thread so a failed first chat is visible
            // even when the customer has no conversation yet.
            setError(error.message);
        } finally {
            trigger.disabled = false;
        }
    });

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const body = input.value.trim();
        if (!activeConversation || !body) return;

        sendButton.disabled = true;

        try {
            const data = await request(activeConversation.send_url, "POST", {
                body,
            });

            renderMessages([data.message]);
            input.value = "";
            setError();
            await loadConversations();
        } catch (error) {
            setError(error.message);
        } finally {
            sendButton.disabled = false;
            input.focus();
        }
    });

    input.addEventListener("keydown", (event) => {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });

    loadConversations();
    window.setInterval(loadConversations, 15000);
    window.setInterval(() => loadMessages(false), 3000);
})();
