<!DOCTYPE html>
<html>

<head>
    <title>Laravel Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial;
            display: flex;
            height: 100vh;
            margin: 0;
        }

        #userList {
            width: 25%;
            border-right: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
        }

        #chatBox {
            width: 75%;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        #messages {
            flex: 1;
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
            margin-bottom: 10px;
        }

        .message {
            margin: 5px 0;
        }

        .from-me {
            text-align: right;
            font-weight: bold;
        }

        #messageForm {
            display: flex;
        }

        #messageInput {
            flex: 1;
            padding: 5px;
        }

        #sendBtn {
            padding: 5px 10px;
        }
    </style>
</head>

<body>

    <b>Me: {{ session('fullname') }}</b>
    <div id="userList">
        <h3>Users</h3>
        <ul>
            @php
                $users = DB::table('users')->where('uid', '<>', session('uid'))->get();
            @endphp
            @foreach($users as $user)
                <li><a href="#" class="chatUser" data-id="{{ $user->uid }}">{{ $user->full_name }}</a></li>
            @endforeach
        </ul>
    </div>

    <div id="chatBox">
        <h3>Chat with <span id="chatWith">Select a user</span></h3>
        <div id="messages"></div>

        <!-- Use div+button instead of form to avoid accidental POST -->
        <div id="messageForm">
            <input type="text" id="messageInput" placeholder="Type a message..." disabled>
            <button id="sendBtn" disabled>Send</button>
        </div>
    </div>

    <!-- Pusher + Laravel Echo CDN -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

    <script>
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            wsHost: window.location.hostname,
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws'],
            authEndpoint: 'http://localhost/laravelchat/public/broadcasting/auth',
        });
    </script>
    <script>

        const CHAT_SEND_URL = '{{ route("chat.send") }}';

        const USER_ID = {{ session('uid') }};
        let currentChatUser = null;

        // Select user to chat
        document.querySelectorAll('.chatUser').forEach(el => {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                currentChatUser = this.dataset.id;
                document.getElementById('chatWith').innerText = this.innerText;
                document.getElementById('messageInput').disabled = false;
                document.getElementById('sendBtn').disabled = false;
                document.getElementById('messages').innerHTML = ''; // clear messages
            });
        });

        // Listen for incoming messages

        window.Echo.private(`chat.${USER_ID}`)
            .listen('.message.received', e => {
                console.log('RECEIVED', e);

                if (currentChatUser !== e.fromUserId) return;

                const div = document.createElement('div');
                div.className = 'message';
                div.innerText = e.message;
                document.getElementById('messages').appendChild(div);
            });

        // Send message
        document.getElementById('sendBtn').addEventListener('click', function () {
            if (!currentChatUser) return alert('Select a user to chat with');

            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message === '') return;

            // Send via fetch POST
            fetch(CHAT_SEND_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    to_user_id: currentChatUser,
                    message: message
                })
            }).then(res => res.json())
                .then(data => {
                    if (data.status !== 'sent') console.error('Message not sent', data);
                }).catch(err => console.error(err));

            // Show message immediately
            const messagesDiv = document.getElementById('messages');
            const msgDiv = document.createElement('div');
            msgDiv.classList.add('message', 'from-me');
            msgDiv.innerText = message;
            messagesDiv.appendChild(msgDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            messageInput.value = '';
        });
    </script>

</body>

</html>