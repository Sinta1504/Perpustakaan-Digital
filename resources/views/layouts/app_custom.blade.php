<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'E-LIB - Dashboard' }}</title>
    
    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- External Resources --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow-x: hidden;
        }
        [x-cloak] { display: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }

        .animate-fade-in-down {
            animation: fadeInDown 0.5s ease-out;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    {{-- 1. SIDEBAR --}}
    <aside class="w-64 h-screen bg-slate-900 text-slate-300 fixed left-0 top-0 flex flex-col shadow-2xl z-50">
        <div class="p-8">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <span class="text-3xl transition-transform group-hover:scale-110">📚</span>
                <h1 class="text-xl font-black text-white tracking-tighter uppercase italic">E-LIB</h1>
            </a>
        </div>

        <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Menu Utama</p>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">🏠</span> Beranda
            </a>
            
            <a href="{{ route('katalog') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('katalog') || request()->routeIs('books.show') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📖</span> Katalog
            </a>

            @auth
                @if(auth()->user()->role !== 'admin')
                <a href="{{ route('pinjaman') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('pinjaman') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">⏳</span> Pinjaman Saya
                </a>
                @endif

                {{-- PERUBAHAN DI SINI: DINAMIS SESUAI ROLE --}}
                <a href="{{ route('contact.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('contact.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">📞</span> 
                    <span>
                        @if(auth()->user()->role === 'admin')
                            Konfigurasi Kontak
                        @else
                            Hubungi Kami
                        @endif
                    </span>
                </a>

                @if(auth()->user()->role === 'admin')
                <div class="pt-6 mt-6 border-t border-slate-800">
                    <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Administrator</p>
                    
                    <a href="{{ route('admin.inventory') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('admin.inventory') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-lg">📊</span> Inventori
                    </a>

                    <a href="{{ route('admin.loans') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('admin.loans') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-lg">🛡️</span> Monitoring
                    </a>

                    <a href="{{ route('admin.support') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('admin.support') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-lg">🎧</span> Layanan Pengguna
                    </a>

                    <a href="{{ route('admin.feedback.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('admin.feedback.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                        <span class="text-lg">💬</span> Suara Peminjam
                    </a>
                </div>
                @endif
            @endauth
        </nav>

        <div class="p-4 bg-slate-950/50">
            @auth
                <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-2xl mb-3 border border-white/5">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center font-bold text-white uppercase shadow-inner">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 truncate uppercase tracking-widest font-semibold">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-[10px] font-black text-slate-500 hover:text-red-400 py-3 transition-all tracking-[0.2em] border border-slate-800 rounded-xl hover:bg-red-500/5">
                        KELUAR APLIKASI
                    </button>
                </form>
            @endauth
        </div>
    </aside>

    {{-- 2. MAIN CONTENT AREA --}}
    <main class="flex-1 ml-64 min-h-screen flex flex-col">
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200 py-4 px-10 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-tight">
                    {{ now()->translatedFormat('l, d F Y') }}
                </h2>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="{{ route('katalog') }}" class="text-[10px] font-black text-slate-400 hover:text-blue-600 transition-colors uppercase tracking-widest">Cari Buku</a>
                <span class="text-[10px] font-black bg-green-100 text-green-700 px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">Server Online</span>
            </div>
        </header>

        <div class="p-10 flex-1">
            @if(session('success'))
                <div class="mb-8 p-5 bg-green-50 border border-green-100 text-green-700 rounded-[2rem] font-bold text-sm flex items-center gap-4 shadow-sm animate-fade-in-down">
                    <span class="bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center">✓</span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-5 bg-red-50 border border-red-100 text-red-700 rounded-[2rem] font-bold text-sm flex items-center gap-4 shadow-sm animate-fade-in-down">
                    <span class="bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center">✕</span>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="py-6 px-10 border-t border-slate-200 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} E-LIB DIGITAL LIBRARY SYSTEM
            </p>
        </footer>
    </main>
</body>
</html>