@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    {{-- 1. Header & Fitur Pencarian --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">
                📚 Koleksi Pustaka
            </h2>
            <p class="text-slate-500 font-medium text-sm mt-2">Jelajahi dunia melalui barisan kata dalam buku.</p>
        </div>
        
        <form action="{{ route('books.index') }}" method="GET" class="relative group">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Cari judul atau penulis..." 
                   class="pl-12 pr-6 py-4 bg-white border border-slate-100 rounded-2xl w-full md:w-80 shadow-sm group-hover:shadow-md transition-all outline-none focus:border-blue-500 font-bold italic text-xs uppercase">
            
            <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>
    </div>

    {{-- 2. Grid Koleksi Buku --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
        @forelse($books as $book)
        <div class="bg-white rounded-[3rem] p-6 shadow-sm border border-slate-50 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 group relative overflow-hidden">
            
            {{-- Badge Kategori --}}
            <div class="absolute top-8 left-8 z-10">
                <span class="bg-white/90 backdrop-blur-md text-slate-900 text-[9px] font-black px-4 py-2 rounded-full uppercase italic shadow-sm border border-slate-100">
                    {{ $book->kategori }}
                </span>
            </div>

            {{-- Cover Buku --}}
            <div class="relative mb-6">
                <div class="aspect-[3/4.5] rounded-[2.5rem] overflow-hidden bg-slate-100 shadow-inner relative">
                    <img src="{{ Str::startsWith($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover) }}" 
                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out"
                         onerror="this.src='https://placehold.co/400x600?text=Cover+Buku'">
                    
                    {{-- Hover Sinopsis Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-8">
                        <p class="text-white text-[10px] leading-relaxed font-medium italic line-clamp-3">
                            {{ $book->sinopsis ?? 'Ketuk detail untuk membaca sinopsis lengkap buku ini.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail Info Buku --}}
            <div class="px-2 mb-6">
                <div class="flex justify-between items-start gap-2 mb-2">
                    <h3 class="font-black text-slate-900 uppercase italic text-sm leading-tight line-clamp-2 flex-1">
                        {{ $book->judul }}
                    </h3>
                </div>
                <p class="text-[10px] text-blue-600 font-black uppercase italic tracking-widest mb-4">
                    BY: {{ $book->penulis }}
                </p>
                
                {{-- Status Stok --}}
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 rounded-full {{ $book->stok > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></div>
                    <span class="text-[10px] font-black {{ $book->stok > 0 ? 'text-slate-700' : 'text-rose-500' }} uppercase italic">
                        {{ $book->stok > 0 ? 'Tersedia: ' . $book->stok . ' Buku' : 'Stok Habis' }}
                    </span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="space-y-3">
                @if($book->stok > 0)
                    <a href="{{ route('loans.create', $book->id) }}" 
                       class="flex items-center justify-center gap-3 w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase italic tracking-widest hover:bg-blue-600 shadow-lg shadow-slate-200 hover:shadow-blue-200 transition-all active:scale-95">
                        <span>Pinjam Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <button disabled class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl font-black text-[10px] uppercase italic tracking-widest cursor-not-allowed border border-slate-200">
                        Maaf, Stok Habis
                    </button>
                @endif

                {{-- Fitur Admin (Edit/Hapus) --}}
                @if(auth()->user() && auth()->user()->role === 'admin')
                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-slate-50">
                    <a href="{{ route('books.edit', $book->id) }}" 
                       class="py-3 bg-amber-50 text-amber-600 rounded-xl font-black text-[9px] uppercase italic text-center border border-amber-100 hover:bg-amber-500 hover:text-white transition-all">
                        Ubah Data
                    </a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini dari database?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-rose-50 text-rose-600 rounded-xl font-black text-[9px] uppercase italic border border-rose-100 hover:bg-rose-500 hover:text-white transition-all">
                            Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        {{-- State Pencarian Tidak Ditemukan --}}
        <div class="col-span-full py-20 text-center">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-xl font-black text-slate-900 uppercase italic">Buku tidak ditemukan</h3>
            <p class="text-slate-500 mt-2">Coba gunakan kata kunci lain seperti nama penulis atau kategori.</p>
            <a href="{{ route('books.index') }}" class="inline-block mt-6 text-blue-600 font-bold uppercase italic text-xs border-b-2 border-blue-600 pb-1">Kembali ke semua koleksi</a>
        </div>
        @endforelse
    </div>
</div>
@endsection