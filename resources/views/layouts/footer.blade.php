<footer class="main-footer">
  <strong>Copyright &copy; 2024-2026 <a href="https://www.instagram.com/carfreaker1/?hl=en">carfreaker1</a></strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 3.2.0
  </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark"> 
  <!-- Control sidebar content goes here -->
  <div class="card-footer">
  @if(Auth::check())
    <p>{{Auth::user()->name}}</p>
    <p>Profile: {{Auth::user()->role->name}}</p>
    <a href="{{route('logout')}}"><button type="submit" class="btn btn-primary mb-3">Logout</button></a>
  @else
    <p>Guest</p>
  @endif    
  </div>
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@stack('scripts')
@auth
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

var currentUserId = {{ auth() -> id() }};
var currentChatId = null;

// FIX 1: subscribe to private channel
var channel = pusher.subscribe('private-taskmanager.' + currentUserId);

function playNotificationSound() {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioCtx.createOscillator();
        const gainNode = audioCtx.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioCtx.destination);

        // Create a pleasant "ding" sound
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
        oscillator.frequency.exponentialRampToValueAtTime(1760, audioCtx.currentTime + 0.1); // A6

        // Fade out the sound quickly
        gainNode.gain.setValueAtTime(0.5, audioCtx.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.2);

        oscillator.start();
        oscillator.stop(audioCtx.currentTime + 0.2);
    } catch (e) {
        console.error("Audio API error:", e);
    }
}

function showNotificationPopup(sender, message) {
    $(document).Toasts('create', {
        title: 'New Message from ' + sender,
        body: message,
        autohide: true,
        delay: 5000,
        class: 'bg-info'
    });
}

// FIX 2: use correct event name
channel.bind('MessageSent', function (data) {
    // Always play sound if message is not from ourselves
    if (data.message.from_id != currentUserId) {
        playNotificationSound();

        if (data.message.from_id == currentChatId) {
            // Append message to chat box if we are actively chatting with the sender
            appendMessage(data.message.from_id, data.message.sender.name, data.message.message, data.message.created_at);
        } else {
            // Otherwise, show a toast notification popup
            showNotificationPopup(data.message.sender.name, data.message.message);
        }
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
                //appendMessage(m.sender.name, m.message);
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

document.getElementById('chat-form').addEventListener('submit', function (e) {
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
(async function () {
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
@endauth
<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  setTimeout(()=>{$('.toast').fadeOut(500,function(){$(this).remove();});},4000);
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Ion Slider -->
<script src="{{ asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('assets/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>

<!-- jQuery Knob Chart -->
<script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<!-- BS-Stepper -->
<script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js')}}"></script>
<!-- dropzonejs -->
<script src="{{ asset('assets/plugins/dropzone/min/dropzone.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<!-- overlayScrollbars -->
<script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('assets/dist/js/demo.js') }}"></script> --}}
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('assets/dist/js/pages/dashboard.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/specificpage.js') }}"></script>
<script src="{{ asset('js/taskassign.js') }}"></script>
<script src="{{ asset('js/tasktimeline.js') }}"></script>
<script src="{{ asset('js/custom1.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script>
  $(function () {
    /* BOOTSTRAP SLIDER */
    $('.slider').bootstrapSlider()

    /* ION SLIDER */
    $('#range_5').ionRangeSlider({
      min     : 0,
      max     : 100,
      type    : 'single',
      step    : 1,
      postfix : ' %',
      prettify: false,
      hasGrid : true
    })
  })
</script>
</body>
</html>