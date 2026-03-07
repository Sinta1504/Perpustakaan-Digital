@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    
    {{-- 1. HEADER & TOMBOL TAMBAH --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter">Inventori Perpustakaan</h2>
            <p class="text-slate-500 italic">Kelola koleksi buku dan pantau feedback peminjam.</p>
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

    {{-- 3. TABEL INVENTORI (Tampilan Utama yang Sempat Hilang) --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-16">
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

    {{-- 4. SUARA PEMINJAM (Review Section) --}}
    <section class="mt-20">
        <div class="flex items-center gap-4 mb-10">
            <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">📣 Suara Peminjam</h2>
            <div class="h-[2px] flex-1 bg-slate-100"></div>
            <span class="bg-blue-600 text-white text-[10px] font-black px-4 py-1 rounded-full uppercase italic tracking-widest">
                {{ $allReviews->count() }} Ulasan
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($allReviews as $rev)
                <div class="bg-white rounded-[3rem] p-8 shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-black italic shadow-lg shadow-blue-100">
                            {{ substr($rev->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 uppercase italic text-sm">{{ $rev->user->name }}</h4>
                            <p class="text-[10px] text-blue-600 font-bold uppercase italic tracking-widest">Buku: {{ $rev->book->judul }}</p>
                        </div>
                    </div>

                    <div class="flex gap-1 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-100' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>

                    <p class="text-slate-600 text-sm italic leading-relaxed font-medium mb-6">"{{ $rev->review }}"</p>

                    <div class="pt-6 border-t border-slate-50 flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">
                            {{ $rev->updated_at->format('d M Y') }}
                        </span>
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-[3rem] p-12 text-center text-slate-400 font-black uppercase italic tracking-widest text-sm">
                    Belum ada ulasan yang masuk.
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection