<nav x-data="{ open: false }" class="flex flex-col h-screen bg-[#111827] text-slate-400 w-64 fixed left-0 top-0 z-[999]">
    <div class="p-6 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-900/20">
                <i class="fas fa-book-open text-white text-xs"></i>
            </div>
            <span class="text-white font-black text-xl tracking-tighter">E-LIB</span>
        </div>
    </div>

    <div class="px-4 space-y-2 flex-1 overflow-y-auto">
        <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">Menu Utama</p>
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'hover:text-white hover:bg-slate-800/50' }}">
            <i class="fas fa-home w-5"></i>
            <span class="font-bold text-sm">Beranda</span>
        </a>

        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-6 py-4 rounded-2xl transition-all {{ request()->routeIs('profile.edit') ? 'bg-blue-600 text-white shadow-lg' : 'hover:text-white hover:bg-slate-800/50' }}">
            <i class="fas fa-id-card w-5"></i>
            <span class="font-bold text-sm">Profil Akun</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-6 py-4 rounded-2xl hover:text-white hover:bg-slate-800/50 transition-all">
            <i class="fas fa-th-large w-5"></i>
            <span class="font-bold text-sm">Katalog</span>
        </a>

        @if(auth()->user()->role === 'admin')
            <div class="pt-6 mt-6 border-t border-slate-800">
                <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">Administrator</p>
                <a href="{{ route('admin.inventory') }}" class="flex items-center gap-3 px-6 py-4 rounded-2xl hover:text-white hover:bg-slate-800/50 transition-all">
                    <i class="fas fa-boxes w-5"></i>
                    <span class="font-bold text-sm">Inventori</span>
                </a>
            </div>
        @endif
    </div>

    <div class="p-4 border-t border-slate-800 bg-[#0f172a] relative z-[999]">
    <a href="/profile" class="block w-full pointer-events-auto cursor-pointer">
        <div class="flex items-center gap-3 p-3 rounded-[1.5rem] bg-slate-900 hover:bg-blue-600/20 border border-slate-800 hover:border-blue-500/50 transition-all">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black shadow-lg uppercase">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            
            <div class="overflow-hidden flex-1 text-left">
                <p class="text-white font-black text-xs truncate">{{ Auth::user()->name }}</p>
                <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">{{ Auth::user()->role ?? 'User' }}</p>
            </div>
            <i class="fas fa-chevron-right text-[10px] text-slate-700"></i>
        </div>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-2">
        @csrf
        <button type="submit" class="w-full text-center py-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-600 hover:text-red-500 transition-all">
            Keluar Aplikasi
        </button>
    </form>
</div>