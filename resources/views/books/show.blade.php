@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    {{-- Tombol Kembali --}}
    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold mb-8 transition group">
        <span class="text-xl transform group-hover:-translate-x-1 transition">←</span> Kembali ke Katalog
    </a>

    <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100">
        <div class="flex flex-col lg:flex-row">
            
            {{-- Bagian Cover Buku --}}
            <div class="lg:w-2/5 bg-slate-50 p-12 flex justify-center items-center relative group">
                <img src="{{ Str::startsWith($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover) }}" 
                     alt="{{ $book->judul }}" 
                     class="w-full max-w-[320px] shadow-2xl rounded-2xl rotate-2 group-hover:rotate-0 transition-all duration-500">
                
                {{-- Badge Kategori --}}
                <div class="absolute top-6 left-6">
                    <span class="bg-blue-600 text-white px-6 py-2 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl">
                        {{ $book->kategori }}
                    </span>
                </div>
            </div>

            {{-- Bagian Detail Informasi --}}
            <div class="lg:w-3/5 p-10 md:p-16 flex flex-col justify-between">
                <div>
                    <div class="mb-8">
                        <span class="bg-blue-100 text-blue-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Informasi Buku</span>
                        <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mt-4 italic uppercase">
                            {{ $book->judul }}
                        </h1>
                        <p class="text-xl text-slate-400 font-bold italic uppercase tracking-widest mt-2">
                            Karya: <span class="text-slate-600">{{ $book->penulis }}</span>
                        </p>
                    </div>

                    {{-- Info Badge --}}
                    <div class="flex gap-4 mb-10">
                        <div class="bg-slate-50 px-6 py-4 rounded-3xl border border-slate-100 text-center">
                            <p class="text-[10px] text-slate-400 font-black uppercase mb-1">Status Stok</p>
                            <p class="text-lg font-black {{ $book->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $book->stok > 0 ? $book->stok . ' Tersedia' : 'Habis' }}
                            </p>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 rounded-3xl border border-slate-100 text-center">
                            <p class="text-[10px] text-slate-400 font-black uppercase mb-1">ID Koleksi</p>
                            <p class="text-lg font-black text-slate-700">#BK-{{ $book->id }}</p>
                        </div>
                    </div>

                    {{-- Sinopsis --}}
                    <div class="prose prose-slate max-w-none mb-10">
                        <h3 class="text-xs font-black text-slate-900 mb-3 uppercase tracking-widest">Sinopsis / Deskripsi</h3>
                        <p class="text-slate-500 leading-relaxed text-lg italic">
                            {{ $book->sinopsis ?? $book->deskripsi ?? 'Belum ada deskripsi untuk buku ini.' }}
                        </p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row gap-4 border-t border-slate-100 pt-10">
                    @if($book->stok > 0)
                        <a href="{{ route('loans.create', $book->id) }}" 
                           class="flex-grow bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-full shadow-xl shadow-blue-100 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-xs">
                           <span>📖</span> Konfirmasi Peminjaman
                        </a>
                    @else
                        <button disabled 
                                class="flex-grow bg-slate-200 text-slate-400 font-black py-5 rounded-full cursor-not-allowed uppercase tracking-widest text-xs">
                            Stok Sedang Kosong
                        </button>
                    @endif

                    {{-- Tombol Edit khusus Admin --}}
                    @if(auth()->user() && auth()->user()->role === 'admin')
                        <a href="{{ route('books.edit', $book->id) }}" 
                           class="sm:w-1/3 bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-full text-center shadow-lg transition uppercase tracking-widest text-[10px]">
                            Edit Data Buku
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection