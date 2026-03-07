@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter">Katalog Buku</h2>
            <p class="text-slate-500 italic">Menampilkan semua koleksi buku tersedia.</p>
        </div>
        
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase italic tracking-widest shadow-lg shadow-blue-100 transition flex items-center gap-2">
                + Tambah Buku Baru
            </a>
        @endif
    </div>

    {{-- Grid Katalog --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($books as $book)
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 group">
                
                {{-- Bagian Visual Cover --}}
                <div class="relative aspect-[3/4] overflow-hidden bg-slate-100 p-4">
                    {{-- KODE PENENTU GAMBAR: Menggunakan asset storage --}}
                    <img src="{{ asset('storage/' . $book->cover) }}" 
                         alt="{{ $book->judul }}" 
                         class="w-full h-full object-cover rounded-[2rem] shadow-md group-hover:scale-105 transition-transform duration-500"
                         onerror="this.onerror=null;this.src='https://placehold.co/400x600?text=Cover+Tidak+Ditemukan';">
                    
                    {{-- Badge Kategori --}}
                    <div class="absolute top-6 left-6">
                        <span class="bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[9px] font-black text-blue-600 uppercase italic">
                            {{ $book->kategori ?? 'Novel' }}
                        </span>
                    </div>
                </div>

                <div class="p-8">
                    <h3 class="font-black text-slate-900 leading-tight mb-1 uppercase italic truncate">{{ $book->judul }}</h3>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-4 italic">Oleh: {{ $book->penulis }}</p>
                    
                    <div class="mb-6">
                        <span class="text-[10px] font-black px-3 py-1 rounded-full {{ $book->stok > 0 ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                            STOK: {{ $book->stok }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2">
                        {{-- Tombol Detail & Pinjam --}}
                        <a href="{{ route('loans.create', $book->id) }}" class="w-full text-center py-4 rounded-2xl bg-slate-900 text-white font-black text-[10px] uppercase italic tracking-[0.2em] hover:bg-blue-600 transition-colors shadow-xl">
                            Detail & Pinjam
                        </a>

                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div class="flex gap-2 mt-2">
                                <a href="{{ route('books.edit', $book->id) }}" class="flex-1 text-center py-2 rounded-xl bg-amber-50 text-amber-600 font-black text-[9px] uppercase italic hover:bg-amber-100">
                                    Edit
                                </a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf @method('DELETE')
                                    <button class="w-full py-2 rounded-xl bg-red-50 text-red-600 font-black text-[9px] uppercase italic hover:bg-red-100">
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
                <p class="text-slate-400 font-black uppercase italic tracking-widest">Belum ada koleksi buku.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection