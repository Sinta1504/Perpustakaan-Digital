@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold mb-8 transition">
        <span class="text-xl">←</span> Kembali ke Katalog
    </a>

    <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100">
        <div class="flex flex-col lg:flex-row">
            
            <div class="lg:w-2/5 bg-slate-100 relative group">
                <img src="{{ $book->cover }}" alt="{{ $book->judul }}" class="w-full h-full object-cover shadow-2xl transform group-hover:scale-105 transition duration-700">
                <div class="absolute top-6 left-6">
                    <span class="bg-blue-600 text-white px-6 py-2 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl">
                        {{ $book->kategori }}
                    </span>
                </div>
            </div>

            <div class="lg:w-3/5 p-10 md:p-16 flex flex-col justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight mb-4">
                        {{ $book->judul }}
                    </h1>
                    <p class="text-xl text-slate-400 font-bold mb-8 uppercase tracking-widest">
                        Karya: <span class="text-slate-600">{{ $book->penulis }}</span>
                    </p>

                    <div class="flex gap-4 mb-10">
                        <div class="bg-slate-50 px-6 py-4 rounded-3xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-400 font-bold uppercase mb-1">Status Stok</p>
                            <p class="text-lg font-black {{ $book->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $book->stok > 0 ? $book->stok . ' Tersedia' : 'Habis' }}
                            </p>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 rounded-3xl border border-slate-100 text-center">
                            <p class="text-xs text-slate-400 font-bold uppercase mb-1">ID Buku</p>
                            <p class="text-lg font-black text-slate-700">#BK-{{ $book->id }}</p>
                        </div>
                    </div>

                    <div class="prose prose-slate max-w-none mb-10">
                        <h3 class="text-lg font-black text-slate-900 mb-3 uppercase tracking-wider">Sinopsis Buku</h3>
                        <p class="text-slate-500 leading-relaxed text-lg">
                            {{ $book->deskripsi ?? 'Belum ada deskripsi untuk buku ini.' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 border-t border-slate-100 pt-10">
                    @if($book->stok > 0)
                        <a href="{{ route('loans.create', $book->id) }}" class="flex-grow bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-100 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                            <span>📖</span> PINJAM BUKU INI
                        </a>
                    @else
                        <button disabled class="flex-grow bg-slate-200 text-slate-400 font-black py-5 rounded-2xl cursor-not-allowed">
                            STOK SEDANG KOSONG
                        </button>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('books.edit', $book->id) }}" class="sm:w-1/3 bg-amber-500 hover:bg-amber-600 text-white font-black py-5 rounded-2xl text-center shadow-lg transition">
                            EDIT DATA
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection