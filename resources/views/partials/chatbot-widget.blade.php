<!-- AI Chatbot Widget -->
<div id="ai-chatbot-widget" class="ai-chatbot-hidden">
    <!-- Chat Icon -->
    <div id="ai-chatbot-toggle" class="shadow-lg">
        <i class="fas fa-robot"></i>
    </div>

    <!-- Chat Container -->
    <div id="ai-chatbot-container" class="shadow-xl">
        <div class="ai-chatbot-header">
            <div class="ai-chatbot-title">
                <i class="fas fa-robot mr-2"></i> AI Assistant
            </div>
            <button id="ai-chatbot-close"><i class="fas fa-times"></i></button>
        </div>
        <div class="ai-chatbot-body" id="ai-chatbot-messages">
            <div class="ai-chat-msg bot-msg">
                <div class="msg-avatar"><i class="fas fa-robot"></i></div>
                <div class="msg-content">Hello! I'm your AI assistant connected to your database. You can ask me about Tasks, Projects, and Users!</div>
            </div>
        </div>
        <div class="ai-chatbot-footer">
            <form id="ai-chatbot-form">
                @csrf
                <div class="input-group">
                    <input type="text" id="ai-chatbot-input" class="form-control" placeholder="Ask something..." autocomplete="off">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="ai-chatbot-send"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Chatbot Widget Styles - Premium Look */
#ai-chatbot-widget {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9999;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}

#ai-chatbot-toggle {
    width: 65px;
    height: 65px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 28px;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(118, 75, 162, 0.4);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

#ai-chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 35px rgba(118, 75, 162, 0.6);
}

#ai-chatbot-container {
    position: absolute;
    bottom: 85px;
    right: 0;
    width: 380px;
    height: 550px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
    transform-origin: bottom right;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.ai-chatbot-open #ai-chatbot-container {
    display: flex;
    animation: slideIn 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

@keyframes slideIn {
    from { opacity: 0; transform: scale(0.9) translateY(20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.ai-chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.ai-chatbot-title {
    font-weight: 600;
    font-size: 18px;
    display: flex;
    align-items: center;
}

#ai-chatbot-close {
    background: transparent;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
}

#ai-chatbot-close:hover {
    opacity: 1;
}

.ai-chatbot-body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Custom Scrollbar */
.ai-chatbot-body::-webkit-scrollbar {
    width: 6px;
}
.ai-chatbot-body::-webkit-scrollbar-track {
    background: transparent;
}
.ai-chatbot-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.ai-chat-msg {
    display: flex;
    max-width: 85%;
    gap: 10px;
}

.ai-chat-msg.bot-msg {
    align-self: flex-start;
}

.ai-chat-msg.user-msg {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.msg-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    font-size: 14px;
}

.bot-msg .msg-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.user-msg .msg-avatar {
    background: #e2e8f0;
    color: #475569;
}

.msg-content {
    background: white;
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.5;
    color: #334155;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
}

.bot-msg .msg-content {
    border-top-left-radius: 4px; border: 1px solid rgba(0,0,0,0.05);
}

.user-msg .msg-content {
    background: #667eea;
    color: white;
    border-top-right-radius: 4px;
}

.ai-chatbot-footer {
    padding: 15px 20px;
    background: white;
    border-top: 1px solid #f1f5f9;
}

#ai-chatbot-input {
    border-radius: 20px 0 0 20px;
    border: 1px solid #e2e8f0;
    padding-left: 15px;
}

#ai-chatbot-input:focus {
    box-shadow: none;
    border-color: #667eea;
}

#ai-chatbot-send {
    border-radius: 0 20px 20px 0;
    background: #667eea;
    border: none;
    padding-left: 15px;
    padding-right: 15px;
}

#ai-chatbot-send:hover {
    background: #764ba2;
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 15px 16px;
    background: white;
    border-radius: 18px;
    border-top-left-radius: 4px;
    width: max-content;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    border: 1px solid rgba(0,0,0,0.05);
}

.typing-dot {
    width: 6px;
    height: 6px;
    background: #94a3b8;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out both;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

/* Markdown specific styles */
.msg-content p { margin-bottom: 8px; }
.msg-content p:last-child { margin-bottom: 0; }
.msg-content strong { font-weight: 600; color: #1e293b; }
.user-msg .msg-content strong { color: white; }
.msg-content ul, .msg-content ol { margin-left: 15px; margin-bottom: 8px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('ai-chatbot-widget');
    const toggle = document.getElementById('ai-chatbot-toggle');
    const close = document.getElementById('ai-chatbot-close');
    const form = document.getElementById('ai-chatbot-form');
    const input = document.getElementById('ai-chatbot-input');
    const messagesBody = document.getElementById('ai-chatbot-messages');

    // Toggle Chat
    toggle.addEventListener('click', () => {
        widget.classList.toggle('ai-chatbot-open');
        if (widget.classList.contains('ai-chatbot-open')) {
            input.focus();
        }
    });

    close.addEventListener('click', () => {
        widget.classList.remove('ai-chatbot-open');
    });

    function scrollToBottom() {
        messagesBody.scrollTop = messagesBody.scrollHeight;
    }

    function appendMessage(text, isUser = false) {
        // Very basic simple markdown bold formatting **text**
        let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Basic list items formatting * item
        formattedText = formattedText.replace(/^\*\s(.*)$/gm, '<ul><li>$1</li></ul>');
        formattedText = formattedText.replace(/<\/ul>\n<ul>/g, ''); // Fix merged lists
        // Replace newlines with <br>
        formattedText = formattedText.replace(/\n/g, '<br>');

        const msgDiv = document.createElement('div');
        msgDiv.className = `ai-chat-msg ${isUser ? 'user-msg' : 'bot-msg'}`;
        msgDiv.innerHTML = `
            <div class="msg-avatar"><i class="fas ${isUser ? 'fa-user' : 'fa-robot'}"></i></div>
            <div class="msg-content">${formattedText}</div>
        `;
        messagesBody.appendChild(msgDiv);
        scrollToBottom();
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'ai-chat-msg bot-msg id-typing';
        typingDiv.innerHTML = `
            <div class="msg-avatar"><i class="fas fa-robot"></i></div>
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        messagesBody.appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const typingItem = document.querySelector('.id-typing');
        if (typingItem) {
            typingItem.remove();
        }
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        // Add user message
        appendMessage(message, true);
        input.value = '';
        input.disabled = true;

        // Show typing indicator
        showTypingIndicator();

        try {
            const token = document.querySelector('input[name="_token"]').value;
            const response = await fetch('/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            removeTypingIndicator();
            input.disabled = false;
            input.focus();

            if (data.reply) {
                appendMessage(data.reply, false);
            } else {
                appendMessage("Oops, I didn't get a proper response.", false);
            }

        } catch (error) {
            removeTypingIndicator();
            input.disabled = false;
            console.error('Chat error:', error);
            appendMessage("Sorry, I encountered a connection error. Please try again.", false);
        }
    });
});
</script>
