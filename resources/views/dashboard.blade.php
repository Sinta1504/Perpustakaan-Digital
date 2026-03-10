@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">

    {{-- 1. Header Dashboard: Banner Sambutan --}}
    <div class="relative overflow-hidden bg-slate-900 rounded-[3rem] p-10 mb-12 text-white shadow-2xl">
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-2">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-slate-400 text-lg font-medium max-w-lg">
                Mau baca buku apa hari ini? Temukan koleksi terbaik untuk menemani waktu luangmu.
            </p>
        </div>
        {{-- Dekorasi Elemen Background --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 blur-[100px] rounded-full -mr-20 -mt-20"></div>
    </div>

    {{-- 2. Bagian Rekomendasi Buku --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                Rekomendasi Buku 📚
            </h2>
            <p class="text-slate-500 font-medium mt-1">
                Buku-buku pilihan yang paling banyak dibaca oleh pengguna lain.
            </p>
        </div>
        
        {{-- PERBAIKAN DI SINI: route('katalog') diubah menjadi route('books.index') --}}
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-sm group">
            Lihat Semua Katalog 
            <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
    </div>

    {{-- 3. Grid Kartu Buku --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($recommendedBooks as $book)
        <div class="group bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-50 transition-all duration-300 hover:-translate-y-3">
            
            {{-- Kontainer Gambar --}}
            <div class="relative overflow-hidden rounded-[2rem] mb-6 aspect-[3/4] bg-slate-100 shadow-inner">
                
                @php
                    $imageSrc = $book->cover_url ?? ( $book->cover ? asset('storage/'.$book->cover) : null );
                @endphp

                <img src="{{ $imageSrc }}" 
                     alt="{{ $book->judul }}"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     onerror="this.src='https://placehold.co/400x600?text={{ urlencode($book->judul) }}'">
                
                {{-- Overlay & Tombol Cepat saat Hover --}}
                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                    <a href="{{ route('books.show', $book->id) }}" class="bg-white text-slate-900 px-6 py-3 rounded-xl font-extrabold text-xs uppercase tracking-widest shadow-xl transform translate-y-8 group-hover:translate-y-0 transition-all duration-500">
                        Pinjam Sekarang
                    </a>
                </div>
            </div>

            {{-- Informasi Buku --}}
            <div class="px-2">
                <h4 class="font-black text-slate-900 uppercase italic tracking-tighter leading-tight mb-1 truncate group-hover:text-blue-600 transition-colors" title="{{ $book->judul }}">
                    {{ $book->judul }}
                </h4>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-[2px] bg-blue-500"></span>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em]">
                        {{ $book->penulis }}
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Footer/Empty State jika tidak ada buku --}}
    @if($recommendedBooks->isEmpty())
    <div class="bg-slate-100 rounded-[3rem] p-20 text-center border-4 border-dashed border-slate-200">
        <p class="text-slate-400 font-black uppercase tracking-widest italic">Belum ada buku rekomendasi hari ini.</p>
    </div>
    @endif
</div>
@endsection