@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-5xl mx-auto bg-white rounded-[3rem] shadow-2xl border border-slate-100 overflow-hidden">
        <div class="flex flex-col lg:flex-row">
            
            <div class="lg:w-1/3 bg-slate-50 p-10 flex flex-col items-center text-center border-r border-slate-100">
                <div class="relative mb-6">
                    <img src="{{ $book->cover }}" alt="{{ $book->judul }}" class="w-48 h-64 object-cover rounded-3xl shadow-2xl">
                    <span class="absolute -top-3 -right-3 bg-blue-600 text-white text-[10px] font-black px-4 py-1 rounded-full shadow-lg uppercase tracking-tighter">
                        {{ $book->kategori }}
                    </span>
                </div>
                <h2 class="text-2xl font-black text-slate-900 leading-tight mb-2">{{ $book->judul }}</h2>
                <p class="text-slate-500 font-bold text-sm mb-6">Penulis: {{ $book->penulis }}</p>
                
                <div class="flex gap-2">
                    <div class="bg-white px-4 py-2 rounded-xl border border-slate-200">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">ID Database</p>
                        <p class="text-xs font-black text-slate-700">#{{ $book->id }}</p>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-xl border border-blue-100">
                        <p class="text-[10px] text-blue-400 font-bold uppercase">Kode Buku</p>
                        <p class="text-xs font-black text-blue-600">LIB-000{{ $book->id }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:w-2/3 p-10 md:p-14">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-2xl">📖</span>
                    <h3 class="text-xl font-black text-slate-900">Deskripsi Buku</h3>
                </div>
                
                <p class="text-slate-500 italic leading-relaxed mb-10 text-lg">
                    "{{ Str::limit($book->deskripsi, 200) ?? 'Kisah luar biasa yang akan memperluas cakrawala berpikir Anda melalui lembaran digital ini.' }}"
                </p>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 text-center">
                        <p class="text-[10px] text-blue-600 font-black uppercase tracking-widest mb-1">Mulai Pinjam</p>
                        <p class="text-lg font-black text-slate-900">{{ $tanggalPinjam }}</p>
                    </div>
                    <div class="bg-orange-50/50 p-6 rounded-3xl border border-orange-100 text-center">
                        <p class="text-[10px] text-orange-600 font-black uppercase tracking-widest mb-1">Tenggat Pengembalian</p>
                        <p class="text-lg font-black text-slate-900">{{ $tanggalKembali }}</p>
                    </div>
                </div>

                <div class="bg-red-50 p-4 rounded-2xl border border-red-100 flex gap-4 items-start mb-10">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <h4 class="text-sm font-black text-red-700">Informasi Keterlambatan</h4>
                        <p class="text-xs text-red-600 font-medium">Pengembalian lewat dari tanggal tenggat akan dikenakan denda sebesar <span class="font-black">Rp 2.000 / hari</span>.</p>
                    </div>
                </div>

                <form action="{{ route('loans.store', $book->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-tighter">Nama Lengkap Peminjam</label>
                        <input type="text" name="peminjam_nama" value="{{ auth()->user()->name }}" readonly
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-slate-200 text-slate-500 font-bold focus:ring-0 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-tighter">Nomor Identitas (NIM/NIK)</label>
                        <input type="text" name="identitas" required placeholder="Masukkan nomor identitas..."
                            class="w-full px-6 py-4 rounded-2xl bg-white border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none shadow-sm">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-200 transition transform hover:scale-[1.01] active:scale-95 flex items-center justify-center gap-3">
                        ✅ Konfirmasi Peminjaman
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection