@extends('layouts.app_custom')

@section('content')
{{-- Load Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mx-auto px-6 py-12">

    {{-- CEK APAKAH YANG LOGIN ADMIN --}}
    @if(auth()->user()->role == 'admin')
        
        {{-- ========================================== --}}
        {{-- TAMPILAN DASHBOARD ADMIN --}}
        {{-- ========================================== --}}
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-800 uppercase italic tracking-tighter leading-none">Panel Manajemen Admin</h2>
            <p class="text-slate-500 font-medium mt-2">Selamat datang kembali, Pengelola Perpustakaan.</p>
        </div>

        {{-- GRID STATISTIK OTOMATIS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {{-- Total Buku --}}
            <div class="bg-blue-600 p-6 rounded-[2rem] text-white shadow-xl shadow-blue-100 transition-transform hover:scale-105">
                <p class="text-xs font-black uppercase opacity-80 mb-1 tracking-widest">Total Koleksi</p>
                <h3 class="text-3xl font-black italic">{{ \App\Models\Book::count() }} <span class="text-sm not-italic opacity-70 font-bold">Buku</span></h3>
            </div>

            {{-- Total Pinjaman --}}
            <div class="bg-slate-900 p-6 rounded-[2rem] text-white shadow-xl shadow-slate-200 transition-transform hover:scale-105">
                <p class="text-xs font-black uppercase opacity-80 mb-1 tracking-widest">Total Pinjaman</p>
                <h3 class="text-3xl font-black italic">{{ \App\Models\Loan::count() }} <span class="text-sm not-italic opacity-70 font-bold">Transaksi</span></h3>
            </div>

            {{-- Member Aktif --}}
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm transition-transform hover:scale-105">
                <p class="text-xs font-black uppercase text-slate-400 mb-1 tracking-widest">Member Aktif</p>
                <h3 class="text-3xl font-black italic text-slate-800">{{ \App\Models\User::where('role', '!=', 'admin')->count() }} <span class="text-sm not-italic opacity-50 font-bold">Orang</span></h3>
            </div>

            {{-- Ulasan Masuk --}}
            <div class="bg-amber-400 p-6 rounded-[2rem] text-slate-900 shadow-xl shadow-amber-100 transition-transform hover:scale-105">
                <p class="text-xs font-black uppercase opacity-80 mb-1 tracking-widest">Ulasan</p>
                <h3 class="text-3xl font-black italic">{{ \App\Models\Feedback::count() }} <span class="text-sm not-italic opacity-70 font-bold">Masuk</span></h3>
            </div>
        </div>

        {{-- ANALISIS VISUAL (GRAFIK & RANKING) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            
            {{-- Grafik Tren Peminjaman (Kiri) --}}
            <div class="lg:col-span-2 bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm">
                <h4 class="font-black text-slate-800 uppercase italic mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-600 rounded-full"></span> 
                    Tren Peminjaman Bulanan
                </h4>
                <div class="h-[350px]">
                    <canvas id="loanChart"></canvas>
                </div>
            </div>

            {{-- Ranking Buku (Kanan) --}}
            <div class="flex flex-col gap-6">
                {{-- Kartu Buku Terpopuler --}}
                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:border-blue-200">
                    <h4 class="font-black text-slate-800 uppercase italic mb-4 text-sm flex items-center gap-2">
                        <span>Buku Terpopuler</span> <span class="text-base">🔥</span>
                    </h4>
                    <div class="space-y-3">
                        @foreach($topBooks as $top)
                        <div class="flex items-center gap-3 p-2 bg-blue-50 rounded-2xl border border-blue-100 transition-all hover:bg-blue-100">
                            <span class="text-xs font-black text-blue-600 ml-2">#{{ $loop->iteration }}</span>
                            <p class="font-bold text-slate-800 text-[11px] uppercase truncate flex-1">{{ $top->judul }}</p>
                            <span class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded-lg font-black">{{ $top->loans_count }}x</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kartu Buku Kurang Diminati --}}
                <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm transition-all hover:border-slate-300">
                    <h4 class="font-black text-slate-400 uppercase italic mb-4 text-sm flex items-center gap-2">
                        <span>Kurang Diminati</span> <span class="text-base">🧊</span>
                    </h4>
                    <div class="space-y-3">
                        @foreach($leastBooks as $least)
                        <div class="flex items-center gap-3 p-2 bg-slate-50 rounded-2xl border border-slate-100 transition-all hover:bg-slate-100">
                            <span class="text-xs font-black text-slate-400 ml-2">#{{ $loop->iteration }}</span>
                            <p class="font-bold text-slate-500 text-[11px] uppercase truncate flex-1">{{ $least->judul }}</p>
                            <span class="text-[10px] bg-slate-200 text-slate-600 px-2 py-1 rounded-lg font-black">{{ $least->loans_count }}x</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ctx = document.getElementById('loanChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labels) !!}, 
                        datasets: [{
                            label: 'Jumlah Pinjaman',
                            data: {!! json_encode($dataPinjaman) !!},
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 4,
                            pointRadius: 6,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#2563eb',
                            pointBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { weight: 'bold' } } },
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                        }
                    }
                });
            });
        </script>

    @else
        
        {{-- ========================================== --}}
        {{-- TAMPILAN DASHBOARD USER --}}
        {{-- ========================================== --}}
        
        {{-- BANNER WELCOME --}}
        <div class="relative overflow-hidden bg-slate-900 rounded-[3rem] p-10 mb-12 text-white shadow-2xl">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-blue-600 rounded-full blur-3xl opacity-20"></div>
            
            <div class="relative z-10">
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-2 italic tracking-tighter">Halo, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-slate-400 text-lg font-medium max-w-lg">Mau baca buku apa hari ini? Temukan koleksi terbaik kami.</p>
            </div>
        </div>

        {{-- SEKSI GRAFIK USER (REVISI BARU) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Card Grafik -->
            <div class="lg:col-span-2 bg-white rounded-[3rem] p-8 shadow-sm border border-slate-50">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-black uppercase italic text-slate-900 text-xs tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span> 
                        📈 Statistik Membacamu
                    </h4>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Aktivitas Terbaru</span>
                </div>
                <div class="h-[200px]">
                    <canvas id="readerChart"></canvas>
                </div>
            </div>

            <!-- Card Info Cepat -->
            <div class="bg-slate-900 rounded-[3rem] p-8 text-white flex flex-col justify-center relative overflow-hidden shadow-xl shadow-slate-200">
                <div class="relative z-10">
                    <h4 class="font-black uppercase italic text-[10px] mb-2 text-blue-400">Status Literasi</h4>
                    <div class="text-5xl font-black mb-2 italic">12</div>
                    <p class="text-[10px] leading-relaxed opacity-70 uppercase font-bold italic">Buku telah kamu selesaikan tahun ini!</p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-white/5 text-8xl rotate-12">📚</div>
            </div>
        </div>

        {{-- JUDUL REKOMENDASI --}}
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">Rekomendasi Buku 📚</h2>
            <a href="{{ route('books.index') }}" class="text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
        </div>

        {{-- GRID BUKU --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @forelse($recommendedBooks as $book)
                <div class="bg-white rounded-[2.5rem] p-5 shadow-sm border border-slate-50 transition-all hover:shadow-xl hover:-translate-y-2 group">
                    <div class="aspect-[3/4] rounded-[1.5rem] overflow-hidden mb-5 shadow-md">
                        <img src="{{ Str::startsWith($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover) }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x600?text=No+Cover';">
                    </div>
                    <div class="px-2">
                        <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest italic mb-1 block truncate">{{ $book->kategori ?? 'Umum' }}</span>
                        <h3 class="font-black text-slate-800 uppercase text-sm leading-tight line-clamp-2 mb-4 h-10 group-hover:text-blue-600 transition-colors">{{ $book->judul }}</h3>
                        
                        <a href="{{ route('books.show', $book->id) }}" 
                           class="block text-center py-3 bg-slate-50 text-slate-900 rounded-xl font-black text-[10px] uppercase tracking-widest group-hover:bg-slate-900 group-hover:text-white transition-all">
                            Detail Buku
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-black uppercase italic tracking-tighter">Belum ada koleksi buku tersedia.</p>
                </div>
            @endforelse
        </div>

        {{-- SCRIPT CHART USER --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const ctxUser = document.getElementById('readerChart').getContext('2d');
                new Chart(ctxUser, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($labels) !!}, 
                        datasets: [{
                            label: 'Buku Dipinjam',
                            data: {!! json_encode($dataPinjaman) !!}, 
                            backgroundColor: '#3b82f6', 
                            borderRadius: 10,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f8fafc' }, ticks: { font: { weight: 'bold' } } },
                            x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                        }
                    }
                });
            });
        </script>

    @endif

</div>
@endsection