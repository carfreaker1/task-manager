@extends('layouts.masterlayout')

@section('content')
    <style>
        /* ===== General Layout ===== */
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .chat-container {
            padding: 20px;
        }

        .chat-messages {
            scroll-behavior: smooth;
        }

        .chat-card {
            display: flex;
            height: 80vh;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        /* ===== Users Panel ===== */
        .users-panel {
            width: 30%;
            border-right: 1px solid #eee;
            display: flex;
            flex-direction: column;
            background: #fafafa;
        }

        .panel-header {
            padding: 15px;
            background: #4e73df;
            color: white;
            font-weight: 600;
        }

        .user-list {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-y: auto;
        }

        .user-list li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
        }

        .user-list li a:hover {
            background: #e9ecef;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: #4e73df;
            color: white;
            font-weight: bold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .user-name {
            font-weight: 500;
        }

        /* ===== Chat Panel ===== */
        .chat-panel {
            width: 70%;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: #ffffff;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fc;

            display: flex;
            flex-direction: column;
        }

        /* Chat bubbles */
        .message {
            max-width: 60%;
            padding: 10px 15px;
            border-radius: 18px;
            margin-bottom: 10px;
            font-size: 14px;
            word-wrap: break-word;
            display: inline-block;
        }

        .message.sent {
            background: #4e73df;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .message.received {
            background: #e4e6eb;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }

        /* ===== Chat Form ===== */
        .chat-form {
            padding: 10px;
            border-top: 1px solid #eee;
            background: white;
        }

        .input-group {
            display: flex;
        }

        .input-group input {
            flex: 1;
            padding: 10px;
            border-radius: 20px 0 0 20px;
            border: 1px solid #ddd;
            outline: none;
        }

        .input-group button {
            padding: 10px 20px;
            border: none;
            background: #4e73df;
            color: white;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
            transition: 0.3s;
        }

        .input-group button:hover {
            background: #2e59d9;
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 768px) {

            .chat-card {
                flex-direction: column;
                height: 95vh;
            }

            .users-panel {
                width: 100%;
                height: 35%;
            }

            .chat-panel {
                width: 70%;
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            .user-list li a {
                padding: 10px;
            }

            .message {
                max-width: 85%;
                font-size: 13px;
            }
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Messaging Area</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Messages</a></li>
                            {{-- <li class="breadcrumb-item active">Fields</li> --}}
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Team List</h3>
                            </div>
                            @if (session('success'))
                                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('success') }}</p> --}}
                                <script>
                                    $(document).ready(function() {
                                        $(document).Toasts('create', {
                                            title: 'Subject',
                                            body: 'Subject Created Succesfully'
                                        })
                                        $(".toast").addClass('bg-success')
                                    });
                                </script>
                            @elseif (session('update'))
                                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('update') }}</p> --}}
                                <script>
                                    $(document).ready(function() {
                                        $(document).Toasts('create', {
                                            title: 'Subject',
                                            body: 'Subject Updated Succesfully'
                                        })
                                        $(".toast").addClass('bg-success')
                                    });
                                </script>
                            @elseif (session('delete'))
                                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('delete') }}</p> --}}
                                <script>
                                    $(document).ready(function() {
                                        $(document).Toasts('create', {
                                            title: 'Subject',
                                            body: 'Subject Deleted Succesfully'
                                        })
                                        $(".toast").addClass('bg-danger')
                                    });
                                </script>
                            @elseif (session('successdesig'))
                                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('successdesig') }}</p> --}}
                                <script>
                                    $(document).ready(function() {
                                        $(document).Toasts('create', {
                                            title: 'Designation',
                                            body: 'Designation Created Succesfully'
                                        })
                                        $(".toast").addClass('bg-success')
                                    });
                                </script>
                            @elseif (session('update'))
                                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('updatedesig') }}</p> --}}
                                <script>
                                    $(document).ready(function() {
                                        $(document).Toasts('create', {
                                            title: 'Designation',
                                            body: 'Designation Created Succesfully'
                                        })
                                        $(".toast").addClass('bg-success')
                                    });
                                </script>
                            @elseif (session('deletedesig'))
                                {{-- <p class="alert alert-success text-center alert-dismissible">{{ session('deletedesig') }}</p> --}}
                                <script>
                                    $(document).ready(function() {
                                        $(document).Toasts('create', {
                                            title: 'Designation',
                                            body: 'Designation Deleted Succesfully'
                                        })
                                        $(".toast").addClass('bg-danger')
                                    });
                                </script>
                            @endif
                            <div class="chat-container">
                                <div class="chat-card">

                                    <!-- Users List -->
                                    <div class="users-panel">
                                        <div class="panel-header">
                                            <h4>Users</h4>
                                        </div>
                                        <ul id="user-list" class="user-list">
                                            @foreach ($users as $user)
                                                <li>
                                                    <a href="#"
                                                        onclick="openChat({{ $user->id }}, '{{ $user->name }}')">
                                                        <div class="user-avatar">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                        <span class="user-name">{{ $user->name }}</span><span
                                                            class="badge badge-secondary user-status" style="margin-left: 4px;"     id="status-{{ $user->id }}">Offline</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <!-- Chat Box -->
                                    <div class="chat-panel">
                                        <div class="panel-header chat-header">
                                            <h4 id="chat-with">Select a user to chat</h4>
                                        </div>

                                        <div id="messages" class="chat-messages"></div>

                                        <form id="chat-form" class="chat-form">
                                            @csrf
                                            <input type="hidden" id="to_id">

                                            <div class="input-group">
                                                <input type="text" id="message" placeholder="Type a message..."
                                                    autocomplete="off">
                                                <button type="submit">Send</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}

        <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
        <script>
            function scrollToBottom() {
                let messages = document.getElementById("messages");
                messages.scrollTop = messages.scrollHeight;
            }
            Pusher.logToConsole = false;

            var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                authEndpoint: "/broadcasting/auth",
                auth: {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('[name=_token]').value
                    }
                }
            });

            var currentUserId = {{ auth()->id() }};
            var currentChatId = null;

            // FIX 1: subscribe to private channel
            var channel = pusher.subscribe('private-taskmanager.' + currentUserId);

            // FIX 2: use correct event name
            channel.bind('MessageSent', function(data) {
                if (data.message.from_id === currentChatId) {
                    //   appendMessage(data.message.sender.name, data.message.message);
                    appendMessage(data.message.sender.name, data.message.message, data.message.created_at);
                }
            });

            function openChat(userId, userName) {
                currentChatId = userId;
                document.getElementById('chat-with').innerText = "Chat with " + userName;
                document.getElementById('to_id').value = userId;
                document.getElementById('chat-form').style.display = 'block';

                fetch(`/chat/${userId}/messages`)
                    .then(res => res.json())
                    .then(messages => {
                        let msgBox = document.getElementById('messages');
                        msgBox.innerHTML = "";
                        messages.forEach(m => {
                            //   appendMessage(m.sender.name, m.message);
                            appendMessage(
                                m.from_id,
                                m.sender.name,
                                m.message,
                                m.created_at
                            );
                        });
                        scrollToBottom();
                        fetchUserStatuses();
                    });
            }

            //   function appendMessage(user, message) {
            //       let msg = `<p><strong>${user}:</strong> ${message}</p>`;
            //       document.getElementById('messages').innerHTML += msg;
            //   }
            // function appendMessage(fromId, user, message) {

            //     let messageClass = (fromId == currentUserId) ? 'sent' : 'received';

            //     let msg = `
            //         <span class="message ${messageClass}">
            //             ${message}
            //         </span>
            //     `;

            //     document.getElementById('messages').innerHTML += msg;
            //     scrollToBottom();
            // }
            function appendMessage(fromId, user, message, time = null) {

            let messageClass = (fromId == currentUserId) ? 'sent' : 'received';

            // Format time if available
            let formattedTime = '';
            if (time) {
                let date = new Date(time);
                formattedTime = date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            let msg = `
                <div style="display:flex; flex-direction:column; 
                            align-items:${messageClass === 'sent' ? 'flex-end' : 'flex-start'};">
                    <span class="message ${messageClass}">
                        ${message}
                    </span>
                    <small class="message-time">${formattedTime}</small>
                </div>
            `;

            document.getElementById('messages').innerHTML += msg;
            scrollToBottom();
            }

            document.getElementById('chat-form').addEventListener('submit', function(e) {
                e.preventDefault();
                let toId = document.getElementById('to_id').value;
                let message = document.getElementById('message').value;

                fetch('send-message', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        to_id: toId,
                        message: message
                    })
                }).then(res => res.json()).then(data => {
                    //   appendMessage(data.sender.name, data.message);
                    appendMessage(
                        data.from_id,
                        data.sender.name,
                        data.message,
                        data.created_at
                    );
                    document.getElementById('message').value = '';
                });
            });


            async function isReallyOnline() {
                if (!navigator.onLine) return false;

                try {
                    await fetch('https://www.google.com/generate_204', {
                        cache: 'no-store',
                        mode: 'no-cors'
                    });
                    return true;
                } catch {
                    return false;
                }
            }

            let onlineTimer = null;

            // Update status function
            function updateOnlineStatus(status) {
                fetch('/user/status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status
                    })
                });
            }

            // When user comes online → wait 5 sec → set status = 1
            window.addEventListener('online', () => {
                clearTimeout(onlineTimer);
                onlineTimer = setTimeout(() => updateOnlineStatus(1), 5000);
            });

            // When user goes offline / closes tab → status = 0
            window.addEventListener('offline', () => updateOnlineStatus(0));
            window.addEventListener('beforeunload', () => updateOnlineStatus(0));

            // Initial load check
            (async function() {
                const isOnline = await isReallyOnline();
                console.log('Initial online status:', isOnline);

                if (isOnline) {
                    setTimeout(() => updateOnlineStatus(1), 1000);
                }
            })();

            function fetchUserStatuses() {
                fetch('/users/status')
                    .then(res => res.json())
                    .then(users => {
                        users.forEach(user => {
                            let badge = document.getElementById('status-' + user.id);
                            if (!badge) return;

                            if (user.status == 1) {
                                badge.innerText = 'Online';
                                badge.className = 'badge badge-success user-status';
                            } else {
                                badge.innerText = 'Offline';
                                badge.className = 'badge badge-secondary user-status';
                            }

                            // If current chat user
                            if (currentChatId == user.id) {
                                document.getElementById('chat-status').innerText =
                                    user.status == 1 ? 'Online' : 'Offline';
                            }
                        });
                    });
            }

            // fetch every 1 minute
            setInterval(fetchUserStatuses, 60000);
            fetchUserStatuses();
        </script>
    @endsection
