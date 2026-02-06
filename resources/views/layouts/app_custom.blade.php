<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'E-LIB - Dashboard' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <aside class="w-64 h-screen bg-slate-900 text-slate-300 fixed left-0 top-0 flex flex-col shadow-2xl z-50">
        <div class="p-8">
            <div class="flex items-center gap-3">
                <span class="text-3xl">📚</span>
                <h1 class="text-xl font-black text-white tracking-tighter">E-LIB</h1>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Menu Utama</p>
            
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->is('/') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">🏠</span> Beranda
            </a>
            
            <a href="{{ route('katalog') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->is('katalog*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">📖</span> Katalog
            </a>

            @auth
            <a href="{{ route('pinjaman') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->is('pinjaman*') && auth()->user()->role !== 'admin' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <span class="text-lg">⏳</span> Pinjaman Saya
            </a>

            @if(auth()->user()->role === 'admin')
            <div class="pt-6 mt-6 border-t border-slate-800">
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Administrator</p>
                
                <a href="{{ route('admin.inventory') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->is('admin/inventori*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">📊</span> Inventori
                </a>

                <a href="{{ route('admin.loans') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('admin.loans') || request()->is('pinjaman*') ? 'bg-red-600 text-white shadow-lg shadow-red-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                    <span class="text-lg">🛡️</span> Panel Admin
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
                    <button type="submit" class="w-full text-[10px] font-black text-slate-500 hover:text-red-400 py-2 transition-all tracking-[0.2em]">
                        KELUAR APLIKASI
                    </button>
                </form>
            @else
                <div class="flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white text-center py-3 rounded-xl font-bold shadow-lg shadow-blue-900/20 hover:bg-blue-700 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="block w-full bg-slate-800 text-slate-300 text-center py-3 rounded-xl font-bold hover:bg-slate-700 hover:text-white transition border border-slate-700">
                        Daftar Baru
                    </a>
                </div>
            @endauth
        </div>
    </aside>

    <main class="flex-1 ml-64 min-h-screen">
        <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200 py-4 px-10 flex justify-between items-center">
            <h2 class="text-sm font-bold text-slate-500 uppercase tracking-tight">
                {{ now()->translatedFormat('l, d F Y') }}
            </h2>
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black bg-green-100 text-green-700 px-3 py-1 rounded-full uppercase tracking-widest">Sistem Aktif</span>
            </div>
        </header>

        <div class="p-10">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl font-bold text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl font-bold text-sm">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>