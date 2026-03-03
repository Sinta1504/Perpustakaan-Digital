@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto">
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">Katalog Buku</h2>
        <p class="text-slate-500 font-medium text-sm">Menampilkan semua koleksi buku tersedia.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($books as $book)
        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
            <div class="relative mb-6">
                <div class="aspect-[3/4] rounded-[2rem] overflow-hidden bg-slate-100">
                    {{-- PERBAIKAN: Logika gambar agar support URL API dan file Lokal storage --}}
                    <img src="{{ Str::startsWith($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover) }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            </div>

            <div class="space-y-1 mb-6">
                <h3 class="font-black text-slate-900 uppercase italic text-sm line-clamp-1">{{ $book->judul }}</h3>
                <p class="text-[11px] text-slate-400 font-bold uppercase italic">Oleh: {{ $book->penulis }}</p>
                <div class="pt-2">
                    <span class="text-[10px] font-black {{ $book->stok > 0 ? 'text-emerald-500' : 'text-rose-500' }} uppercase italic bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">
                        Stok: {{ $book->stok }}
                    </span>
                </div>
            </div>

            <div class="space-y-2">
                {{-- PERBAIKAN: Mengganti form direct store menjadi tombol Detail agar User bisa lihat Sinopsis & Denda --}}
                @if($book->stok > 0)
                    <a href="{{ route('loans.create', $book->id) }}" 
                       class="block w-full py-3 bg-slate-900 text-center text-white rounded-xl font-black text-[10px] uppercase italic tracking-widest hover:bg-blue-600 transition-all">
                        Detail & Pinjam
                    </a>
                @else
                    <button disabled class="w-full py-3 bg-slate-200 text-slate-400 rounded-xl font-black text-[10px] uppercase italic tracking-widest cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif

                {{-- FITUR ADMIN: Edit dan Hapus tetap tampil di bawah tombol pinjam --}}
                @if(auth()->user() && auth()->user()->role === 'admin')
                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-50">
                    <a href="{{ route('books.edit', $book->id) }}" class="flex items-center justify-center py-2 bg-amber-50 text-amber-600 rounded-xl font-black text-[9px] uppercase italic border border-amber-100 hover:bg-amber-500 hover:text-white transition-all">
                        Edit
                    </a>
                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 bg-rose-50 text-rose-600 rounded-xl font-black text-[9px] uppercase italic border border-rose-100 hover:bg-rose-500 hover:text-white transition-all">
                            Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection