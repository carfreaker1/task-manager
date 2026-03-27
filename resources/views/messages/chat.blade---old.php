@extends('layouts.masterlayout')

@section('content')
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
                  <h3 class="card-title">Subjects List</h3>
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
                <div class="row">
                    <!-- Users List -->
                    <div class="col-md-4">
                        <h4>Users</h4>
                        <ul id="user-list">
                            @foreach($users as $user)
                                <li><a href="#" onclick="openChat({{ $user->id }}, '{{ $user->name }}')">{{ $user->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
            
                    <!-- Chat Box -->
                    <div class="col-md-8">
                        <h4 id="chat-with">Select a user to chat</h4>
                        <div id="messages" style="height:300px; overflow-y:scroll; border:1px solid #ccc; padding:10px;"></div>
            
                        <form id="chat-form" style="display:none; margin-top:10px;">
                            @csrf
                            <input type="hidden" id="to_id">
                            <input type="text" id="message" placeholder="Type message" class="form-control">
                            <button type="submit" class="btn btn-primary mt-2">Send</button>
                        </form>
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
          Pusher.logToConsole = false;
      
          var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
              cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
              authEndpoint: "/broadcasting/auth",
              auth: { headers: { "X-CSRF-TOKEN": document.querySelector('[name=_token]').value } }
          });
      
          var currentUserId = {{ auth()->id() }};
          var currentChatId = null;
      
          // FIX 1: subscribe to private channel
          var channel = pusher.subscribe('private-taskmanager.' + currentUserId);
      
          // FIX 2: use correct event name
          channel.bind('MessageSent', function(data) {
              if (data.message.from_id === currentChatId) {
                  appendMessage(data.message.sender.name, data.message.message);
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
                          appendMessage(m.sender.name, m.message);
                      });
                  });
          }
      
          function appendMessage(user, message) {
              let msg = `<p><strong>${user}:</strong> ${message}</p>`;
              document.getElementById('messages').innerHTML += msg;
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
                  body: JSON.stringify({ to_id: toId, message: message })
              }).then(res => res.json()).then(data => {
                  appendMessage(data.sender.name, data.message);
                  document.getElementById('message').value = '';
              });
          });
      </script>
      
@endsection