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
    #chatRobot img:hover {
        transform: scale(1.2);
        transition: 0.1s;
    }

    #chatWidget {
        display: none;
        flex-direction: column;
        position: fixed;
        bottom: 100px;
        right: 20px;
        width: 320px;
        max-height: 420px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        z-index: 3001;
    }

    #chatHeader {
        background: #1E40AF;
        color: #fff;
        padding: 10px;
        font-weight: bold;
        cursor: pointer;
    }

    #chatBody {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    #chatInput {
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .chat-message {
        padding: 6px 10px;
        border-radius: 8px;
        max-width: 85%;
    }

    .chat-user {
        background: #032c69ff;
        color: #fff;
        align-self: flex-end;
    }

    .chat-bot {
        background: #df4646ff;
        color: #fff;
        align-self: flex-start;
    }

    #chatRobot {
        position: fixed;
        bottom: 30px;
        right: 16px;
        z-index: 3002;
        cursor: pointer;
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

<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>


<script src="/new_project_bk/public/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/new_project_bk/public/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/new_project_bk/public/assets/libs/node-waves/waves.min.js"></script>
<script src="/new_project_bk/public/assets/libs/feather-icons/feather.min.js"></script>
<script src="/new_project_bk/public/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="/new_project_bk/public/assets/js/plugins.js"></script>
<script src="/new_project_bk/public/assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    const chatRobot = document.getElementById('chatRobot');
    const chatWidget = document.getElementById('chatWidget');
    const chatClose = document.getElementById('chatClose');
    const chatBody = document.getElementById('chatBody');
    const chatInput = document.getElementById('chatInput');

    chatRobot.addEventListener('click', () => {
        chatWidget.style.display = chatWidget.style.display === 'flex' ? 'none' : 'flex';
        chatInput.focus();
    });

    chatClose.addEventListener('click', () => chatWidget.style.display = 'none');

    const appendMessage = (msg, sender = 'bot') => {
        const div = document.createElement('div');
        div.classList.add('chat-message', sender === 'bot' ? 'chat-bot' : 'chat-user');
        div.textContent = msg;
        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    chatInput.addEventListener('keypress', async e => {
        if (e.key === 'Enter' && chatInput.value.trim() !== '') {
            const msg = chatInput.value.trim();
            appendMessage(msg, 'user');
            chatInput.value = '';
            try {
                const res = await fetch('/new_project_bk/helper/chatHandler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: msg
                    })
                });
                const data = await res.json();
                appendMessage(data.reply || 'Gabim gjatë marrjes së përgjigjes', 'bot');
            } catch (err) {
                console.error(err);
                appendMessage('Gabim gjatë komunikimit me serverin', 'bot');
            }
        }
    });
</script>
</body>

</html>