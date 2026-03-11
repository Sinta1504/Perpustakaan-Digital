@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    
    {{-- 1. HEADER & TOMBOL TAMBAH --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter">Inventori Perpustakaan</h2>
            <p class="text-slate-500 italic">Kelola koleksi buku dan pantau stok perpustakaan.</p>
        </div>
        
        <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase italic tracking-widest shadow-xl shadow-blue-100 transition flex items-center gap-2">
            + Tambah Buku Baru
        </a>
    </div>

    {{-- 2. KARTU STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2 italic">Total Koleksi</p>
            <h3 class="text-3xl font-black text-slate-900 italic">{{ $books->count() }} <span class="text-sm font-medium text-slate-400 uppercase">Buku</span></h3>
            <div class="absolute -right-4 -bottom-4 opacity-5 text-slate-900">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21l-8-4.5v-9L12 3l8 4.5v9l-8 4.5z"/></svg>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2 italic">Peminjaman Aktif</p>
            <h3 class="text-3xl font-black text-slate-900 italic">{{ $activeLoans->count() }} <span class="text-sm font-medium text-slate-400 uppercase">Siswa</span></h3>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-2 italic">Buku Rusak</p>
            <h3 class="text-3xl font-black text-slate-900 italic">{{ $brokenBooksCount }} <span class="text-sm font-medium text-slate-400 uppercase">Unit</span></h3>
        </div>
    </div>

    {{-- 3. TABEL INVENTORI --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <h3 class="font-black text-slate-900 uppercase italic tracking-tighter text-xl">Daftar Inventori Buku</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="p-6 text-[10px] font-black uppercase italic text-slate-400 tracking-widest">Cover</th>
                        <th class="p-6 text-[10px] font-black uppercase italic text-slate-400 tracking-widest">Judul & Penulis</th>
                        <th class="p-6 text-[10px] font-black uppercase italic text-slate-400 tracking-widest">Kategori</th>
                        <th class="p-6 text-[10px] font-black uppercase italic text-slate-400 tracking-widest text-center">Stok</th>
                        <th class="p-6 text-[10px] font-black uppercase italic text-slate-400 tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($books as $book)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6">
                                <img src="{{ asset('storage/' . $book->cover) }}" 
                                     class="w-12 h-16 object-cover rounded-xl shadow-md"
                                     onerror="this.src='https://placehold.co/400x600?text=No+Cover'">
                            </td>
                            <td class="p-6">
                                <p class="font-black text-slate-900 uppercase italic text-sm mb-1">{{ $book->judul }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Oleh: {{ $book->penulis }}</p>
                            </td>
                            <td class="p-6">
                                <span class="text-[9px] font-black px-3 py-1 bg-blue-50 text-blue-600 rounded-full uppercase italic">
                                    {{ $book->kategori }}
                                </span>
                            </td>
                            <td class="p-6 text-center">
                                <span class="font-black italic text-sm {{ $book->stok < 3 ? 'text-red-500' : 'text-slate-900' }}">
                                    {{ $book->stok }}
                                </span>
                            </td>
                            <td class="p-6">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('books.edit', $book->id) }}" class="p-3 bg-amber-50 text-amber-600 rounded-2xl hover:bg-amber-100 transition shadow-sm shadow-amber-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button class="p-3 bg-red-50 text-red-600 rounded-2xl hover:bg-red-100 transition shadow-sm shadow-red-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center text-slate-400 font-black uppercase italic tracking-widest">
                                Belum ada data buku tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection