<!-- Support Button (Floating) -->
<button 
    onclick="openSupportModal()" 
    class="fixed bottom-48 right-6 w-12 h-12 bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 group z-40"
    title="Soporte"
    style="bottom: 12rem;"
>
    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-walee-400 dark:group-hover:text-walee-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
</button>

<!-- Botón Scroll Arriba (Floating) -->
<button 
    onclick="scrollToTop()" 
    class="fixed bottom-24 right-6 w-12 h-12 bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 group z-40"
    title="Ir arriba"
    style="bottom: 6rem;"
>
    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-walee-400 dark:group-hover:text-walee-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
    </svg>
</button>

<!-- Botón Scroll Abajo (Floating) -->
<button 
    onclick="scrollToBottom()" 
    class="fixed bottom-6 right-6 w-12 h-12 bg-white dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 group z-40"
    title="Ir abajo"
>
    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-walee-400 dark:group-hover:text-walee-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
    </svg>
</button>

<!-- Support Modal -->
<div id="supportModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-slate-700/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-walee-400/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-walee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-black dark:text-white">Soporte</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400">¿Necesitas ayuda?</p>
                </div>
            </div>
            <button onclick="closeSupportModal()" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-black dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Form -->
        <form id="supportForm" class="p-5 space-y-4">
            <!-- Subject -->
            <div>
                <label class="block text-sm font-medium text-black dark:text-slate-300 mb-2">Asunto</label>
                <input 
                    type="text" 
                    name="subject" 
                    placeholder="¿En qué podemos ayudarte?"
                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-black dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all text-sm"
                >
            </div>
            
            <!-- Message -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-black dark:text-slate-300">Mensaje</label>
                    <button 
                        type="button"
                        onclick="toggleEmojiPicker()"
                        class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-all"
                        title="Insertar emoji"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>
                </div>
                <!-- Emoji Picker -->
                <div id="emojiPicker" class="hidden mb-2 p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl">
                    <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto">
                        <button type="button" onclick="insertEmoji('😀')" class="text-2xl hover:scale-125 transition-transform" title="😀">😀</button>
                        <button type="button" onclick="insertEmoji('😃')" class="text-2xl hover:scale-125 transition-transform" title="😃">😃</button>
                        <button type="button" onclick="insertEmoji('😄')" class="text-2xl hover:scale-125 transition-transform" title="😄">😄</button>
                        <button type="button" onclick="insertEmoji('😁')" class="text-2xl hover:scale-125 transition-transform" title="😁">😁</button>
                        <button type="button" onclick="insertEmoji('😅')" class="text-2xl hover:scale-125 transition-transform" title="😅">😅</button>
                        <button type="button" onclick="insertEmoji('😂')" class="text-2xl hover:scale-125 transition-transform" title="😂">😂</button>
                        <button type="button" onclick="insertEmoji('🤣')" class="text-2xl hover:scale-125 transition-transform" title="🤣">🤣</button>
                        <button type="button" onclick="insertEmoji('😊')" class="text-2xl hover:scale-125 transition-transform" title="😊">😊</button>
                        <button type="button" onclick="insertEmoji('😇')" class="text-2xl hover:scale-125 transition-transform" title="😇">😇</button>
                        <button type="button" onclick="insertEmoji('🙂')" class="text-2xl hover:scale-125 transition-transform" title="🙂">🙂</button>
                        <button type="button" onclick="insertEmoji('🙃')" class="text-2xl hover:scale-125 transition-transform" title="🙃">🙃</button>
                        <button type="button" onclick="insertEmoji('😉')" class="text-2xl hover:scale-125 transition-transform" title="😉">😉</button>
                        <button type="button" onclick="insertEmoji('😌')" class="text-2xl hover:scale-125 transition-transform" title="😌">😌</button>
                        <button type="button" onclick="insertEmoji('😍')" class="text-2xl hover:scale-125 transition-transform" title="😍">😍</button>
                        <button type="button" onclick="insertEmoji('🥰')" class="text-2xl hover:scale-125 transition-transform" title="🥰">🥰</button>
                        <button type="button" onclick="insertEmoji('😘')" class="text-2xl hover:scale-125 transition-transform" title="😘">😘</button>
                        <button type="button" onclick="insertEmoji('😗')" class="text-2xl hover:scale-125 transition-transform" title="😗">😗</button>
                        <button type="button" onclick="insertEmoji('😙')" class="text-2xl hover:scale-125 transition-transform" title="😙">😙</button>
                        <button type="button" onclick="insertEmoji('😚')" class="text-2xl hover:scale-125 transition-transform" title="😚">😚</button>
                        <button type="button" onclick="insertEmoji('😋')" class="text-2xl hover:scale-125 transition-transform" title="😋">😋</button>
                        <button type="button" onclick="insertEmoji('😛')" class="text-2xl hover:scale-125 transition-transform" title="😛">😛</button>
                        <button type="button" onclick="insertEmoji('😝')" class="text-2xl hover:scale-125 transition-transform" title="😝">😝</button>
                        <button type="button" onclick="insertEmoji('😜')" class="text-2xl hover:scale-125 transition-transform" title="😜">😜</button>
                        <button type="button" onclick="insertEmoji('🤪')" class="text-2xl hover:scale-125 transition-transform" title="🤪">🤪</button>
                        <button type="button" onclick="insertEmoji('🤨')" class="text-2xl hover:scale-125 transition-transform" title="🤨">🤨</button>
                        <button type="button" onclick="insertEmoji('🧐')" class="text-2xl hover:scale-125 transition-transform" title="🧐">🧐</button>
                        <button type="button" onclick="insertEmoji('🤓')" class="text-2xl hover:scale-125 transition-transform" title="🤓">🤓</button>
                        <button type="button" onclick="insertEmoji('😎')" class="text-2xl hover:scale-125 transition-transform" title="😎">😎</button>
                        <button type="button" onclick="insertEmoji('🤩')" class="text-2xl hover:scale-125 transition-transform" title="🤩">🤩</button>
                        <button type="button" onclick="insertEmoji('🥳')" class="text-2xl hover:scale-125 transition-transform" title="🥳">🥳</button>
                        <button type="button" onclick="insertEmoji('😏')" class="text-2xl hover:scale-125 transition-transform" title="😏">😏</button>
                        <button type="button" onclick="insertEmoji('😒')" class="text-2xl hover:scale-125 transition-transform" title="😒">😒</button>
                        <button type="button" onclick="insertEmoji('😞')" class="text-2xl hover:scale-125 transition-transform" title="😞">😞</button>
                        <button type="button" onclick="insertEmoji('😔')" class="text-2xl hover:scale-125 transition-transform" title="😔">😔</button>
                        <button type="button" onclick="insertEmoji('😟')" class="text-2xl hover:scale-125 transition-transform" title="😟">😟</button>
                        <button type="button" onclick="insertEmoji('😕')" class="text-2xl hover:scale-125 transition-transform" title="😕">😕</button>
                        <button type="button" onclick="insertEmoji('🙁')" class="text-2xl hover:scale-125 transition-transform" title="🙁">🙁</button>
                        <button type="button" onclick="insertEmoji('😣')" class="text-2xl hover:scale-125 transition-transform" title="😣">😣</button>
                        <button type="button" onclick="insertEmoji('😖')" class="text-2xl hover:scale-125 transition-transform" title="😖">😖</button>
                        <button type="button" onclick="insertEmoji('😫')" class="text-2xl hover:scale-125 transition-transform" title="😫">😫</button>
                        <button type="button" onclick="insertEmoji('😩')" class="text-2xl hover:scale-125 transition-transform" title="😩">😩</button>
                        <button type="button" onclick="insertEmoji('🥺')" class="text-2xl hover:scale-125 transition-transform" title="🥺">🥺</button>
                        <button type="button" onclick="insertEmoji('😢')" class="text-2xl hover:scale-125 transition-transform" title="😢">😢</button>
                        <button type="button" onclick="insertEmoji('😭')" class="text-2xl hover:scale-125 transition-transform" title="😭">😭</button>
                        <button type="button" onclick="insertEmoji('😤')" class="text-2xl hover:scale-125 transition-transform" title="😤">😤</button>
                        <button type="button" onclick="insertEmoji('😠')" class="text-2xl hover:scale-125 transition-transform" title="😠">😠</button>
                        <button type="button" onclick="insertEmoji('😡')" class="text-2xl hover:scale-125 transition-transform" title="😡">😡</button>
                        <button type="button" onclick="insertEmoji('🤬')" class="text-2xl hover:scale-125 transition-transform" title="🤬">🤬</button>
                        <button type="button" onclick="insertEmoji('🤯')" class="text-2xl hover:scale-125 transition-transform" title="🤯">🤯</button>
                        <button type="button" onclick="insertEmoji('😳')" class="text-2xl hover:scale-125 transition-transform" title="😳">😳</button>
                        <button type="button" onclick="insertEmoji('🥵')" class="text-2xl hover:scale-125 transition-transform" title="🥵">🥵</button>
                        <button type="button" onclick="insertEmoji('🥶')" class="text-2xl hover:scale-125 transition-transform" title="🥶">🥶</button>
                        <button type="button" onclick="insertEmoji('😱')" class="text-2xl hover:scale-125 transition-transform" title="😱">😱</button>
                        <button type="button" onclick="insertEmoji('😨')" class="text-2xl hover:scale-125 transition-transform" title="😨">😨</button>
                        <button type="button" onclick="insertEmoji('😰')" class="text-2xl hover:scale-125 transition-transform" title="😰">😰</button>
                        <button type="button" onclick="insertEmoji('😥')" class="text-2xl hover:scale-125 transition-transform" title="😥">😥</button>
                        <button type="button" onclick="insertEmoji('😓')" class="text-2xl hover:scale-125 transition-transform" title="😓">😓</button>
                        <button type="button" onclick="insertEmoji('🤗')" class="text-2xl hover:scale-125 transition-transform" title="🤗">🤗</button>
                        <button type="button" onclick="insertEmoji('🤔')" class="text-2xl hover:scale-125 transition-transform" title="🤔">🤔</button>
                        <button type="button" onclick="insertEmoji('🤭')" class="text-2xl hover:scale-125 transition-transform" title="🤭">🤭</button>
                        <button type="button" onclick="insertEmoji('🤫')" class="text-2xl hover:scale-125 transition-transform" title="🤫">🤫</button>
                        <button type="button" onclick="insertEmoji('🤥')" class="text-2xl hover:scale-125 transition-transform" title="🤥">🤥</button>
                        <button type="button" onclick="insertEmoji('😶')" class="text-2xl hover:scale-125 transition-transform" title="😶">😶</button>
                        <button type="button" onclick="insertEmoji('😐')" class="text-2xl hover:scale-125 transition-transform" title="😐">😐</button>
                        <button type="button" onclick="insertEmoji('😑')" class="text-2xl hover:scale-125 transition-transform" title="😑">😑</button>
                        <button type="button" onclick="insertEmoji('😬')" class="text-2xl hover:scale-125 transition-transform" title="😬">😬</button>
                        <button type="button" onclick="insertEmoji('🙄')" class="text-2xl hover:scale-125 transition-transform" title="🙄">🙄</button>
                        <button type="button" onclick="insertEmoji('😯')" class="text-2xl hover:scale-125 transition-transform" title="😯">😯</button>
                        <button type="button" onclick="insertEmoji('😦')" class="text-2xl hover:scale-125 transition-transform" title="😦">😦</button>
                        <button type="button" onclick="insertEmoji('😧')" class="text-2xl hover:scale-125 transition-transform" title="😧">😧</button>
                        <button type="button" onclick="insertEmoji('😮')" class="text-2xl hover:scale-125 transition-transform" title="😮">😮</button>
                        <button type="button" onclick="insertEmoji('😲')" class="text-2xl hover:scale-125 transition-transform" title="😲">😲</button>
                        <button type="button" onclick="insertEmoji('🥱')" class="text-2xl hover:scale-125 transition-transform" title="🥱">🥱</button>
                        <button type="button" onclick="insertEmoji('😴')" class="text-2xl hover:scale-125 transition-transform" title="😴">😴</button>
                        <button type="button" onclick="insertEmoji('🤤')" class="text-2xl hover:scale-125 transition-transform" title="🤤">🤤</button>
                        <button type="button" onclick="insertEmoji('😪')" class="text-2xl hover:scale-125 transition-transform" title="😪">😪</button>
                        <button type="button" onclick="insertEmoji('😵')" class="text-2xl hover:scale-125 transition-transform" title="😵">😵</button>
                        <button type="button" onclick="insertEmoji('🤐')" class="text-2xl hover:scale-125 transition-transform" title="🤐">🤐</button>
                        <button type="button" onclick="insertEmoji('🥴')" class="text-2xl hover:scale-125 transition-transform" title="🥴">🥴</button>
                        <button type="button" onclick="insertEmoji('🤢')" class="text-2xl hover:scale-125 transition-transform" title="🤢">🤢</button>
                        <button type="button" onclick="insertEmoji('🤮')" class="text-2xl hover:scale-125 transition-transform" title="🤮">🤮</button>
                        <button type="button" onclick="insertEmoji('🤧')" class="text-2xl hover:scale-125 transition-transform" title="🤧">🤧</button>
                        <button type="button" onclick="insertEmoji('😷')" class="text-2xl hover:scale-125 transition-transform" title="😷">😷</button>
                        <button type="button" onclick="insertEmoji('🤒')" class="text-2xl hover:scale-125 transition-transform" title="🤒">🤒</button>
                        <button type="button" onclick="insertEmoji('🤕')" class="text-2xl hover:scale-125 transition-transform" title="🤕">🤕</button>
                        <button type="button" onclick="insertEmoji('🤑')" class="text-2xl hover:scale-125 transition-transform" title="🤑">🤑</button>
                        <button type="button" onclick="insertEmoji('🤠')" class="text-2xl hover:scale-125 transition-transform" title="🤠">🤠</button>
                        <button type="button" onclick="insertEmoji('😈')" class="text-2xl hover:scale-125 transition-transform" title="😈">😈</button>
                        <button type="button" onclick="insertEmoji('👿')" class="text-2xl hover:scale-125 transition-transform" title="👿">👿</button>
                        <button type="button" onclick="insertEmoji('👹')" class="text-2xl hover:scale-125 transition-transform" title="👹">👹</button>
                        <button type="button" onclick="insertEmoji('👺')" class="text-2xl hover:scale-125 transition-transform" title="👺">👺</button>
                        <button type="button" onclick="insertEmoji('🤡')" class="text-2xl hover:scale-125 transition-transform" title="🤡">🤡</button>
                        <button type="button" onclick="insertEmoji('💩')" class="text-2xl hover:scale-125 transition-transform" title="💩">💩</button>
                        <button type="button" onclick="insertEmoji('👻')" class="text-2xl hover:scale-125 transition-transform" title="👻">👻</button>
                        <button type="button" onclick="insertEmoji('💀')" class="text-2xl hover:scale-125 transition-transform" title="💀">💀</button>
                        <button type="button" onclick="insertEmoji('☠️')" class="text-2xl hover:scale-125 transition-transform" title="☠️">☠️</button>
                        <button type="button" onclick="insertEmoji('👽')" class="text-2xl hover:scale-125 transition-transform" title="👽">👽</button>
                        <button type="button" onclick="insertEmoji('👾')" class="text-2xl hover:scale-125 transition-transform" title="👾">👾</button>
                        <button type="button" onclick="insertEmoji('🤖')" class="text-2xl hover:scale-125 transition-transform" title="🤖">🤖</button>
                        <button type="button" onclick="insertEmoji('🎃')" class="text-2xl hover:scale-125 transition-transform" title="🎃">🎃</button>
                        <button type="button" onclick="insertEmoji('😺')" class="text-2xl hover:scale-125 transition-transform" title="😺">😺</button>
                        <button type="button" onclick="insertEmoji('😸')" class="text-2xl hover:scale-125 transition-transform" title="😸">😸</button>
                        <button type="button" onclick="insertEmoji('😹')" class="text-2xl hover:scale-125 transition-transform" title="😹">😹</button>
                        <button type="button" onclick="insertEmoji('😻')" class="text-2xl hover:scale-125 transition-transform" title="😻">😻</button>
                        <button type="button" onclick="insertEmoji('😼')" class="text-2xl hover:scale-125 transition-transform" title="😼">😼</button>
                        <button type="button" onclick="insertEmoji('😽')" class="text-2xl hover:scale-125 transition-transform" title="😽">😽</button>
                        <button type="button" onclick="insertEmoji('🙀')" class="text-2xl hover:scale-125 transition-transform" title="🙀">🙀</button>
                        <button type="button" onclick="insertEmoji('😿')" class="text-2xl hover:scale-125 transition-transform" title="😿">😿</button>
                        <button type="button" onclick="insertEmoji('😾')" class="text-2xl hover:scale-125 transition-transform" title="😾">😾</button>
                        <button type="button" onclick="insertEmoji('👋')" class="text-2xl hover:scale-125 transition-transform" title="👋">👋</button>
                        <button type="button" onclick="insertEmoji('🤚')" class="text-2xl hover:scale-125 transition-transform" title="🤚">🤚</button>
                        <button type="button" onclick="insertEmoji('🖐')" class="text-2xl hover:scale-125 transition-transform" title="🖐">🖐</button>
                        <button type="button" onclick="insertEmoji('✋')" class="text-2xl hover:scale-125 transition-transform" title="✋">✋</button>
                        <button type="button" onclick="insertEmoji('🖖')" class="text-2xl hover:scale-125 transition-transform" title="🖖">🖖</button>
                        <button type="button" onclick="insertEmoji('👌')" class="text-2xl hover:scale-125 transition-transform" title="👌">👌</button>
                        <button type="button" onclick="insertEmoji('🤌')" class="text-2xl hover:scale-125 transition-transform" title="🤌">🤌</button>
                        <button type="button" onclick="insertEmoji('🤏')" class="text-2xl hover:scale-125 transition-transform" title="🤏">🤏</button>
                        <button type="button" onclick="insertEmoji('✌️')" class="text-2xl hover:scale-125 transition-transform" title="✌️">✌️</button>
                        <button type="button" onclick="insertEmoji('🤞')" class="text-2xl hover:scale-125 transition-transform" title="🤞">🤞</button>
                        <button type="button" onclick="insertEmoji('🤟')" class="text-2xl hover:scale-125 transition-transform" title="🤟">🤟</button>
                        <button type="button" onclick="insertEmoji('🤘')" class="text-2xl hover:scale-125 transition-transform" title="🤘">🤘</button>
                        <button type="button" onclick="insertEmoji('🤙')" class="text-2xl hover:scale-125 transition-transform" title="🤙">🤙</button>
                        <button type="button" onclick="insertEmoji('👈')" class="text-2xl hover:scale-125 transition-transform" title="👈">👈</button>
                        <button type="button" onclick="insertEmoji('👉')" class="text-2xl hover:scale-125 transition-transform" title="👉">👉</button>
                        <button type="button" onclick="insertEmoji('👆')" class="text-2xl hover:scale-125 transition-transform" title="👆">👆</button>
                        <button type="button" onclick="insertEmoji('🖕')" class="text-2xl hover:scale-125 transition-transform" title="🖕">🖕</button>
                        <button type="button" onclick="insertEmoji('👇')" class="text-2xl hover:scale-125 transition-transform" title="👇">👇</button>
                        <button type="button" onclick="insertEmoji('☝️')" class="text-2xl hover:scale-125 transition-transform" title="☝️">☝️</button>
                        <button type="button" onclick="insertEmoji('👍')" class="text-2xl hover:scale-125 transition-transform" title="👍">👍</button>
                        <button type="button" onclick="insertEmoji('👎')" class="text-2xl hover:scale-125 transition-transform" title="👎">👎</button>
                        <button type="button" onclick="insertEmoji('✊')" class="text-2xl hover:scale-125 transition-transform" title="✊">✊</button>
                        <button type="button" onclick="insertEmoji('👊')" class="text-2xl hover:scale-125 transition-transform" title="👊">👊</button>
                        <button type="button" onclick="insertEmoji('🤛')" class="text-2xl hover:scale-125 transition-transform" title="🤛">🤛</button>
                        <button type="button" onclick="insertEmoji('🤜')" class="text-2xl hover:scale-125 transition-transform" title="🤜">🤜</button>
                        <button type="button" onclick="insertEmoji('👏')" class="text-2xl hover:scale-125 transition-transform" title="👏">👏</button>
                        <button type="button" onclick="insertEmoji('🙌')" class="text-2xl hover:scale-125 transition-transform" title="🙌">🙌</button>
                        <button type="button" onclick="insertEmoji('👐')" class="text-2xl hover:scale-125 transition-transform" title="👐">👐</button>
                        <button type="button" onclick="insertEmoji('🤲')" class="text-2xl hover:scale-125 transition-transform" title="🤲">🤲</button>
                        <button type="button" onclick="insertEmoji('🤝')" class="text-2xl hover:scale-125 transition-transform" title="🤝">🤝</button>
                        <button type="button" onclick="insertEmoji('🙏')" class="text-2xl hover:scale-125 transition-transform" title="🙏">🙏</button>
                        <button type="button" onclick="insertEmoji('✍️')" class="text-2xl hover:scale-125 transition-transform" title="✍️">✍️</button>
                        <button type="button" onclick="insertEmoji('💪')" class="text-2xl hover:scale-125 transition-transform" title="💪">💪</button>
                        <button type="button" onclick="insertEmoji('🦾')" class="text-2xl hover:scale-125 transition-transform" title="🦾">🦾</button>
                        <button type="button" onclick="insertEmoji('🦿')" class="text-2xl hover:scale-125 transition-transform" title="🦿">🦿</button>
                        <button type="button" onclick="insertEmoji('🦵')" class="text-2xl hover:scale-125 transition-transform" title="🦵">🦵</button>
                        <button type="button" onclick="insertEmoji('🦶')" class="text-2xl hover:scale-125 transition-transform" title="🦶">🦶</button>
                        <button type="button" onclick="insertEmoji('👂')" class="text-2xl hover:scale-125 transition-transform" title="👂">👂</button>
                        <button type="button" onclick="insertEmoji('🦻')" class="text-2xl hover:scale-125 transition-transform" title="🦻">🦻</button>
                        <button type="button" onclick="insertEmoji('👃')" class="text-2xl hover:scale-125 transition-transform" title="👃">👃</button>
                        <button type="button" onclick="insertEmoji('🧠')" class="text-2xl hover:scale-125 transition-transform" title="🧠">🧠</button>
                        <button type="button" onclick="insertEmoji('🦷')" class="text-2xl hover:scale-125 transition-transform" title="🦷">🦷</button>
                        <button type="button" onclick="insertEmoji('🦴')" class="text-2xl hover:scale-125 transition-transform" title="🦴">🦴</button>
                        <button type="button" onclick="insertEmoji('👀')" class="text-2xl hover:scale-125 transition-transform" title="👀">👀</button>
                        <button type="button" onclick="insertEmoji('👁️')" class="text-2xl hover:scale-125 transition-transform" title="👁️">👁️</button>
                        <button type="button" onclick="insertEmoji('👅')" class="text-2xl hover:scale-125 transition-transform" title="👅">👅</button>
                        <button type="button" onclick="insertEmoji('👄')" class="text-2xl hover:scale-125 transition-transform" title="👄">👄</button>
                        <button type="button" onclick="insertEmoji('💋')" class="text-2xl hover:scale-125 transition-transform" title="💋">💋</button>
                        <button type="button" onclick="insertEmoji('💘')" class="text-2xl hover:scale-125 transition-transform" title="💘">💘</button>
                        <button type="button" onclick="insertEmoji('💝')" class="text-2xl hover:scale-125 transition-transform" title="💝">💝</button>
                        <button type="button" onclick="insertEmoji('💖')" class="text-2xl hover:scale-125 transition-transform" title="💖">💖</button>
                        <button type="button" onclick="insertEmoji('💗')" class="text-2xl hover:scale-125 transition-transform" title="💗">💗</button>
                        <button type="button" onclick="insertEmoji('💓')" class="text-2xl hover:scale-125 transition-transform" title="💓">💓</button>
                        <button type="button" onclick="insertEmoji('💞')" class="text-2xl hover:scale-125 transition-transform" title="💞">💞</button>
                        <button type="button" onclick="insertEmoji('💕')" class="text-2xl hover:scale-125 transition-transform" title="💕">💕</button>
                        <button type="button" onclick="insertEmoji('💟')" class="text-2xl hover:scale-125 transition-transform" title="💟">💟</button>
                        <button type="button" onclick="insertEmoji('❣️')" class="text-2xl hover:scale-125 transition-transform" title="❣️">❣️</button>
                        <button type="button" onclick="insertEmoji('💔')" class="text-2xl hover:scale-125 transition-transform" title="💔">💔</button>
                        <button type="button" onclick="insertEmoji('❤️')" class="text-2xl hover:scale-125 transition-transform" title="❤️">❤️</button>
                        <button type="button" onclick="insertEmoji('🧡')" class="text-2xl hover:scale-125 transition-transform" title="🧡">🧡</button>
                        <button type="button" onclick="insertEmoji('💛')" class="text-2xl hover:scale-125 transition-transform" title="💛">💛</button>
                        <button type="button" onclick="insertEmoji('💚')" class="text-2xl hover:scale-125 transition-transform" title="💚">💚</button>
                        <button type="button" onclick="insertEmoji('💙')" class="text-2xl hover:scale-125 transition-transform" title="💙">💙</button>
                        <button type="button" onclick="insertEmoji('💜')" class="text-2xl hover:scale-125 transition-transform" title="💜">💜</button>
                        <button type="button" onclick="insertEmoji('🖤')" class="text-2xl hover:scale-125 transition-transform" title="🖤">🖤</button>
                        <button type="button" onclick="insertEmoji('🤍')" class="text-2xl hover:scale-125 transition-transform" title="🤍">🤍</button>
                        <button type="button" onclick="insertEmoji('🤎')" class="text-2xl hover:scale-125 transition-transform" title="🤎">🤎</button>
                        <button type="button" onclick="insertEmoji('💯')" class="text-2xl hover:scale-125 transition-transform" title="💯">💯</button>
                        <button type="button" onclick="insertEmoji('💢')" class="text-2xl hover:scale-125 transition-transform" title="💢">💢</button>
                        <button type="button" onclick="insertEmoji('💥')" class="text-2xl hover:scale-125 transition-transform" title="💥">💥</button>
                        <button type="button" onclick="insertEmoji('💫')" class="text-2xl hover:scale-125 transition-transform" title="💫">💫</button>
                        <button type="button" onclick="insertEmoji('💦')" class="text-2xl hover:scale-125 transition-transform" title="💦">💦</button>
                        <button type="button" onclick="insertEmoji('💨')" class="text-2xl hover:scale-125 transition-transform" title="💨">💨</button>
                        <button type="button" onclick="insertEmoji('🕳️')" class="text-2xl hover:scale-125 transition-transform" title="🕳️">🕳️</button>
                        <button type="button" onclick="insertEmoji('💣')" class="text-2xl hover:scale-125 transition-transform" title="💣">💣</button>
                        <button type="button" onclick="insertEmoji('💬')" class="text-2xl hover:scale-125 transition-transform" title="💬">💬</button>
                        <button type="button" onclick="insertEmoji('👁️‍🗨️')" class="text-2xl hover:scale-125 transition-transform" title="👁️‍🗨️">👁️‍🗨️</button>
                        <button type="button" onclick="insertEmoji('🗨️')" class="text-2xl hover:scale-125 transition-transform" title="🗨️">🗨️</button>
                        <button type="button" onclick="insertEmoji('🗯️')" class="text-2xl hover:scale-125 transition-transform" title="🗯️">🗯️</button>
                        <button type="button" onclick="insertEmoji('💭')" class="text-2xl hover:scale-125 transition-transform" title="💭">💭</button>
                        <button type="button" onclick="insertEmoji('💤')" class="text-2xl hover:scale-125 transition-transform" title="💤">💤</button>
                        <button type="button" onclick="insertEmoji('👋')" class="text-2xl hover:scale-125 transition-transform" title="👋">👋</button>
                        <button type="button" onclick="insertEmoji('🤚')" class="text-2xl hover:scale-125 transition-transform" title="🤚">🤚</button>
                        <button type="button" onclick="insertEmoji('🖐')" class="text-2xl hover:scale-125 transition-transform" title="🖐">🖐</button>
                        <button type="button" onclick="insertEmoji('✋')" class="text-2xl hover:scale-125 transition-transform" title="✋">✋</button>
                        <button type="button" onclick="insertEmoji('🖖')" class="text-2xl hover:scale-125 transition-transform" title="🖖">🖖</button>
                        <button type="button" onclick="insertEmoji('👌')" class="text-2xl hover:scale-125 transition-transform" title="👌">👌</button>
                        <button type="button" onclick="insertEmoji('🤌')" class="text-2xl hover:scale-125 transition-transform" title="🤌">🤌</button>
                        <button type="button" onclick="insertEmoji('🤏')" class="text-2xl hover:scale-125 transition-transform" title="🤏">🤏</button>
                        <button type="button" onclick="insertEmoji('✌️')" class="text-2xl hover:scale-125 transition-transform" title="✌️">✌️</button>
                        <button type="button" onclick="insertEmoji('🤞')" class="text-2xl hover:scale-125 transition-transform" title="🤞">🤞</button>
                        <button type="button" onclick="insertEmoji('🤟')" class="text-2xl hover:scale-125 transition-transform" title="🤟">🤟</button>
                        <button type="button" onclick="insertEmoji('🤘')" class="text-2xl hover:scale-125 transition-transform" title="🤘">🤘</button>
                        <button type="button" onclick="insertEmoji('🤙')" class="text-2xl hover:scale-125 transition-transform" title="🤙">🤙</button>
                        <button type="button" onclick="insertEmoji('👈')" class="text-2xl hover:scale-125 transition-transform" title="👈">👈</button>
                        <button type="button" onclick="insertEmoji('👉')" class="text-2xl hover:scale-125 transition-transform" title="👉">👉</button>
                        <button type="button" onclick="insertEmoji('👆')" class="text-2xl hover:scale-125 transition-transform" title="👆">👆</button>
                        <button type="button" onclick="insertEmoji('🖕')" class="text-2xl hover:scale-125 transition-transform" title="🖕">🖕</button>
                        <button type="button" onclick="insertEmoji('👇')" class="text-2xl hover:scale-125 transition-transform" title="👇">👇</button>
                        <button type="button" onclick="insertEmoji('☝️')" class="text-2xl hover:scale-125 transition-transform" title="☝️">☝️</button>
                        <button type="button" onclick="insertEmoji('👍')" class="text-2xl hover:scale-125 transition-transform" title="👍">👍</button>
                        <button type="button" onclick="insertEmoji('👎')" class="text-2xl hover:scale-125 transition-transform" title="👎">👎</button>
                        <button type="button" onclick="insertEmoji('✊')" class="text-2xl hover:scale-125 transition-transform" title="✊">✊</button>
                        <button type="button" onclick="insertEmoji('👊')" class="text-2xl hover:scale-125 transition-transform" title="👊">👊</button>
                        <button type="button" onclick="insertEmoji('🤛')" class="text-2xl hover:scale-125 transition-transform" title="🤛">🤛</button>
                        <button type="button" onclick="insertEmoji('🤜')" class="text-2xl hover:scale-125 transition-transform" title="🤜">🤜</button>
                        <button type="button" onclick="insertEmoji('👏')" class="text-2xl hover:scale-125 transition-transform" title="👏">👏</button>
                        <button type="button" onclick="insertEmoji('🙌')" class="text-2xl hover:scale-125 transition-transform" title="🙌">🙌</button>
                        <button type="button" onclick="insertEmoji('👐')" class="text-2xl hover:scale-125 transition-transform" title="👐">👐</button>
                        <button type="button" onclick="insertEmoji('🤲')" class="text-2xl hover:scale-125 transition-transform" title="🤲">🤲</button>
                        <button type="button" onclick="insertEmoji('🤝')" class="text-2xl hover:scale-125 transition-transform" title="🤝">🤝</button>
                        <button type="button" onclick="insertEmoji('🙏')" class="text-2xl hover:scale-125 transition-transform" title="🙏">🙏</button>
                        <button type="button" onclick="insertEmoji('✍️')" class="text-2xl hover:scale-125 transition-transform" title="✍️">✍️</button>
                        <button type="button" onclick="insertEmoji('💪')" class="text-2xl hover:scale-125 transition-transform" title="💪">💪</button>
                        <button type="button" onclick="insertEmoji('🦾')" class="text-2xl hover:scale-125 transition-transform" title="🦾">🦾</button>
                        <button type="button" onclick="insertEmoji('🦿')" class="text-2xl hover:scale-125 transition-transform" title="🦿">🦿</button>
                        <button type="button" onclick="insertEmoji('🦵')" class="text-2xl hover:scale-125 transition-transform" title="🦵">🦵</button>
                        <button type="button" onclick="insertEmoji('🦶')" class="text-2xl hover:scale-125 transition-transform" title="🦶">🦶</button>
                        <button type="button" onclick="insertEmoji('👂')" class="text-2xl hover:scale-125 transition-transform" title="👂">👂</button>
                        <button type="button" onclick="insertEmoji('🦻')" class="text-2xl hover:scale-125 transition-transform" title="🦻">🦻</button>
                        <button type="button" onclick="insertEmoji('👃')" class="text-2xl hover:scale-125 transition-transform" title="👃">👃</button>
                        <button type="button" onclick="insertEmoji('🧠')" class="text-2xl hover:scale-125 transition-transform" title="🧠">🧠</button>
                        <button type="button" onclick="insertEmoji('🦷')" class="text-2xl hover:scale-125 transition-transform" title="🦷">🦷</button>
                        <button type="button" onclick="insertEmoji('🦴')" class="text-2xl hover:scale-125 transition-transform" title="🦴">🦴</button>
                        <button type="button" onclick="insertEmoji('👀')" class="text-2xl hover:scale-125 transition-transform" title="👀">👀</button>
                        <button type="button" onclick="insertEmoji('👁️')" class="text-2xl hover:scale-125 transition-transform" title="👁️">👁️</button>
                        <button type="button" onclick="insertEmoji('👅')" class="text-2xl hover:scale-125 transition-transform" title="👅">👅</button>
                        <button type="button" onclick="insertEmoji('👄')" class="text-2xl hover:scale-125 transition-transform" title="👄">👄</button>
                        <button type="button" onclick="insertEmoji('💋')" class="text-2xl hover:scale-125 transition-transform" title="💋">💋</button>
                        <button type="button" onclick="insertEmoji('💘')" class="text-2xl hover:scale-125 transition-transform" title="💘">💘</button>
                        <button type="button" onclick="insertEmoji('💝')" class="text-2xl hover:scale-125 transition-transform" title="💝">💝</button>
                        <button type="button" onclick="insertEmoji('💖')" class="text-2xl hover:scale-125 transition-transform" title="💖">💖</button>
                        <button type="button" onclick="insertEmoji('💗')" class="text-2xl hover:scale-125 transition-transform" title="💗">💗</button>
                        <button type="button" onclick="insertEmoji('💓')" class="text-2xl hover:scale-125 transition-transform" title="💓">💓</button>
                        <button type="button" onclick="insertEmoji('💞')" class="text-2xl hover:scale-125 transition-transform" title="💞">💞</button>
                        <button type="button" onclick="insertEmoji('💕')" class="text-2xl hover:scale-125 transition-transform" title="💕">💕</button>
                        <button type="button" onclick="insertEmoji('💟')" class="text-2xl hover:scale-125 transition-transform" title="💟">💟</button>
                        <button type="button" onclick="insertEmoji('❣️')" class="text-2xl hover:scale-125 transition-transform" title="❣️">❣️</button>
                        <button type="button" onclick="insertEmoji('💔')" class="text-2xl hover:scale-125 transition-transform" title="💔">💔</button>
                        <button type="button" onclick="insertEmoji('❤️')" class="text-2xl hover:scale-125 transition-transform" title="❤️">❤️</button>
                        <button type="button" onclick="insertEmoji('🧡')" class="text-2xl hover:scale-125 transition-transform" title="🧡">🧡</button>
                        <button type="button" onclick="insertEmoji('💛')" class="text-2xl hover:scale-125 transition-transform" title="💛">💛</button>
                        <button type="button" onclick="insertEmoji('💚')" class="text-2xl hover:scale-125 transition-transform" title="💚">💚</button>
                        <button type="button" onclick="insertEmoji('💙')" class="text-2xl hover:scale-125 transition-transform" title="💙">💙</button>
                        <button type="button" onclick="insertEmoji('💜')" class="text-2xl hover:scale-125 transition-transform" title="💜">💜</button>
                        <button type="button" onclick="insertEmoji('🖤')" class="text-2xl hover:scale-125 transition-transform" title="🖤">🖤</button>
                        <button type="button" onclick="insertEmoji('🤍')" class="text-2xl hover:scale-125 transition-transform" title="🤍">🤍</button>
                        <button type="button" onclick="insertEmoji('🤎')" class="text-2xl hover:scale-125 transition-transform" title="🤎">🤎</button>
                        <button type="button" onclick="insertEmoji('💯')" class="text-2xl hover:scale-125 transition-transform" title="💯">💯</button>
                        <button type="button" onclick="insertEmoji('💢')" class="text-2xl hover:scale-125 transition-transform" title="💢">💢</button>
                        <button type="button" onclick="insertEmoji('💥')" class="text-2xl hover:scale-125 transition-transform" title="💥">💥</button>
                        <button type="button" onclick="insertEmoji('💫')" class="text-2xl hover:scale-125 transition-transform" title="💫">💫</button>
                        <button type="button" onclick="insertEmoji('💦')" class="text-2xl hover:scale-125 transition-transform" title="💦">💦</button>
                        <button type="button" onclick="insertEmoji('💨')" class="text-2xl hover:scale-125 transition-transform" title="💨">💨</button>
                        <button type="button" onclick="insertEmoji('🕳️')" class="text-2xl hover:scale-125 transition-transform" title="🕳️">🕳️</button>
                        <button type="button" onclick="insertEmoji('💣')" class="text-2xl hover:scale-125 transition-transform" title="💣">💣</button>
                        <button type="button" onclick="insertEmoji('💬')" class="text-2xl hover:scale-125 transition-transform" title="💬">💬</button>
                        <button type="button" onclick="insertEmoji('👁️‍🗨️')" class="text-2xl hover:scale-125 transition-transform" title="👁️‍🗨️">👁️‍🗨️</button>
                        <button type="button" onclick="insertEmoji('🗨️')" class="text-2xl hover:scale-125 transition-transform" title="🗨️">🗨️</button>
                        <button type="button" onclick="insertEmoji('🗯️')" class="text-2xl hover:scale-125 transition-transform" title="🗯️">🗯️</button>
                        <button type="button" onclick="insertEmoji('💭')" class="text-2xl hover:scale-125 transition-transform" title="💭">💭</button>
                        <button type="button" onclick="insertEmoji('💤')" class="text-2xl hover:scale-125 transition-transform" title="💤">💤</button>
                    </div>
                </div>
                <textarea 
                    name="message" 
                    id="supportMessage"
                    rows="4" 
                    placeholder="Describe tu problema o pregunta..."
                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-black dark:text-white placeholder-slate-500 dark:placeholder-slate-500 focus:border-walee-500 focus:ring-2 focus:ring-walee-500/20 focus:outline-none transition-all resize-none text-sm"
                ></textarea>
            </div>
            
            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium text-black dark:text-slate-300 mb-2">Archivos adjuntos (opcional)</label>
                <div class="relative">
                    <input 
                        type="file" 
                        name="screenshots" 
                        id="supportFile"
                        accept="image/*,application/pdf"
                        multiple
                        class="hidden"
                        onchange="handleSupportFiles(this)"
                    >
                    <label 
                        for="supportFile" 
                        class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-400 hover:border-walee-500/50 hover:text-walee-400 cursor-pointer transition-all"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span id="fileLabel" class="text-sm">Subir archivos (imágenes o PDF)</span>
                    </label>
                </div>
                <!-- Selected Files List -->
                <div id="supportFilesList" class="mt-3 space-y-2 hidden"></div>
            </div>
            
            <!-- Estado del Ticket (Solo uno a la vez) -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-black dark:text-slate-300 mb-2">Estado del ticket</label>
                
                <!-- Urgente Radio -->
                <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <input 
                        type="radio" 
                        name="ticket_estado" 
                        value="urgente"
                        id="supportUrgente"
                        class="w-5 h-5 text-red-500 border-slate-300 dark:border-slate-600 focus:ring-red-500 focus:ring-2"
                    >
                    <div class="flex items-center gap-2 flex-1">
                        <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span class="text-sm font-medium text-black dark:text-white">Urgente</span>
                    </div>
                </label>
                
                <!-- Prioritario Radio -->
                <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <input 
                        type="radio" 
                        name="ticket_estado" 
                        value="prioritario"
                        id="supportPrioritario"
                        class="w-5 h-5 text-yellow-500 border-slate-300 dark:border-slate-600 focus:ring-yellow-500 focus:ring-2"
                    >
                    <div class="flex items-center gap-2 flex-1">
                        <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span class="text-sm font-medium text-black dark:text-white">Prioritario</span>
                    </div>
                </label>
                
                <!-- A Discutir Radio -->
                <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <input 
                        type="radio" 
                        name="ticket_estado" 
                        value="a_discutir"
                        id="supportADiscutir"
                        class="w-5 h-5 text-blue-500 border-slate-300 dark:border-slate-600 focus:ring-blue-500 focus:ring-2"
                    >
                    <div class="flex items-center gap-2 flex-1">
                        <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span class="text-sm font-medium text-black dark:text-white">A Discutir</span>
                    </div>
                </label>
            </div>
            
            <!-- Submit -->
            <button 
                type="submit" 
                class="w-full px-4 py-3 bg-walee-500 hover:bg-walee-400 text-white font-medium rounded-xl transition-all flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Enviar mensaje
            </button>
        </form>
        
        <!-- Footer -->
        <div class="px-5 py-3 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700/50">
            <p class="text-xs text-slate-600 dark:text-slate-500 text-center">
                También puedes escribirnos a <span class="text-walee-400">websolutionscrnow@gmail.com</span>
            </p>
        </div>
    </div>
</div>

<script>
    // Funciones de emoji - siempre disponibles globalmente
    function toggleEmojiPicker() {
        const emojiPicker = document.getElementById('emojiPicker');
        if (emojiPicker) {
            emojiPicker.classList.toggle('hidden');
        }
    }
    
    function insertEmoji(emoji) {
        const messageTextarea = document.getElementById('supportMessage');
        if (messageTextarea) {
            const cursorPos = messageTextarea.selectionStart;
            const textBefore = messageTextarea.value.substring(0, cursorPos);
            const textAfter = messageTextarea.value.substring(messageTextarea.selectionEnd);
            messageTextarea.value = textBefore + emoji + textAfter;
            messageTextarea.focus();
            messageTextarea.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
            // Guardar datos después de insertar emoji
            if (typeof saveSupportFormData === 'function') {
                saveSupportFormData();
            }
        }
    }
    
    // Hacer funciones accesibles globalmente
    window.toggleEmojiPicker = toggleEmojiPicker;
    window.insertEmoji = insertEmoji;
    
    // Support modal functions - only initialize if not already defined
    if (typeof showSupportMessage === 'undefined') {
        function showSupportMessage() {
            // Crear un mensaje temporal
            const messageDiv = document.createElement('div');
            messageDiv.className = 'fixed top-4 right-4 bg-walee-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[10000] max-w-sm animate-fade-in-up';
            messageDiv.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold mb-1">Soporte Temporalmente Deshabilitado</h4>
                        <p class="text-sm text-white/90">Volvemos el <strong>5 de enero</strong>. Gracias por tu comprensión.</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-white/80 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(messageDiv);
            
            // Remover el mensaje después de 5 segundos
            setTimeout(() => {
                if (messageDiv.parentElement) {
                    messageDiv.remove();
                }
            }, 5000);
        }
    }
    
    if (typeof openSupportModal === 'undefined') {
        const csrfTokenSupport = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        function openSupportModal() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.classList.remove('hidden');
                // Restaurar datos guardados
                restoreSupportFormData();
            }
        }
        
        function closeSupportModal() {
            const modal = document.getElementById('supportModal');
            const form = document.getElementById('supportForm');
            
            if (modal) {
                // Guardar datos antes de cerrar
                saveSupportFormData();
                modal.classList.add('hidden');
            }
            // NO resetear el formulario aquí, solo guardar los datos
        }
        
        function saveSupportFormData() {
            const form = document.getElementById('supportForm');
            if (!form) return;
            
            const formData = {
                subject: form.querySelector('[name="subject"]')?.value || '',
                message: form.querySelector('[name="message"]')?.value || '',
                ticket_estado: form.querySelector('input[name="ticket_estado"]:checked')?.value || ''
            };
            
            localStorage.setItem('supportFormData', JSON.stringify(formData));
        }
        
        function restoreSupportFormData() {
            const form = document.getElementById('supportForm');
            if (!form) return;
            
            const savedData = localStorage.getItem('supportFormData');
            if (savedData) {
                try {
                    const formData = JSON.parse(savedData);
                    
                    const subjectInput = form.querySelector('[name="subject"]');
                    const messageInput = form.querySelector('[name="message"]');
                    
                    if (subjectInput) subjectInput.value = formData.subject || '';
                    if (messageInput) messageInput.value = formData.message || '';
                    
                    // Restaurar radio button seleccionado
                    if (formData.ticket_estado) {
                        const estadoRadio = form.querySelector(`input[name="ticket_estado"][value="${formData.ticket_estado}"]`);
                        if (estadoRadio) {
                            estadoRadio.checked = true;
                        }
                    }
                } catch (e) {
                    console.error('Error restaurando datos del formulario:', e);
                }
            }
        }
        
        function clearSupportFormData() {
            localStorage.removeItem('supportFormData');
            const form = document.getElementById('supportForm');
            const fileLabel = document.getElementById('fileLabel');
            const filesList = document.getElementById('supportFilesList');
            
            if (form) {
                form.reset();
            }
            if (fileLabel) {
                fileLabel.textContent = 'Subir archivos (imágenes o PDF)';
            }
            if (filesList) {
                filesList.classList.add('hidden');
                filesList.innerHTML = '';
            }
            // Reset radio buttons
            const estadoRadios = document.querySelectorAll('input[name="ticket_estado"]');
            estadoRadios.forEach(radio => radio.checked = false);
        }
        
        function handleSupportFiles(input) {
            const filesList = document.getElementById('supportFilesList');
            const fileLabel = document.getElementById('fileLabel');
            
            if (!filesList || !input.files || input.files.length === 0) {
                if (filesList) filesList.classList.add('hidden');
                if (fileLabel) fileLabel.textContent = 'Subir archivos (imágenes o PDF)';
                return;
            }
            
            filesList.innerHTML = '';
            filesList.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700';
                fileItem.innerHTML = `
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm text-black dark:text-white truncate">${file.name}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 flex-shrink-0">(${formatSupportFileSize(file.size)})</span>
                    </div>
                    <button 
                        type="button" 
                        onclick="removeSupportFile(${index})" 
                        class="ml-2 p-1 text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                        title="Eliminar archivo"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                filesList.appendChild(fileItem);
            });
            
            const fileCount = input.files.length;
            if (fileLabel) {
                fileLabel.textContent = fileCount === 1 
                    ? input.files[0].name 
                    : `${fileCount} archivos seleccionados`;
            }
        }
        
        function removeSupportFile(index) {
            const input = document.getElementById('supportFile');
            if (!input || !input.files) return;
            
            const dt = new DataTransfer();
            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            input.files = dt.files;
            handleSupportFiles(input);
        }
        
        function formatSupportFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        function showSupportNotification(title, message, type) {
            const modal = document.getElementById('supportModal');
            if (!modal) return;
            
            const existing = modal.querySelector('.support-notification');
            if (existing) existing.remove();
            
            const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
            const notification = document.createElement('div');
            notification.className = `support-notification ${bgColor} text-white px-4 py-3 rounded-xl mb-4 text-sm`;
            notification.innerHTML = `<strong>${title}</strong><br>${message}`;
            
            const form = document.getElementById('supportForm');
            if (form) {
                form.insertBefore(notification, form.firstChild);
                setTimeout(() => notification.remove(), 5000);
            }
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('supportModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeSupportModal();
                });
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('supportModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        closeSupportModal();
                    }
                }
            });
            
            const form = document.getElementById('supportForm');
            if (form) {
                // Guardar datos mientras se escribe
                const subjectInput = form.querySelector('[name="subject"]');
                const messageInput = form.querySelector('[name="message"]');
                const estadoRadios = form.querySelectorAll('input[name="ticket_estado"]');
                
                if (subjectInput) {
                    subjectInput.addEventListener('input', saveSupportFormData);
                }
                if (messageInput) {
                    messageInput.addEventListener('input', saveSupportFormData);
                }
                estadoRadios.forEach(radio => {
                    radio.addEventListener('change', saveSupportFormData);
                });
                
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const asunto = this.querySelector('[name="subject"]').value.trim();
                    const mensaje = this.querySelector('[name="message"]').value.trim();
                    
                    if (!asunto || !mensaje) {
                        showSupportNotification('Error', 'Por favor completa el asunto y mensaje', 'error');
                        return;
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enviando...
                    `;
                    
                    try {
                        const formData = new FormData();
                        formData.append('name', 'Web Solutions');
                        formData.append('email', 'websolutionscrnow@gmail.com');
                        formData.append('website', 'https://websolutions.work/walee-dashboard');
                        formData.append('asunto', asunto);
                        formData.append('mensaje', mensaje);
                        
                        // Agregar campo de estado (solo uno a la vez)
                        const estadoRadio = document.querySelector('input[name="ticket_estado"]:checked');
                        if (estadoRadio) {
                            const estado = estadoRadio.value;
                            if (estado === 'urgente') {
                                formData.append('urgente', '1');
                            } else if (estado === 'prioritario') {
                                formData.append('prioritario', '1');
                            } else if (estado === 'a_discutir') {
                                formData.append('a_discutir', '1');
                            }
                        }
                        
                        const fileInput = document.getElementById('supportFile');
                        if (fileInput && fileInput.files && fileInput.files.length > 0) {
                            Array.from(fileInput.files).forEach((file, index) => {
                                formData.append(`archivos[${index}]`, file);
                            });
                        }
                        
                        const response = await fetch('{{ route("walee.tickets.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfTokenSupport,
                            },
                            body: formData,
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            const estadoRadio = document.querySelector('input[name="ticket_estado"]:checked');
                            let message = 'Tu mensaje ha sido recibido. Te responderemos pronto.';
                            
                            if (estadoRadio) {
                                const estado = estadoRadio.value;
                                if (estado === 'urgente') {
                                    message = 'Tu mensaje urgente ha sido recibido. Te responderemos lo antes posible.';
                                } else if (estado === 'prioritario') {
                                    message = 'Tu mensaje prioritario ha sido recibido. Te responderemos pronto.';
                                } else if (estado === 'a_discutir') {
                                    message = 'Tu mensaje ha sido recibido y será discutido. Te responderemos pronto.';
                                }
                            }
                            
                            showSupportNotification('¡Enviado!', message, 'success');
                            // Limpiar formulario y datos guardados después de enviar exitosamente
                            clearSupportFormData();
                            
                            setTimeout(() => closeSupportModal(), 2000);
                        } else {
                            showSupportNotification('Error', data.message || 'No se pudo enviar el mensaje', 'error');
                        }
                    } catch (error) {
                        showSupportNotification('Error', 'Error de conexión: ' + error.message, 'error');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
        });
    }
    
    // Scroll functions
    if (typeof scrollToTop === 'undefined') {
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
    
    if (typeof scrollToBottom === 'undefined') {
        function scrollToBottom() {
            window.scrollTo({
                top: document.documentElement.scrollHeight,
                behavior: 'smooth'
            });
        }
    }
</script>

