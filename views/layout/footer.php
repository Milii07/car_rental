<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>
                    document.write(new Date().getFullYear())
                </script> © Velzon.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by Themesbrand
                </div>
            </div>
        </div>
    </div>
</footer>
</div>

</div>

<style>
    #chatRobot {
        position: fixed;
        bottom: 30px;
        right: 16px;
        z-index: 3002;
        cursor: pointer;
    }

    #chatRobot img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s;
    }

    #chatRobot img:hover {
        transform: scale(1.2);
    }

    #chatWidget {
        display: none;
        flex-direction: column;
        position: fixed;
        bottom: 100px;
        right: 90px;
        width: 360px;
        height: 520px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        z-index: 3001;
    }

    #chatHeader {
        background: #075E54;
        color: #fff;
        padding: 10px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #chatHeader span.close-chat {
        font-size: 18px;
        cursor: pointer;
    }

    #chatBody {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        background: #e5ddd5;
    }

    .chat-message {
        padding: 6px 10px;
        border-radius: 12px;
        max-width: 85%;
        word-break: break-word;
    }

    .chat-user {
        background: #34b7f1;
        color: #fff;
        align-self: flex-end;
    }

    .chat-bot {
        background: #fff;
        color: #000;
        align-self: flex-start;
    }

    #chatInputWrapper {
        display: flex;
        align-items: center;
        padding: 5px;
        border-top: 1px solid #ddd;
        background: #fff;
    }

    #chatInput {
        flex: 1;
        padding: 8px 40px 8px 35px;
        border-radius: 18px;
        border: 1px solid #ccc;
        position: relative;
        outline: none;
        background: url('data:image/svg+xml;utf8,<svg fill="%23999" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 19V5a2 2 0 0 0-2-2H5C3.895 3 3 3.895 3 5v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zm-2-9l-5 6-3-4-4 5h12z"/></svg>') no-repeat 10px center;
        background-size: 20px 20px;
    }

    #chatUserIcon {
        position: fixed;
        bottom: 95px;
        right: 15px;
        z-index: 3003;
        cursor: pointer;
    }

    #chatUserIcon .chat-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s;
    }

    #chatUserIcon .chat-icon:hover {
        transform: scale(1.2);
    }

    #chatUserWidget {
        display: none;
        position: fixed;
        bottom: 120px;
        right: 90px;
        width: 550px;
        height: 650px;
        background: #f0f2f5;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: row;
        overflow: hidden;
        z-index: 3002;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #chatSidebar {
        width: 150px;
        background: #fff;
        border-right: 1px solid #ccc;
        overflow-y: auto;
    }

    #chatSidebarHeader {
        padding: 16px;
        font-weight: bold;
        background: #075E54;
        color: #fff;
        text-align: center;
        font-size: 18px;
    }

    .contact-item {
        padding: 10px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #eee;
        flex-direction: column;
    }

    .contact-item:hover {
        background: #f0f0f0;
    }

    .contact-item.active {
        background: #dcf8c6;
    }

    .unread-count {
        background: red;
        color: #fff;
        padding: 2px 6px;
        border-radius: 12px;
        font-size: 12px;
        align-self: flex-end;
    }

    #chatArea {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    #chatUserHeader {
        background: #075E54;
        color: #fff;
        padding: 16px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 18px;
    }

    #chatUserHeader .close-chat {
        cursor: pointer;
        font-size: 12px;
    }

    #chatUserBody,
    #chatBody {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 8px;
        background-color: #e5ddd5;
        background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"%3E%3Ccircle cx="12" cy="12" r="3" fill="%23666" opacity="0.05"/%3E%3Ccircle cx="36" cy="36" r="3" fill="%23666" opacity="0.05"/%3E%3Ccircle cx="36" cy="12" r="3" fill="%23666" opacity="0.05"/%3E%3Ccircle cx="12" cy="36" r="3" fill="%23666" opacity="0.05"/%3E%3C/svg%3E');
        background-repeat: repeat;
        background-size: 48px 48px;
    }




    .chat-bubble {
        padding: 10px 14px;
        border-radius: 20px;
        max-width: 75%;
        word-break: break-word;
        font-size: 14px;
        line-height: 1.4;
    }

    .my-message {
        background-color: #dcf8c6;
        color: #000;
        align-self: flex-end;
    }

    .their-message {
        background-color: #fff;
        color: #000;
        align-self: flex-start;
    }

    #chatInputWrapper {
        display: flex;
        gap: 8px;
        padding: 10px;
        border-top: 1px solid #ccc;
        background: #fff;
    }

    #chatInputWrapper input[type="text"] {
        flex: 1;
        padding: 10px 14px 10px 40px;
        border-radius: 25px;
        border: 1px solid #ccc;
        outline: none;
        background: url('data:image/svg+xml;utf8,<svg fill="%23999" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 19V5a2 2 0 0 0-2-2H5C3.895 3 3 3.895 3 5v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zm-2-9l-5 6-3-4-4 5h12z"/></svg>') no-repeat 10px center;
        background-size: 20px 20px;
    }

    #chatInputWrapper input[type="file"] {
        display: none;
    }

    #chatInputWrapper button {
        padding: 10px 14px;
        border-radius: 50%;
        border: none;
        background: #075E54;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
    }

    #chatInputWrapper button:hover {
        background: #064d46;
    }

    #chatUserBody::-webkit-scrollbar,
    #chatContacts::-webkit-scrollbar {
        width: 6px;
    }

    #chatUserBody::-webkit-scrollbar-thumb,
    #chatContacts::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }

    .chat-calendar {
        background: #f4f6f8;
        border-radius: 10px;
        padding: 10px;
        margin: 10px 0;
    }

    .chat-calendar input {
        display: block;
        width: 100%;
        margin: 6px 0;
        padding: 5px;
    }

    .chat-calendar button {
        margin-top: 8px;
        background: #007bff;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .chat-calendar button:hover {
        background: #0056b3;
    }

    #back-to-top {
        position: fixed;
        bottom: 170px;
        right: 23px;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 60px;
        height: 55px;
        border-radius: 50%;
        background-color: #dc3545;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s;
    }

    #back-to-top:hover {
        transform: translateY(-5px);
    }

    #back-to-top i {
        font-size: 20px;
    }

    #chatBody::-webkit-scrollbar,
    #chatUserBody::-webkit-scrollbar,
    #chatContacts::-webkit-scrollbar {
        width: 6px;
    }

    #chatBody::-webkit-scrollbar-thumb,
    #chatUserBody::-webkit-scrollbar-thumb,
    #chatContacts::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
</style>

<div id="chatRobot">
    <img src="/new_project_bk/uploads/chat.robot/Chat.jpg" alt="Chat Robot" style="width:60px; height:60px; border-radius:50%; box-shadow:0 4px 15px rgba(0,0,0,0.3);">
</div>

<div id="chatWidget">
    <div id="chatHeader">
        Chat Auto Future Block
        <span id="chatClose" style="float:right; cursor:pointer;">✖</span>
    </div>
    <div id="chatBody"></div>
    <div style="padding:10px; border-top:1px solid #ddd;">
        <input id="chatInput" type="text" placeholder="Shkruaj mesazhin...">
    </div>
</div>

<div id="chatUserIcon">
    <img src="/new_project_bk/uploads/chat.robot/mm.jpg" class="chat-icon" alt="Chat">
</div>
<div id="chatUserWidget">
    <div id="chatSidebar">
        <div id="chatSidebarHeader">Kontakti</div>
        <div id="chatContacts"></div>
    </div>
    <div id="chatArea">
        <div id="chatUserHeader">
            <span id="chatUserTitle">Biseda</span>
            <span id="chatUserClose" class="close-chat">✖</span>
        </div>
        <div id="chatUserBody"></div>
        <form id="chatUserForm">
            <input type="hidden" id="receiver_id" name="receiver_id">
            <input type="hidden" id="receiver_type" name="receiver_type">

            <div id="chatInputWrapper">
                <span id="chatFileIcon" style="cursor:pointer; font-size:20px; margin-right:8px;">📷</span>
                <input type="file" id="chatUserFile" name="file" style="display:none;">
                <input type="text" id="chatUserInput" name="message" placeholder="Shkruaj mesazhin...">
                <button type="submit">➤</button>
            </div>
        </form>
    </div>
</div>

<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>

<link rel="stylesheet" href="/new_project_bk/public/assets/libs/flatpickr/flatpickr.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/new_project_bk/public/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/new_project_bk/public/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/new_project_bk/public/assets/libs/node-waves/waves.min.js"></script>
<script src="/new_project_bk/public/assets/libs/feather-icons/feather.min.js"></script>
<script src="/new_project_bk/public/assets/libs/flatpickr/flatpickr.min.js"></script>

<script>
    const BASE_URL = "<?php echo BASE_URL; ?>";
    const UPLOADS_URL = "<?php echo UPLOADS_URL; ?>";
</script>

<script>
    const chatRobot = document.getElementById('chatRobot');
    const chatWidget = document.getElementById('chatWidget');
    const chatClose = document.getElementById('chatClose');
    const chatBody = document.getElementById('chatBody');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');

    let lastBotResponse = null;

    const appendMessage = (msg, sender = 'bot') => {
        const div = document.createElement('div');
        div.classList.add('chat-message', sender === 'bot' ? 'chat-bot' : 'chat-user');
        div.innerHTML = msg;
        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    const sendMessage = async (payload, autoAppend = true) => {
        try {
            const res = await fetch('/new_project_bk/helper/chatHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            lastBotResponse = data;
            if (autoAppend && data.reply) appendMessage(data.reply, 'bot');
            return data;
        } catch (err) {
            console.error(err);
            appendMessage('Gabim gjatë komunikimit me serverin', 'bot');
        }
    }

    const greetOnOpen = async () => await sendMessage({
        event: 'open'
    });

    const showReservationCalendar = (startDate) => {
        if (document.querySelector('.chat-calendar')) return;

        const calendarDiv = document.createElement('div');
        calendarDiv.classList.add('chat-calendar');
        calendarDiv.innerHTML = `
        <div class="calendar-box">
            <p><strong>Zgjidh datat e rezervimit:</strong></p>
            <label>Data e nisjes:</label>
            <input type="date" id="start-date" min="${startDate}" value="${startDate}">
            <label>Data e mbarimit:</label>
            <input type="date" id="end-date" min="${startDate}">
            <button id="confirm-reservation">Rezervo</button>
        </div>
    `;
        chatBody.appendChild(calendarDiv);
        chatBody.scrollTop = chatBody.scrollHeight;

        const oldBtn = document.getElementById('confirm-reservation');
        if (oldBtn) oldBtn.replaceWith(oldBtn.cloneNode(true));

        document.getElementById('confirm-reservation').addEventListener('click', async () => {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;

            if (!start || !end) {
                alert("Zgjidh datat e rezervimit!");
                return;
            }

            appendMessage(`Rezervimi nga ${start} deri më ${end}.`, 'user');

            const res = await sendMessage({
                action: 'reserve',
                start_date: start,
                end_date: end
            }, false);
            if (res.reply) appendMessage(res.reply, 'bot');

            document.querySelector('.chat-calendar')?.remove();
        });
    }

    const handleUserMessage = async (msg) => {
        appendMessage(msg, 'user');

        if (lastBotResponse && lastBotResponse.expected_confirmations && lastBotResponse.next_available_date) {
            const positiveWords = lastBotResponse.expected_confirmations.map(w => w.toLowerCase());
            if (positiveWords.some(w => msg.toLowerCase().includes(w))) {
                showReservationCalendar(lastBotResponse.next_available_date);
                return;
            }
        }

        await sendMessage({
            message: msg
        });
    }

    chatInput.addEventListener('keypress', async e => {
        if (e.key === 'Enter' && chatInput.value.trim() !== '') {
            const msg = chatInput.value.trim();
            chatInput.value = '';
            await handleUserMessage(msg);
        }
    });

    chatSendBtn?.addEventListener('click', async () => {
        const msg = chatInput.value.trim();
        if (!msg) return;
        chatInput.value = '';
        await handleUserMessage(msg);
    });

    chatRobot.addEventListener('click', async () => {
        if (chatWidget.style.display !== 'flex') {
            chatWidget.style.display = 'flex';
            chatInput.focus();
            await greetOnOpen();
        } else {
            chatWidget.style.display = 'none';
        }
    });

    chatClose.addEventListener('click', () => chatWidget.style.display = 'none');
</script>

<script>
    const USER_ID = <?php echo $_SESSION['user_id'] ?? 0; ?>;
    const IS_ADMIN = <?php echo $_SESSION['is_admin'] ?? 0; ?>;

    let selectedContact = null;
    let lastMessageId = 0;
    let lastDate = '';

    function formatTime(dateStr) {
        const d = new Date(dateStr);
        return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
    }

    function formatDateSeparator(dateStr) {
        const d = new Date(dateStr);
        const dateKey = d.toDateString();
        if (dateKey !== lastDate) {
            lastDate = dateKey;
            return `<div class="chat-date-separator">${d.toLocaleDateString('sq-AL', { weekday: 'long', day: '2-digit', month:'2-digit', year:'numeric' })}</div>`;
        }
        return '';
    }

    function fetchContacts() {
        $.post('../../../helper/send_message.php', {
            action: 'fetch_contacts'
        }, function(resp) {
            if (!resp.success) return;

            const $list = $('#chatContacts').empty();

            resp.contacts.forEach(c => {
                const unread = c.unread_count > 0 ? `<span class="unread-count">${c.unread_count}</span>` : '';
                const contactType = IS_ADMIN ? 'user' : 'admin';
                const $item = $(`
                <div class="contact-item" data-id="${c.contact_id}" data-type="${contactType}">
                    <div style="display:flex;flex-direction:column;">
                        <strong>${c.contact_name}</strong>
                        <small>${c.last_message ?? ''}</small>
                    </div>
                    ${unread}
                </div>
            `);

                $item.on('click', () => selectContact({
                    contact_id: c.contact_id,
                    contact_name: c.contact_name,
                    contact_type: contactType
                }));

                $list.append($item);
            });

            // Zgjidh kontaktin e fundit nga localStorage
            const lastContact = localStorage.getItem('lastContact');
            if (lastContact) selectContact(JSON.parse(lastContact));
            else if (resp.contacts.length > 0) selectContact({
                contact_id: resp.contacts[0].contact_id,
                contact_name: resp.contacts[0].contact_name,
                contact_type: IS_ADMIN ? 'user' : 'admin'
            });
        }, 'json');
    }

    function selectContact(contact) {
        selectedContact = contact;
        localStorage.setItem('lastContact', JSON.stringify(contact));

        $('#receiver_id').val(contact.contact_id);
        $('#receiver_type').val(contact.contact_type);
        $('#chatUserTitle').text('Biseda me: ' + contact.contact_name);

        lastMessageId = 0;
        lastDate = '';
        $('#chatUserBody').empty();

        fetchMessages(true);

        // Poll për mesazhet e reja çdo 2 sekonda
        if (window.pollInterval) clearInterval(window.pollInterval);
        window.pollInterval = setInterval(() => fetchMessages(), 2000);
    }

    function fetchMessages(initial = false) {
        if (!selectedContact) return;

        $.post('../../../helper/send_message.php', {
            action: 'fetch_messages',
            receiver_id: selectedContact.contact_id,
            receiver_type: selectedContact.contact_type,
            last_id: lastMessageId
        }, function(resp) {
            if (!resp.success) return;

            const $body = $('#chatUserBody');
            if (initial) $body.empty();

            resp.messages.forEach(m => {
                if (m.id <= lastMessageId) return;
                lastMessageId = m.id;

                const isMine = m.sender_id == USER_ID && m.sender_type == (IS_ADMIN ? 'admin' : 'user');
                const $msg = $('<div class="chat-bubble">').addClass(isMine ? 'my-message' : 'their-message');

                let content = m.message ? $('<div>').text(m.message).html() : '';
                if (m.file_path)
                    content += `<br><a href="/new_project_bk/uploads/chat_files/${m.file_path}" target="_blank">📎 Shiko/merr file</a>`;

                const sep = formatDateSeparator(m.created_at);
                if (sep) $body.append(sep);

                $msg.html(content + `<div class="msg-time">${formatTime(m.created_at)}</div>`);
                $body.append($msg);
            });

            $body.scrollTop($body[0].scrollHeight);
        }, 'json');
    }

    $('#chatUserForm').on('submit', function(e) {
        e.preventDefault();
        if (!selectedContact) {
            alert('Zgjidhni një kontakt!');
            return;
        }

        const fd = new FormData(this);
        fd.append('action', 'send');

        const messageText = $('#chatUserInput').val().trim();
        const fileInput = $('#chatUserFile')[0];
        let fileName = '';

        if (messageText || (fileInput && fileInput.files.length > 0)) {
            if (fileInput && fileInput.files.length > 0) fileName = fileInput.files[0].name;
            appendMessageToChat(messageText, fileName);
        }

        $.ajax({
            url: '../../../helper/send_message.php',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                if (!resp.success) alert(resp.message || 'Gabim gjatë dërgimit');
                $('#chatUserInput').val('');
                $('#chatUserFile').val('');
            }
        });
    });

    function appendMessageToChat(message, file_path = '') {
        const $body = $('#chatUserBody');
        const $msg = $('<div class="chat-bubble my-message">');
        let content = message ? $('<div>').text(message).html() : '';
        if (file_path)
            content += `<br><a href="/new_project_bk/uploads/chat_files/${file_path}" target="_blank">📎 Shiko/merr file</a>`;

        const now = new Date();
        const dateSeparator = formatDateSeparator(now);
        if (dateSeparator) $body.append(dateSeparator);

        $msg.html(content + `<div class="msg-time">${formatTime(now)}</div>`);
        $body.append($msg);
        $body.scrollTop($body[0].scrollHeight);
    }

    $('#chatUserIcon').on('click', () => {
        $('#chatUserWidget').fadeToggle(150);
        fetchContacts();
    });
    $('#chatUserClose').on('click', () => {
        $('#chatUserWidget').fadeOut(100);
        if (window.pollInterval) clearInterval(window.pollInterval);
    });
    $('#chatUserInput').on('keypress', function(e) {
        if (e.key === 'Enter' && $(this).val().trim() !== '') $('#chatUserForm').submit();
    });

    $(document).ready(() => {
        fetchContacts();
    });
</script>

</body>

</html>