@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-900">Katalog Buku</h2>
            <p class="text-slate-500">Jelajahi koleksi buku digital terbaru kami.</p>
        </div>
        
        {{-- Tombol Tambah Buku: HANYA UNTUK ADMIN --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 transition flex items-center gap-2">
                <span class="text-xl">+</span> Tambah Buku Baru
            </a>
        @endif
    </div>

    <div class="mb-10">
        <form action="{{ route('katalog') }}" method="GET" class="relative max-w-xl">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari judul, penulis, atau kategori..." 
                class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border-none shadow-sm focus:ring-4 focus:ring-blue-100 transition-all outline-none">
            <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400">
                🔍
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($books as $book)
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-slate-100 group">
                
                {{-- BAGIAN GAMBAR YANG SUDAH DIPERBAIKI --}}
                <div class="relative aspect-[3/4] overflow-hidden bg-slate-200">
                    @php
                        // Cek apakah menggunakan cover_url atau cover biasa
                        $urlGambar = $book->cover_url ?? $book->cover;
                    @endphp

                    @if($urlGambar)
                        <img src="{{ \Illuminate\Support\Str::startsWith($urlGambar, 'http') ? $urlGambar : asset('storage/' . $urlGambar) }}" 
                             alt="{{ $book->judul }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                             onerror="this.onerror=null;this.src='https://placehold.co/400x600?text=Cek+Koneksi+Internet';">
                    @else
                        <div class="flex items-center justify-center h-full bg-slate-200">
                             <span class="text-slate-400 italic text-xs font-bold uppercase tracking-widest text-center px-4">Gambar Tidak Ada</span>
                        </div>
                    @endif
                    
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-black text-blue-600 uppercase">
                            {{ $book->kategori }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="font-bold text-slate-900 leading-tight mb-1 truncate">{{ $book->judul }}</h3>
                    <p class="text-slate-500 text-sm mb-4">Oleh: {{ $book->penulis }}</p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-md {{ $book->stok > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                            Stok: {{ $book->stok }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2">
                        {{-- Tombol Pinjam --}}
                        <a href="{{ route('loans.create', $book->id) }}" class="w-full text-center py-3 rounded-xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition">
                            Pinjam Buku
                        </a>

                        {{-- Tombol Edit & Hapus --}}
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div class="flex gap-2 mt-2 pt-2 border-t border-slate-100">
                                <a href="{{ route('books.edit', $book->id) }}" class="flex-grow text-center py-2 rounded-lg bg-amber-50 text-amber-600 font-bold text-xs hover:bg-amber-100 transition">
                                    Edit
                                </a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="flex-grow" onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full py-2 rounded-lg bg-red-50 text-red-600 font-bold text-xs hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="text-6xl mb-4">📔</div>
                <h3 class="text-xl font-bold text-slate-900">Buku tidak ditemukan</h3>
                <p class="text-slate-500">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection