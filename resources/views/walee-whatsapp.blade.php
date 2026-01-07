<!DOCTYPE html>
<html lang="es" class="h-full" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WhatsApp - Walee</title>
    @include('partials.walee-dark-mode-init')
    @include('partials.walee-violet-light-mode')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        walee: {
                            400: '#D59F3B',
                            500: '#C78F2E',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif; }
        
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-height: 0;
        }
        
        .whatsapp-container {
            display: flex;
            flex: 1;
            width: 100%;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
            min-height: 0;
            position: relative;
        }
        
        @media (max-width: 768px) {
            .whatsapp-container {
                border-radius: 0;
            }
        }
        
        .dark .whatsapp-container {
            background: #111b21;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
        
        /* Sidebar de conversaciones */
        .conversations-sidebar {
            width: 400px;
            background: #fff;
            border-right: 1px solid #e9edef;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .conversations-sidebar {
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 100%;
                z-index: 10;
                transform: translateX(0);
            }
            
            .conversations-sidebar.hidden-mobile {
                transform: translateX(-100%);
            }
        }
        
        .dark .conversations-sidebar {
            background: #111b21;
            border-right-color: #2a3942;
        }
        
        .sidebar-header {
            background: #f0f2f5;
            padding: 20px;
            border-bottom: 1px solid #e9edef;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .sidebar-header {
                padding: 15px;
            }
        }
        
        .dark .sidebar-header {
            background: #202c33;
            border-bottom-color: #2a3942;
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
            color: #111b21;
        }
        
        .dark .search-box {
            background: #2a3942;
            color: #e9edef;
        }
        
        .conversations-list {
            flex: 1;
            overflow-y: auto;
            background: #fff;
        }
        
        .dark .conversations-list {
            background: #111b21;
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
        
        @media (max-width: 768px) {
            .conversation-item {
                padding: 12px 15px;
            }
        }
        
        .dark .conversation-item {
            border-bottom-color: #2a3942;
        }
        
        .conversation-item:hover {
            background: #f5f6f6;
        }
        
        .dark .conversation-item:hover {
            background: #202c33;
        }
        
        .conversation-item.active {
            background: #f0f2f5;
        }
        
        .dark .conversation-item.active {
            background: #202c33;
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
        
        .dark .conversation-name {
            color: #e9edef;
        }
        
        .conversation-preview {
            font-size: 14px;
            color: #667781;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .dark .conversation-preview {
            color: #8696a0;
        }
        
        .conversation-time {
            font-size: 12px;
            color: #667781;
            white-space: nowrap;
        }
        
        .dark .conversation-time {
            color: #8696a0;
        }
        
        /* Área de chat principal */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #efeae2;
            background-image: 
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,.03) 2px, rgba(0,0,0,.03) 4px);
            position: relative;
        }
        
        @media (max-width: 768px) {
            .chat-area {
                width: 100%;
            }
            
            .chat-area.hidden-mobile {
                display: none;
            }
        }
        
        .dark .chat-area {
            background: #0b141a;
            background-image: 
                repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255,255,255,.02) 2px, rgba(255,255,255,.02) 4px);
        }
        
        .chat-header {
            background: #f0f2f5;
            padding: 15px 20px;
            border-bottom: 1px solid #e9edef;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .chat-header {
                padding: 12px 15px;
            }
        }
        
        .back-button {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: transparent;
            border: none;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            color: #54656f;
            transition: background 0.2s;
        }
        
        @media (max-width: 768px) {
            .back-button {
                display: flex;
            }
        }
        
        .back-button:hover {
            background: rgba(0,0,0,0.1);
        }
        
        .dark .back-button {
            color: #8696a0;
        }
        
        .dark .back-button:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .dark .chat-header {
            background: #202c33;
            border-bottom-color: #2a3942;
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
        
        .dark .chat-header-name {
            color: #e9edef;
        }
        
        .chat-header-status {
            font-size: 13px;
            color: #667781;
        }
        
        .dark .chat-header-status {
            color: #8696a0;
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
        
        .dark .message.sent .message-bubble {
            background: #005c4b;
        }
        
        .message.received .message-bubble {
            background: #fff;
            border-bottom-left-radius: 2px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .dark .message.received .message-bubble {
            background: #202c33;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .message-time {
            font-size: 11px;
            color: #667781;
            margin-top: 5px;
            align-self: flex-end;
        }
        
        .dark .message-time {
            color: #8696a0;
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
        
        @media (max-width: 768px) {
            .chat-input-area {
                padding: 8px 15px;
            }
        }
        
        .dark .chat-input-area {
            background: #202c33;
            border-top-color: #2a3942;
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
        
        .dark .input-wrapper {
            background: #2a3942;
        }
        
        .chat-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 15px;
            resize: none;
            max-height: 100px;
            overflow-y: auto;
            background: transparent;
            color: #111b21;
        }
        
        .dark .chat-input {
            color: #e9edef;
        }
        
        .chat-input::placeholder {
            color: #667781;
        }
        
        .dark .chat-input::placeholder {
            color: #8696a0;
        }
        
        .input-icon {
            width: 24px;
            height: 24px;
            cursor: pointer;
            color: #54656f;
            transition: color 0.2s;
        }
        
        .dark .input-icon {
            color: #8696a0;
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
        
        .dark .conversations-list::-webkit-scrollbar-thumb,
        .dark .chat-messages::-webkit-scrollbar-thumb {
            background: #54656f;
        }
        
        .conversations-list::-webkit-scrollbar-thumb:hover,
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0;
        }
        
        .dark .conversations-list::-webkit-scrollbar-thumb:hover,
        .dark .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #667781;
        }
        
        /* Mensaje con estado */
        .message-status {
            font-size: 12px;
            margin-left: 5px;
            opacity: 0.7;
        }
        
        /* Divisor de fecha */
        .message-date-divider {
            text-align: center;
            margin: 20px 0;
            padding: 8px 0;
            color: #667781;
            font-size: 12px;
            position: relative;
        }
        
        .message-date-divider::before,
        .message-date-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #e9edef;
        }
        
        .message-date-divider::before {
            left: 0;
        }
        
        .message-date-divider::after {
            right: 0;
        }
        
        .dark .message-date-divider {
            color: #8696a0;
        }
        
        .dark .message-date-divider::before,
        .dark .message-date-divider::after {
            background: #2a3942;
        }
        
        /* Badge de no leídos */
        .conversation-item {
            position: relative;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-white transition-colors duration-200 h-screen overflow-hidden flex flex-col">
    <div class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 -left-20 w-60 h-60 bg-walee-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative max-w-[90rem] mx-auto px-4 py-6 sm:px-6 lg:px-8 flex flex-col flex-1 min-h-0">
            @php $pageTitle = 'WhatsApp'; @endphp
            @include('partials.walee-navbar')
            
            <div class="main-content">
                <div class="whatsapp-container">
                <!-- Sidebar de conversaciones -->
                <div class="conversations-sidebar">
                    <div class="sidebar-header">
                        <div class="user-avatar">W</div>
                        <input type="text" class="search-box" id="search-box" placeholder="Buscar o empezar un chat nuevo">
                    </div>
                    
                    <div class="conversations-list" id="conversations-list">
                        <!-- Las conversaciones se cargarán dinámicamente -->
                        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                            <div class="animate-spin inline-block w-6 h-6 border-2 border-current border-t-transparent rounded-full"></div>
                            <p class="mt-2">Cargando conversaciones...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Área de chat principal -->
                <div class="chat-area hidden-mobile" id="chat-area">
                    <div class="chat-header">
                        <button class="back-button" onclick="showConversations()" aria-label="Volver a conversaciones">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div class="chat-header-avatar" id="chat-header-avatar">?</div>
                        <div class="chat-header-info">
                            <div class="chat-header-name" id="chat-header-name">Selecciona una conversación</div>
                            <div class="chat-header-status" id="chat-header-status"></div>
                        </div>
                    </div>
                    
                    <div class="chat-messages" id="chat-messages">
                        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                            <p>Selecciona una conversación para comenzar</p>
                        </div>
                    </div>
                    
                    <div class="chat-input-area" id="chat-input-area" style="display: none;">
                        <div class="input-wrapper">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <textarea class="chat-input" id="chat-input" placeholder="Escribe un mensaje" rows="1"></textarea>
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <button class="send-button" id="send-button">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <script>
        let currentConversationId = null;
        let conversations = [];
        let messages = [];
        let pollInterval = null;

        // CSRF Token para las peticiones
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Función para formatear fecha
        function formatTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            
            if (days === 0) {
                return date.toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'});
            } else if (days === 1) {
                return 'Ayer';
            } else if (days < 7) {
                return date.toLocaleDateString('es-ES', {weekday: 'short'});
            } else {
                return date.toLocaleDateString('es-ES', {day: '2-digit', month: '2-digit'});
            }
        }

        // Función para obtener iniciales
        function getInitials(name) {
            if (!name) return '?';
            const parts = name.trim().split(' ');
            if (parts.length >= 2) {
                return (parts[0][0] + parts[1][0]).toUpperCase();
            }
            return name.substring(0, 2).toUpperCase();
        }

        // Cargar conversaciones
        async function loadConversations(searchQuery = '') {
            const url = searchQuery 
                ? `/api/whatsapp/conversations/search?q=${encodeURIComponent(searchQuery)}`
                : '/api/whatsapp/conversations';
            
            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    conversations = data.conversations;
                    renderConversations();
                }
            } catch (error) {
                console.error('Error cargando conversaciones:', error);
                document.getElementById('conversations-list').innerHTML = 
                    '<div class="text-center py-8 text-red-500">Error al cargar conversaciones</div>';
            }
        }

        // Renderizar conversaciones
        function renderConversations() {
            const list = document.getElementById('conversations-list');
            
            if (conversations.length === 0) {
                list.innerHTML = '<div class="text-center py-8 text-slate-500 dark:text-slate-400">No hay conversaciones</div>';
                return;
            }
            
            list.innerHTML = conversations.map(conv => {
                const initials = getInitials(conv.contact_name);
                const time = formatTime(conv.last_message_at);
                const activeClass = currentConversationId === conv.id ? 'active' : '';
                const unreadBadge = conv.unread_count > 0 
                    ? `<span class="absolute top-2 right-2 bg-emerald-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">${conv.unread_count}</span>`
                    : '';
                
                return `
                    <div class="conversation-item ${activeClass}" data-conversation-id="${conv.id}" onclick="selectConversation(${conv.id})">
                        <div class="conversation-avatar">${initials}</div>
                        <div class="conversation-info">
                            <div class="conversation-name">${conv.contact_name || conv.phone_number}</div>
                            <div class="conversation-preview">${conv.last_message || 'Sin mensajes'}</div>
                        </div>
                        <div class="conversation-time">${time}</div>
                        ${unreadBadge}
                    </div>
                `;
            }).join('');
        }

        // Seleccionar conversación
        async function selectConversation(conversationId) {
            currentConversationId = conversationId;
            
            // Actualizar UI
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-conversation-id="${conversationId}"]`)?.classList.add('active');
            
            // Mostrar chat en móviles
            if (window.innerWidth <= 768) {
                showChat();
            }
            
            // Cargar mensajes
            await loadMessages(conversationId);
            
            // Mostrar input
            document.getElementById('chat-input-area').style.display = 'flex';
        }

        // Cargar mensajes
        async function loadMessages(conversationId) {
            try {
                const response = await fetch(`/api/whatsapp/conversations/${conversationId}/messages`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const conversation = data.conversation;
                    messages = data.messages;
                    
                    // Actualizar header
                    document.getElementById('chat-header-name').textContent = conversation.contact_name || conversation.phone_number;
                    document.getElementById('chat-header-avatar').textContent = getInitials(conversation.contact_name || conversation.phone_number);
                    document.getElementById('chat-header-status').textContent = '';
                    
                    // Renderizar mensajes
                    renderMessages();
                    
                    // Recargar conversaciones para actualizar contadores
                    loadConversations();
                }
            } catch (error) {
                console.error('Error cargando mensajes:', error);
            }
        }

        // Renderizar mensajes
        function renderMessages() {
            const container = document.getElementById('chat-messages');
            
            if (messages.length === 0) {
                container.innerHTML = '<div class="text-center py-8 text-slate-500 dark:text-slate-400">No hay mensajes</div>';
                return;
            }
            
            let lastDate = null;
            container.innerHTML = messages.map(msg => {
                const date = new Date(msg.created_at);
                const dateStr = date.toLocaleDateString('es-ES', {day: 'numeric', month: 'long', year: 'numeric'});
                const showDate = lastDate !== dateStr;
                lastDate = dateStr;
                
                const dateDiv = showDate 
                    ? `<div class="message-date-divider">${dateStr}</div>`
                    : '';
                
                const direction = msg.direction === 'incoming' ? 'received' : 'sent';
                const statusIcon = msg.direction === 'outgoing' 
                    ? (msg.status === 'read' ? '✓✓' : msg.status === 'delivered' ? '✓✓' : '✓')
                    : '';
                
                return `
                    ${dateDiv}
                    <div class="message ${direction}">
                        <div class="message-bubble">
                            ${msg.content || (msg.media_name || 'Media')}
                            ${statusIcon ? `<span class="message-status">${statusIcon}</span>` : ''}
                        </div>
                        <div class="message-time">${msg.time || formatTime(msg.created_at)}</div>
                    </div>
                `;
            }).join('');
            
            // Scroll al final
            container.scrollTop = container.scrollHeight;
        }

        // Enviar mensaje
        async function sendMessage() {
            if (!currentConversationId) return;
            
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            
            if (!message) return;
            
            try {
                const response = await fetch(`/api/whatsapp/conversations/${currentConversationId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ content: message })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    input.value = '';
                    input.style.height = 'auto';
                    
                    // Recargar mensajes
                    await loadMessages(currentConversationId);
                }
            } catch (error) {
                console.error('Error enviando mensaje:', error);
                alert('Error al enviar el mensaje');
            }
        }

        // Auto-resize textarea
        const chatInput = document.getElementById('chat-input');
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Send message on Enter (Shift+Enter for new line)
        chatInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Send button click
        document.getElementById('send-button').addEventListener('click', sendMessage);
        
        // Búsqueda
        const searchBox = document.getElementById('search-box');
        let searchTimeout;
        searchBox.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadConversations(this.value);
            }, 300);
        });
        
        // Funciones para navegación móvil
        function showChat() {
            const sidebar = document.querySelector('.conversations-sidebar');
            const chatArea = document.getElementById('chat-area');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.add('hidden-mobile');
                chatArea.classList.remove('hidden-mobile');
            }
        }
        
        function showConversations() {
            const sidebar = document.querySelector('.conversations-sidebar');
            const chatArea = document.getElementById('chat-area');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('hidden-mobile');
                chatArea.classList.add('hidden-mobile');
            }
        }
        
        // Manejar resize de ventana
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.querySelector('.conversations-sidebar');
                const chatArea = document.getElementById('chat-area');
                sidebar.classList.remove('hidden-mobile');
                chatArea.classList.remove('hidden-mobile');
            }
        });
        
        // Polling para actualizar mensajes cada 5 segundos
        function startPolling() {
            if (pollInterval) clearInterval(pollInterval);
            
            pollInterval = setInterval(() => {
                if (currentConversationId) {
                    loadMessages(currentConversationId);
                }
                loadConversations(searchBox.value);
            }, 5000);
        }
        
        // Inicializar
        loadConversations();
        startPolling();
        
        // En móviles mostrar solo conversaciones al cargar
        if (window.innerWidth <= 768) {
            document.getElementById('chat-area').classList.add('hidden-mobile');
        }
    </script>
    
    <!-- World Map with Clocks -->
    @include('partials.walee-world-map-clocks')
</body>
</html>
