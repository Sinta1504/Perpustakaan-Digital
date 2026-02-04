@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">

    @if(auth()->user()->role === 'admin')
        {{-- ========================================== --}}
        {{-- TAMPILAN KHUSUS ADMIN                      --}}
        {{-- ========================================== --}}
        <div class="mb-10 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black text-slate-900">Dashboard Admin 🛡️</h2>
                <p class="text-slate-500">Ringkasan aktivitas dan statistik perpustakaan Anda.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-100">Tambah Buku</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-blue-600 p-8 rounded-[2rem] text-white shadow-xl shadow-blue-200 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-blue-100 text-sm font-bold uppercase tracking-wider mb-1">Total Koleksi</p>
                    <h4 class="text-5xl font-black">{{ $totalBuku }}</h4>
                </div>
                <span class="absolute -right-4 -bottom-4 text-9xl text-white/10 select-none">📚</span>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-wider mb-1">Total Pinjaman</p>
                    <h4 class="text-5xl font-black text-slate-900">{{ $totalPinjaman }}</h4>
                </div>
                <span class="absolute -right-4 -bottom-4 text-9xl text-slate-50 select-none">📑</span>
            </div>

            <div class="bg-slate-900 p-8 rounded-[2rem] text-white shadow-xl shadow-slate-900/20 flex flex-col justify-center">
                <h5 class="font-bold mb-4">Aksi Cepat:</h5>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.loans') }}" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-xs font-bold transition">Semua Pinjaman</a>
                    <a href="{{ route('katalog') }}" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-xs font-bold transition">Cek Katalog</a>
                </div>
            </div>
        </div>

        <div class="max-w-2xl bg-white p-10 rounded-[2.5rem] shadow-xl border border-slate-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
                    <span class="text-2xl">🏆</span> Peminjam Terpopuler
                </h3>
            </div>
            <div class="space-y-4">
                @forelse($topBorrowers as $user)
                <div class="flex items-center justify-between p-5 bg-slate-50/50 rounded-3xl border border-slate-100 transition hover:scale-[1.02] hover:bg-white">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black">{{ substr($user->name, 0, 1) }}</div>
                        <div>
                            <p class="font-black text-slate-800 tracking-tight">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400 font-medium">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="bg-blue-100 text-blue-600 px-5 py-2 rounded-2xl text-xs font-black">{{ $user->loans_count }} Buku</span>
                </div>
                @empty
                <p class="text-slate-400 italic">Belum ada data.</p>
                @endforelse
            </div>
        </div>

    @else
        {{-- ========================================== --}}
        {{-- TAMPILAN KHUSUS SINTA (USER)               --}}
        {{-- ========================================== --}}
        
        <div class="bg-white shadow-2xl p-10 rounded-[3rem] border border-slate-100 mb-10">
            <h1 class="text-4xl font-black text-slate-900 leading-tight">Halo, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-slate-500 mt-4 text-lg">Selamat datang di E-Lib. Mau baca buku apa hari ini?</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('katalog') }}" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">Buka Katalog</a>
                <a href="{{ route('pinjaman') }}" class="bg-slate-100 text-slate-600 px-8 py-4 rounded-2xl font-bold hover:bg-slate-200 transition">Pinjaman Saya</a>
            </div>
        </div>

        @if($overdueBooks->count() > 0)
        <div class="bg-red-50 border-2 border-red-100 p-8 rounded-[2.5rem] shadow-xl shadow-red-100/50">
            <div class="flex items-center gap-4 mb-6">
                <span class="text-4xl">⚠️</span>
                <div>
                    <h4 class="text-red-800 font-black text-xl">Perhatian!</h4>
                    <p class="text-red-600 font-medium">Kamu memiliki {{ $overdueBooks->count() }} buku yang melewati batas waktu.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($overdueBooks as $loan)
                <div class="bg-white p-4 rounded-2xl flex items-center justify-between border border-red-200">
                    <span class="font-bold text-slate-800">{{ $loan->book->judul }}</span>
                    <a href="{{ route('pinjaman') }}" class="text-xs bg-red-600 text-white px-4 py-2 rounded-xl font-bold uppercase tracking-wider">Kembalikan</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif

</div>
@endsection