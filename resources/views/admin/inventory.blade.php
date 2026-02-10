@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-8">
    
    {{-- BARIS 1: RINGKASAN STATISTIK (Buku Rusak & Aktivitas) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-red-50 p-6 rounded-[2rem] border border-red-100 shadow-sm">
            <h4 class="text-red-600 font-black text-xs uppercase tracking-widest mb-2">Buku Tidak Layak Pakai</h4>
            <div class="flex items-end gap-2">
                <span class="text-4xl font-black text-slate-900">{{ $brokenBooksCount }}</span>
                <span class="text-slate-500 text-sm mb-1 font-bold">Eksemplar</span>
            </div>
        </div>

        <div class="bg-blue-50 p-6 rounded-[2rem] border border-blue-100 shadow-sm">
            <h4 class="text-blue-600 font-black text-xs uppercase tracking-widest mb-2">Peminjaman Aktif</h4>
            <div class="flex items-end gap-2">
                <span class="text-4xl font-black text-slate-900">{{ $activeLoans->count() }}</span>
                <span class="text-slate-500 text-sm mb-1 font-bold">Buku di Luar</span>
            </div>
        </div>

        <div class="bg-amber-50 p-6 rounded-[2rem] border border-amber-100 shadow-sm">
            <h4 class="text-amber-600 font-black text-xs uppercase tracking-widest mb-2">Akun Terancam Nonaktif</h4>
            <div class="flex items-end gap-2">
                <span class="text-4xl font-black text-slate-900">{{ $inactiveUsers->count() }}</span>
                <span class="text-slate-500 text-sm mb-1 font-bold">User Pasif</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        {{-- SEKSI: BUKU PALING SERING DIPINJAM --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-50">
            <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">🔥 Buku Terpopuler</h3>
            <div class="space-y-4">
                @foreach($topBooks as $top)
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-xl font-bold">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-900 text-sm">{{ $top->judul }}</h5>
                            <p class="text-[10px] text-slate-500 uppercase font-bold">{{ $top->penulis }}</p>
                        </div>
                    </div>
                    <span class="text-blue-600 font-black text-sm">{{ $top->loans_count }}x Dipinjam</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- SEKSI: DAFTAR PINJAMAN BELUM KEMBALI --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-slate-50">
            <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-2">⏳ Belum Dikembalikan</h3>
            <div class="space-y-4">
                @forelse($activeLoans as $loan)
                <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center text-white text-xs font-bold uppercase">
                            {{ substr($loan->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-900 text-sm">{{ $loan->user->name }}</h5>
                            <p class="text-[10px] text-blue-500 font-black">{{ $loan->book->judul }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 font-bold uppercase">Tgl Pinjam:</p>
                        <p class="text-xs font-black text-slate-700">{{ $loan->created_at->format('d/m/y') }}</p>
                    </div>
                </div>
                @empty
                <p class="text-center py-10 text-slate-400 italic">Semua buku sudah kembali!</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- BARIS 3: AKUN TIDAK AKTIF (> 5 BULAN) --}}
    <div class="mt-10 bg-slate-900 p-10 rounded-[3rem] text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h3 class="text-2xl font-black tracking-tighter italic uppercase">Manajemen Akun Pasif</h3>
                <p class="text-slate-400 text-sm font-medium">Akun yang tidak meminjam lebih dari 5 bulan.</p>
            </div>
            <button class="bg-red-600 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-700 shadow-xl shadow-red-900/20">
                ⚠️ Nonaktifkan Semua Akun Pasif
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($inactiveUsers as $user)
            <div class="bg-slate-800 p-6 rounded-3xl border border-slate-700 flex justify-between items-center group">
                <div>
                    <h5 class="font-bold text-slate-100">{{ $user->name }}</h5>
                    <p class="text-[10px] text-slate-500 uppercase font-black italic">Email: {{ $user->email }}</p>
                </div>
                <div class="bg-red-500/10 text-red-500 px-3 py-1 rounded-lg text-[9px] font-black uppercase">
                    Nonaktifkan
                </div>
            </div>
            @empty
            <p class="col-span-full text-center text-slate-500 italic py-10">Tidak ada akun pasif saat ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection