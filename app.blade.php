<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TripNova') | AI Travel Planner</title>
    <meta name="description" content="Plan your dream adventure with TripNova, the premium AI-driven travel planner.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>






    <style>
        /* ══════════════════════════════════════════
           GLOBAL BACKGROUND — Pakistan Mountains
        ══════════════════════════════════════════ */
      /* ══════════════════════════════════════════
   GLOBAL GREEN THEME
══════════════════════════════════════════ */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Inter', sans-serif;
    color: #0f172a;
    overflow-x: hidden;
    position: relative;
    min-height: 100vh;

    background-color: #d1fae5;
    background-image:
        radial-gradient(circle at top left, #a7f3d0 0%, transparent 40%),
        radial-gradient(circle at bottom right, #6ee7b7 0%, transparent 40%),
        linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%);
    background-attachment: fixed;
    background-repeat: no-repeat;
}

body::before {
    content: '';
    position: fixed;
    inset: 0;
    background: transparent;
    z-index: 0;
    pointer-events: none;
}

body > * {
    position: relative;
    z-index: 1;
}

h1, h2, h3, h4, h5, h6, .font-display {
    font-family: 'Outfit', sans-serif;
}

/* ── Glass panels ── */
.glass-panel {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1px solid rgba(16, 185, 129, 0.15);
    box-shadow: 0 10px 30px rgba(5, 150, 105, 0.12);
}

.glass-panel-glow {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(16, 185, 129, 0.30);
    box-shadow: 0 8px 40px rgba(5, 150, 105, 0.18);
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

[x-cloak] {
    display: none !important;
}

/* FORCE LOGIN PAGE THEME */

body{
    background-image:url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1920&q=85&auto=format&fit=crop') !important;
    background-size:cover !important;
    background-position:center !important;
    background-attachment:fixed !important;
}

body::before{
    content:'' !important;
    position:fixed !important;
    inset:0 !important;
    background:rgba(255,255,255,.75) !important;
    z-index:0 !important;
}

.bg-\[\#0a0f1e\]{
    background:transparent !important;
}

.hero-bg{
    background:transparent !important;
}

.hero-bg::before{
    opacity:.9 !important;
}

.hero-bg::after{
    background:rgba(255,255,255,.55) !important;
}

/* Cards */
.g-card,
.trip-card,
.stat-card,
.sidebar-card{
    background:rgba(255,255,255,.92) !important;
    border:1px solid rgba(16,185,129,.15) !important;
    color:#0f172a !important;
}

/* Force readable text */
.text-white{
    color:#0f172a !important;
}

.text-slate-300,
.text-slate-400,
.text-slate-500{
    color:#475569 !important;
}
    </style>






    @yield('styles')
</head>
<body class="h-full flex flex-col" x-data="{ lang: 'en', toggleLang() { this.lang = this.lang === 'en' ? 'ur' : 'en' } }">

    <header class="glass-panel sticky top-0 z-40 w-full border-b border-white/60 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-400 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-sky-400/30 transform group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-plane-departure text-lg"></i>
                        </div>
                        <span class="text-2xl font-black font-display tracking-tight text-slate-800 group-hover:text-sky-600 transition-colors">TripNova</span>
                    </a>
                </div>

                <nav class="hidden md:flex space-x-8 text-sm font-semibold tracking-wide">
                    <a href="{{ route('destinations.index') }}" class="text-slate-700 hover:text-sky-600 transition duration-200 py-2 flex items-center space-x-2">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span x-text="lang === 'en' ? 'Explorer' : 'سیاحتی مقامات'">Explorer</span>
                    </a>
                    <a href="{{ route('planner.form') }}" class="text-slate-700 hover:text-sky-600 transition duration-200 py-2 flex items-center space-x-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-sky-500"></i>
                        <span x-text="lang === 'en' ? 'AI Planner' : 'اے آئی پلانر'">AI Planner</span>
                    </a>
                    <a href="{{ route('companions.index') }}" class="text-slate-700 hover:text-sky-600 transition duration-200 py-2 flex items-center space-x-2">
                        <i class="fa-solid fa-users"></i>
                        <span x-text="lang === 'en' ? 'Companion Finder' : 'سفری ساتھی'">Companion Finder</span>
                    </a>
                </nav>

                <div class="flex items-center space-x-4">
                    <button @click="toggleLang()" class="px-3 py-1.5 rounded-lg bg-sky-50 border border-sky-100 text-xs font-bold text-sky-700 hover:bg-sky-100 transition-colors flex items-center space-x-1.5">
                        <i class="fa-solid fa-language text-sky-500"></i>
                        <span x-text="lang === 'en' ? 'اردو' : 'English'">اردو</span>
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-sky-200 hover:bg-sky-50 rounded-xl transition duration-200 space-x-2 shadow-sm">
                            <i class="fa-solid fa-circle-user text-sky-500 text-base"></i>
                            <span x-text="lang === 'en' ? 'Dashboard' : 'ڈیش بورڈ'">Dashboard</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition duration-200" title="Logout">
                                <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-700 hover:text-sky-600 text-sm font-bold tracking-wide transition px-2 py-1" x-text="lang === 'en' ? 'Login' : 'لاگ ان'">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    @if(session('success') || session('info') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition x-cloak>
        <div class="flex items-center justify-between px-4 py-3 rounded-xl border {{ session('success') ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : (session('error') ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-sky-50 border-sky-200 text-sky-700') }}">
            <div class="flex items-center space-x-3">
                <i class="fa-solid {{ session('success') ? 'fa-circle-check' : 'fa-circle-info' }} text-lg"></i>
                <p class="text-sm font-semibold tracking-wide">{{ session('success') ?? session('info') ?? session('error') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
    @endif

    <main class="flex-grow relative z-10">
        @yield('content')
    </main>

    <footer class="glass-panel border-t border-white/60 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4 col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-sky-400 to-blue-500 flex items-center justify-center text-white">
                            <i class="fa-solid fa-plane-departure text-sm"></i>
                        </div>
                        <span class="text-xl font-bold font-display tracking-tight text-slate-800">TripNova</span>
                    </div>
                    <p class="text-sm text-slate-600 max-w-sm">Crafting customized, budget-optimized journeys inside Pakistan using AI-powered travel intelligence.</p>
                </div>
            </div>
            <div class="border-t border-slate-200 mt-8 pt-8 text-xs text-slate-500 text-center">
                &copy; 2026 TripNova AI
            </div>
        </div>
    </footer>

    @auth
    <!-- Floating AI Chat -->
    <div class="fixed bottom-6 right-6 z-50" x-data="chatbot()" x-init="init()">
        <button @click="open = !open" class="w-14 h-14 rounded-full bg-gradient-to-tr from-sky-400 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-sky-400/40 hover:scale-110 active:scale-95 transition transform duration-300 border border-white/40">
            <i class="fa-solid fa-comments text-2xl" x-show="!open"></i>
            <i class="fa-solid fa-xmark text-2xl" x-show="open" x-cloak></i>
        </button>
        <div x-show="open" x-cloak x-transition class="absolute bottom-16 right-0 w-80 sm:w-96 h-[460px] glass-panel-glow rounded-2xl flex flex-col overflow-hidden shadow-2xl border border-sky-200">
            <div class="px-5 py-4 bg-gradient-to-r from-sky-500 to-blue-600 flex items-center space-x-3 text-white">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center"><i class="fa-solid fa-robot"></i></div>
                <div>
                    <h4 class="text-sm font-bold tracking-wide">Groq AI Assistant</h4>
                    <p class="text-xs text-sky-100 flex items-center space-x-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping inline-block"></span>
                        <span>Online</span>
                    </p>
                </div>
            </div>
            <div id="chat-body" class="flex-grow p-4 overflow-y-auto space-y-3 scrollbar-hide text-sm bg-white/60">
                <template x-for="msg in messages">
                    <div class="flex" :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[80%] rounded-2xl px-4 py-2.5 shadow-sm" :class="msg.sender === 'user' ? 'bg-sky-500 text-white rounded-br-none' : 'bg-white text-slate-800 border border-sky-100 rounded-bl-none'">
                            <p x-text="msg.text" class="leading-relaxed whitespace-pre-wrap"></p>
                        </div>
                    </div>
                </template>
                <div class="flex justify-start" x-show="loading" x-cloak>
                    <div class="bg-white border border-sky-100 rounded-2xl rounded-bl-none px-4 py-2.5 flex items-center space-x-1 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-sky-300 animate-bounce" style="animation-delay:0.1s"></span>
                        <span class="w-2 h-2 rounded-full bg-sky-300 animate-bounce" style="animation-delay:0.2s"></span>
                        <span class="w-2 h-2 rounded-full bg-sky-300 animate-bounce" style="animation-delay:0.3s"></span>
                    </div>
                </div>
            </div>
            <form @submit.prevent="sendMessage()" class="p-3 bg-white border-t border-sky-100 flex items-center space-x-2">
                <input type="text" x-model="input" placeholder="Ask about destinations..." class="flex-grow bg-slate-50 border border-sky-100 rounded-xl px-4 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-400 transition-shadow">
                <button type="submit" class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition shadow-md" :disabled="loading">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatbot', () => ({
                open: false, messages: [], input: '', loading: false,
                init() { this.messages = [{ sender: 'ai', text: 'Salam! I am your AI Assistant powered by Groq. Ask me anything about traveling in Pakistan!' }]; },
                async sendMessage() {
                    if (!this.input.trim() || this.loading) return;
                    let userMsg = this.input.trim();
                    this.messages.push({ sender: 'user', text: userMsg });
                    this.input = ''; this.loading = true; this.scrollToBottom();
                    try {
                        let response = await fetch('/chat', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ message: userMsg }) });
                        let data = await response.json();
                        this.messages.push({ sender: 'ai', text: data.reply });
                    } catch (e) { this.messages.push({ sender: 'ai', text: 'Sorry, connection error.' }); }
                    this.loading = false; this.scrollToBottom();
                },
                scrollToBottom() { setTimeout(() => { let c = document.getElementById('chat-body'); if(c) c.scrollTop = c.scrollHeight; }, 50); }
            }));
        });
    </script>
    @endauth

    @yield('scripts')
</body>
</html>