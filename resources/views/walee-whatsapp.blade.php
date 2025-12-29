<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Walee WhatsApp - Chat Mejorado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #e5ddd5;
            height: 100vh;
            overflow: hidden;
        }
        
        .whatsapp-container {
            display: flex;
            height: 100vh;
            max-width: 1600px;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Sidebar de conversaciones */
        .conversations-sidebar {
            width: 400px;
            background: #fff;
            border-right: 1px solid #e9edef;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .sidebar-header {
            background: #f0f2f5;
            padding: 20px;
            border-bottom: 1px solid #e9edef;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }
        
        .search-box {
            flex: 1;
            background: #fff;
            border-radius: 25px;
            padding: 10px 20px;
            border: none;
            outline: none;
            font-size: 14px;
        }
        
        .conversations-list {
            flex: 1;
            overflow-y: auto;
            background: #fff;
        }
        
        .conversation-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f2f5;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .conversation-item:hover {
            background: #f5f6f6;
        }
        
        .conversation-item.active {
            background: #f0f2f5;
        }
        
        .conversation-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .conversation-info {
            flex: 1;
            min-width: 0;
        }
        
        .conversation-name {
            font-weight: 600;
            font-size: 16px;
            color: #111b21;
            margin-bottom: 5px;
        }
        
        .conversation-preview {
            font-size: 14px;
            color: #667781;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .conversation-time {
            font-size: 12px;
            color: #667781;
            white-space: nowrap;
        }
        
        /* Área de chat principal */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #efeae2;
            background-image: 
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,.03) 2px, rgba(0,0,0,.03) 4px);
        }
        
        .chat-header {
            background: #f0f2f5;
            padding: 15px 20px;
            border-bottom: 1px solid #e9edef;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .chat-header-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        
        .chat-header-info {
            flex: 1;
        }
        
        .chat-header-name {
            font-weight: 600;
            font-size: 16px;
            color: #111b21;
        }
        
        .chat-header-status {
            font-size: 13px;
            color: #667781;
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .message {
            display: flex;
            gap: 10px;
            max-width: 65%;
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }
        
        .message.received {
            align-self: flex-start;
        }
        
        .message-bubble {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
            position: relative;
        }
        
        .message.sent .message-bubble {
            background: #d9fdd3;
            border-bottom-right-radius: 2px;
        }
        
        .message.received .message-bubble {
            background: #fff;
            border-bottom-left-radius: 2px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .message-time {
            font-size: 11px;
            color: #667781;
            margin-top: 5px;
            align-self: flex-end;
        }
        
        .message.sent .message-time {
            text-align: right;
        }
        
        .chat-input-area {
            background: #f0f2f5;
            padding: 10px 20px;
            border-top: 1px solid #e9edef;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .input-wrapper {
            flex: 1;
            background: #fff;
            border-radius: 25px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chat-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 15px;
            resize: none;
            max-height: 100px;
            overflow-y: auto;
        }
        
        .input-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            color: #54656f;
            transition: color 0.2s;
        }
        
        .input-icon:hover {
            color: #25d366;
        }
        
        .send-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #25d366;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        
        .send-button:hover {
            background: #20ba5a;
        }
        
        .send-button:disabled {
            background: #a0a0a0;
            cursor: not-allowed;
        }
        
        /* Scrollbar personalizado */
        .conversations-list::-webkit-scrollbar,
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .conversations-list::-webkit-scrollbar-track,
        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .conversations-list::-webkit-scrollbar-thumb,
        .chat-messages::-webkit-scrollbar-thumb {
            background: #c4c4c4;
            border-radius: 3px;
        }
        
        .conversations-list::-webkit-scrollbar-thumb:hover,
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0;
        }
        
        /* Estado vacío */
        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #667781;
            text-align: center;
            padding: 40px;
        }
        
        .empty-state-icon {
            font-size: 120px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .empty-state-text {
            font-size: 18px;
            font-weight: 300;
        }
    </style>
</head>
<body>
    <div class="whatsapp-container">
        <!-- Sidebar de conversaciones -->
        <div class="conversations-sidebar">
            <div class="sidebar-header">
                <div class="user-avatar">W</div>
                <input type="text" class="search-box" placeholder="Buscar o empezar un chat nuevo">
            </div>
            
            <div class="conversations-list">
                <!-- Conversación 1 -->
                <div class="conversation-item active">
                    <div class="conversation-avatar">JD</div>
                    <div class="conversation-info">
                        <div class="conversation-name">John Doe</div>
                        <div class="conversation-preview">Hola, ¿cómo estás?</div>
                    </div>
                    <div class="conversation-time">10:30</div>
                </div>
                
                <!-- Conversación 2 -->
                <div class="conversation-item">
                    <div class="conversation-avatar">JS</div>
                    <div class="conversation-info">
                        <div class="conversation-name">Jane Smith</div>
                        <div class="conversation-preview">Gracias por tu ayuda</div>
                    </div>
                    <div class="conversation-time">09:15</div>
                </div>
                
                <!-- Conversación 3 -->
                <div class="conversation-item">
                    <div class="conversation-avatar">MB</div>
                    <div class="conversation-info">
                        <div class="conversation-name">Mike Brown</div>
                        <div class="conversation-preview">Perfecto, nos vemos mañana</div>
                    </div>
                    <div class="conversation-time">Ayer</div>
                </div>
                
                <!-- Más conversaciones de ejemplo -->
                <div class="conversation-item">
                    <div class="conversation-avatar">AL</div>
                    <div class="conversation-info">
                        <div class="conversation-name">Alice Lee</div>
                        <div class="conversation-preview">¿Podrías ayudarme con esto?</div>
                    </div>
                    <div class="conversation-time">Ayer</div>
                </div>
                
                <div class="conversation-item">
                    <div class="conversation-avatar">RW</div>
                    <div class="conversation-info">
                        <div class="conversation-name">Robert Wilson</div>
                        <div class="conversation-preview">Excelente trabajo</div>
                    </div>
                    <div class="conversation-time">Lun</div>
                </div>
            </div>
        </div>
        
        <!-- Área de chat principal -->
        <div class="chat-area">
            <div class="chat-header">
                <div class="chat-header-avatar">JD</div>
                <div class="chat-header-info">
                    <div class="chat-header-name">John Doe</div>
                    <div class="chat-header-status">en línea</div>
                </div>
            </div>
            
            <div class="chat-messages">
                <!-- Mensaje recibido -->
                <div class="message received">
                    <div class="message-bubble">
                        Hola, ¿cómo estás?
                    </div>
                    <div class="message-time">10:25</div>
                </div>
                
                <!-- Mensaje enviado -->
                <div class="message sent">
                    <div class="message-bubble">
                        ¡Hola! Muy bien, gracias. ¿Y tú?
                    </div>
                    <div class="message-time">10:26</div>
                </div>
                
                <!-- Mensaje recibido -->
                <div class="message received">
                    <div class="message-bubble">
                        Todo bien por aquí. ¿Podrías ayudarme con una consulta?
                    </div>
                    <div class="message-time">10:27</div>
                </div>
                
                <!-- Mensaje enviado -->
                <div class="message sent">
                    <div class="message-bubble">
                        Por supuesto, estaré encantado de ayudarte. ¿De qué se trata?
                    </div>
                    <div class="message-time">10:28</div>
                </div>
                
                <!-- Mensaje recibido -->
                <div class="message received">
                    <div class="message-bubble">
                        Necesito información sobre tus servicios. ¿Podrías darme más detalles?
                    </div>
                    <div class="message-time">10:30</div>
                </div>
            </div>
            
            <div class="chat-input-area">
                <div class="input-wrapper">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    <textarea class="chat-input" placeholder="Escribe un mensaje" rows="1"></textarea>
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <button class="send-button">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-resize textarea
        const chatInput = document.querySelector('.chat-input');
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Scroll to bottom of messages
        const chatMessages = document.querySelector('.chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Send message on Enter (Shift+Enter for new line)
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Send button click
        document.querySelector('.send-button').addEventListener('click', sendMessage);
        
        function sendMessage() {
            const message = chatInput.value.trim();
            if (message) {
                // Create message element
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message sent';
                messageDiv.innerHTML = `
                    <div class="message-bubble">${message}</div>
                    <div class="message-time">${new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</div>
                `;
                
                chatMessages.appendChild(messageDiv);
                chatInput.value = '';
                chatInput.style.height = 'auto';
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }
        
        // Conversation item click
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>

