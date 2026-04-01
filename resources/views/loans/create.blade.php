@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    {{-- Tombol Kembali yang sudah diperbaiki ke arah Dashboard/Beranda --}}
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-blue-600 font-bold mb-8 transition group">
        <span class="text-xl transform group-hover:-translate-x-1 transition">←</span> Kembali ke Beranda
    </a>

    <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100 max-w-5xl mx-auto">
        <div class="flex flex-col lg:flex-row">
            
            {{-- Bagian Kiri: Visual Buku & Identitas --}}
            <div class="lg:w-2/5 bg-slate-50 p-12 flex flex-col items-center justify-center border-r border-slate-100 text-center">
                <div class="relative mb-8">
                    {{-- Logika Gambar agar tidak pecah --}}
                    <img src="{{ Str::startsWith($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover) }}" 
                         alt="{{ $book->judul }}" 
                         class="w-full max-w-[220px] shadow-2xl rounded-2xl transform -rotate-2">
                    <span class="absolute -top-4 -right-4 bg-blue-600 text-white px-4 py-1.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg">
                        {{ $book->kategori }}
                    </span>
                </div>

                <h2 class="text-2xl font-black text-slate-900 uppercase italic leading-tight mb-2">{{ $book->judul }}</h2>
                <p class="text-slate-400 font-bold text-sm uppercase tracking-widest mb-8">Penulis: {{ $book->penulis }}</p>

                <div class="grid grid-cols-2 gap-3 w-full">
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-[9px] text-slate-400 font-black uppercase mb-1">ID Database</p>
                        <p class="text-sm font-black text-slate-700">#{{ $book->id }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-[9px] text-slate-400 font-black uppercase mb-1">Kode Buku</p>
                        <p class="text-sm font-black text-blue-600">LIB-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            {{-- Bagian Kanan: Form Konfirmasi --}}
            <div class="lg:w-3/5 p-10 md:p-16">
                <div class="flex items-center gap-3 mb-8">
                    <span class="text-3xl">📖</span>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 uppercase">Deskripsi Buku</h3>
                        <p class="text-slate-400 text-sm italic">"Kisah luar biasa yang akan memperluas cakrawala berpikir Anda melalui lembaran digital ini."</p>
                    </div>
                </div>

                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                    {{-- Info Tanggal --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="bg-blue-50/50 p-6 rounded-[2rem] border border-blue-100 text-center">
                            <p class="text-[10px] font-black text-blue-600 uppercase mb-2">Mulai Pinjam</p>
                            <p class="text-sm font-bold text-slate-700">{{ now()->format('d M Y') }}</p>
                        </div>
                        <div class="bg-orange-50/50 p-6 rounded-[2rem] border border-orange-100 text-center">
                            <p class="text-[10px] font-black text-orange-600 uppercase mb-2">Tenggat Pengembalian</p>
                            <p class="text-sm font-bold text-slate-700">{{ now()->addDays(7)->format('d M Y') }}</p>
                        </div>
                    </div>

                    {{-- Alert Denda --}}
                    <div class="bg-red-50 p-6 rounded-3xl border border-red-100 flex gap-4 mb-8">
                        <span class="text-xl text-red-500">⚠️</span>
                        <p class="text-xs text-red-700 leading-relaxed">
                            <strong class="uppercase block mb-1">Informasi Keterlambatan</strong>
                            Pengembalian lewat dari tanggal tenggat akan dikenakan denda sebesar <span class="font-black">Rp 2.000 / hari.</span>
                        </p>
                    </div>

                    {{-- Input Data --}}
                    <div class="space-y-6 mb-10">
                        <div>
                            <label class="block text-[10px] font-black text-slate-900 uppercase tracking-widest mb-3 ml-2">Nama Lengkap Peminjam</label>
                            <input type="text" value="{{ auth()->user()->name }}" disabled
                                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 text-slate-500 font-bold shadow-inner cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-900 uppercase tracking-widest mb-3 ml-2">Nomor Identitas (NIM/NIK)</label>
                            <input type="text" name="identitas" placeholder="Masukkan nomor identitas..." required
                                   class="w-full bg-white border-2 border-slate-100 rounded-2xl py-4 px-6 text-slate-700 font-bold shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                        </div>
                    </div>

                    {{-- Tombol Konfirmasi --}}
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-100 transition transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-xs">
                        ✅ Konfirmasi Peminjaman
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection