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
    .chat-image-wrapper {
        margin: 8px 0;
        max-width: 100%;
    }

    .chat-image {
        max-width: 250px;
        max-height: 300px;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.2s;
        display: block;
    }

    .chat-image:hover {
        transform: scale(1.02);
    }

    .chat-file-link {
        display: inline-block;
        padding: 8px 12px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        text-decoration: none;
        color: inherit;
        margin: 4px 0;
        transition: background 0.2s;
    }

    .chat-file-link:hover {
        background: rgba(0, 0, 0, 0.15);
    }

    .my-message .chat-image-wrapper {
        text-align: right;
    }

    .their-message .chat-image-wrapper {
        text-align: left;
    }

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
        background-size: 20px 20px;
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
        background-image: url("/new_project_bk/uploads/chat.robot/whatsapp.png");
        background-repeat: repeat;
        background-size: cover;
        background-position: center;
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
        padding: 10px 14px 10px 18px;
        border-radius: 25px;
        border: 1px solid #ccc;
        outline: none;
        background-size: 20px 20px;
    }

    #chatInputWrapper input[type="file"] {
        display: none;
    }

    #chatUserIcon {
        position: fixed;
        bottom: 100px;
        right: 16px;
        z-index: 3003;
        cursor: pointer;
    }

    #chatUserIcon .chat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s;
    }

    #chatUserIcon .chat-icon:hover {
        transform: scale(1.2);
    }



    #chatInputWrapper button {
        padding: 10px 14px;
        border-radius: 50%;
        border: none;
        background: #075E54;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    #chatInputWrapper button:hover {
        background: #064d46;
        transform: scale(1.05);
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

    .chat-date-separator {
        text-align: center;
        background: rgba(0, 0, 0, 0.1);
        color: #555;
        font-size: 12px;
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 12px;
        margin: 10px auto;
        max-width: 60%;
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
    <div style="padding:10px; border-top:1px solid #ddd; display:flex; gap:10px; align-items:center;">
        <input id="chatInput" type="text" placeholder="Shkruaj mesazhin..."
            style="flex:1; padding:10px 13px; font-size:14px; border-radius:23px; border:1px solid #ccc; outline:none;">
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
                <span id="chatFileIcon" style="cursor:pointer; font-size:24px; margin-right:8px;">📷</span>
                <input type="file" id="chatUserFile" name="file" style="display:none;">
                <input type="text" id="chatUserInput" name="message" placeholder="Shkruaj mesazhin...">
                <button type="submit">➤</button>
            </div>
        </form>
    </div>
</div>

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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
    $(function() {
        const USER_ID = <?php echo $_SESSION['user_id'] ?? 0; ?>;
        const IS_ADMIN = <?php echo $_SESSION['is_admin'] ?? 0; ?>;
        const GUEST_ID = <?php echo $_SESSION['guest_id'] ?? 0; ?>;

        const CURRENT_USER_TYPE = USER_ID > 0 ? (IS_ADMIN ? 'admin' : 'user') : 'guest';
        const CURRENT_USER_ID = USER_ID > 0 ? USER_ID : GUEST_ID;

        let selectedContact = null;
        let loadedMessageIds = new Set();
        let pollInterval = null;
        let activeChannel = null;

        Pusher.logToConsole = true;
        const pusher = new Pusher('d0652d5ed102a0e6056c', {
            cluster: 'eu',
            useTLS: true
        });

        const myChannelName = `chat-${CURRENT_USER_TYPE}-${CURRENT_USER_ID}`;
        console.log('Subscribing to my channel:', myChannelName);

        const myChannel = pusher.subscribe(myChannelName);

        myChannel.bind('pusher:subscription_succeeded', function() {
            console.log('Successfully subscribed to:', myChannelName);
        });

        myChannel.bind('pusher:subscription_error', function(status) {
            console.error('Subscription error:', status);
        });

        myChannel.bind('new-message', function(data) {
            console.log('Received message on my channel:', data);

            if (selectedContact) {
                const isFromSelectedContact =
                    (data.sender_type === selectedContact.contact_type &&
                        String(data.sender_id) === String(selectedContact.contact_id)) ||
                    (data.receiver_type === selectedContact.contact_type &&
                        String(data.receiver_id) === String(selectedContact.contact_id));

                if (isFromSelectedContact) {
                    appendMessage(data);
                }
            }
        });

        function appendMessage(msg, scroll = true) {
            if (loadedMessageIds.has(msg.id)) {
                console.log('Message already displayed:', msg.id);
                return;
            }

            loadedMessageIds.add(msg.id);
            const body = $('#chatUserBody');
            const isMine = String(msg.sender_id) === String(CURRENT_USER_ID) && msg.sender_type === CURRENT_USER_TYPE;

            const bubble = $('<div>').addClass('chat-bubble').addClass(isMine ? 'my-message' : 'their-message');
            let content = '';
            if (msg.message) content += $('<div>').text(msg.message).html();
            if (msg.file_path) content += '<br><a href="/new_project_bk/uploads/chat_files/' + msg.file_path + '" target="_blank">📎 File</a>';

            bubble.html(content + '<div class="msg-time">' + new Date(msg.created_at).toLocaleTimeString() + '</div>');
            body.append(bubble);

            if (scroll) body.scrollTop(body[0].scrollHeight);

            console.log('Message appended:', msg.id, 'isMine:', isMine);
        }

        function fetchContacts() {
            $.post('../../../helper/send_message.php', {
                action: 'fetch_contacts'
            }, function(resp) {
                if (!resp.success) return;
                const $list = $('#chatContacts').empty();
                resp.contacts.forEach(c => {
                    const unread = c.unread_count > 0 ? `<span class="unread-count">${c.unread_count}</span>` : '';
                    const $item = $(`<div class="contact-item" data-id="${c.contact_id}" data-type="${c.contact_type}">
                    <div><strong>${c.contact_name}</strong><small>${c.last_message ?? ''}</small></div>${unread}</div>`);
                    $item.off('click').on('click', () => selectContact(c));
                    $list.append($item);
                });
            }, 'json');
        }

        function selectContact(contact) {
            selectedContact = contact;
            $('#receiver_id').val(contact.contact_id);
            $('#receiver_type').val(contact.contact_type);
            $('#chatUserTitle').text('Biseda me: ' + (contact.contact_type === 'guest' ? `Guest #${contact.contact_id}` : contact.contact_name));
            $('#chatUserBody').empty();
            loadedMessageIds.clear();
            $('.contact-item').removeClass('active');
            $(`.contact-item[data-id="${contact.contact_id}"][data-type="${contact.contact_type}"]`).addClass('active');

            console.log('Selected contact:', contact.contact_type, contact.contact_id);

            fetchMessages();
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(fetchMessages, 3000);
        }

        function fetchMessages() {
            if (!selectedContact) return;
            $.post('../../../helper/send_message.php', {
                action: 'fetch_messages',
                receiver_id: selectedContact.contact_id,
                receiver_type: selectedContact.contact_type
            }, function(resp) {
                if (!resp.success) return;
                resp.messages.forEach(m => appendMessage(m, false));
                $('#chatUserBody').scrollTop($('#chatUserBody')[0].scrollHeight);
            }, 'json');
        }

        $('#chatUserForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            if (!selectedContact) return alert('Zgjidhni një kontakt!');

            const msg = $('#chatUserInput').val().trim();
            const fileInput = $('#chatUserFile')[0];

            if (!msg && (!fileInput || fileInput.files.length === 0)) return;

            const fd = new FormData();
            fd.append('action', 'send');
            fd.append('receiver_id', selectedContact.contact_id);
            fd.append('receiver_type', selectedContact.contact_type);
            fd.append('message', msg);
            if (fileInput && fileInput.files.length > 0) fd.append('file', fileInput.files[0]);

            console.log('Sending message to:', selectedContact.contact_type, selectedContact.contact_id);

            $('#chatUserInput').val('');
            $('#chatUserFile').val('');

            $.ajax({
                url: '../../../helper/send_message.php',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log('Message sent successfully:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error sending message:', error);
                }
            });
        });

        $('#chatFileIcon').on('click', () => $('#chatUserFile').click());
        $('#chatUserIcon').on('click', () => {
            $('#chatUserWidget').toggle();
            if ($('#chatUserWidget').is(':visible')) fetchContacts();
        });
        $('#chatUserClose').on('click', () => $('#chatUserWidget').hide());

        $(document).ready(() => {
            $('#chatUserWidget').hide();
            fetchContacts();
            console.log('Chat initialized - User Type:', CURRENT_USER_TYPE, 'User ID:', CURRENT_USER_ID);
        });
    });
</script>

</body>

</html>