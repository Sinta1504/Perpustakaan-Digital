@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">

    {{-- Notifikasi Error & Sukses --}}
    @if ($errors->any())
        <div class="bg-red-500 text-white p-5 rounded-[2rem] mb-8 shadow-lg">
            <div class="flex items-center mb-2">
                <span class="text-xl mr-2">⚠️</span>
                <p class="font-black uppercase tracking-widest text-xs">Ada Kesalahan:</p>
            </div>
            <ul class="list-disc list-inside text-sm font-medium opacity-90">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-500 text-white p-5 rounded-[2rem] mb-8 shadow-lg flex items-center">
            <span class="text-xl mr-3">✅</span>
            <p class="font-black uppercase tracking-widest text-xs">{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex justify-between items-center mb-10">
        <h2 class="text-4xl font-black text-slate-900 tracking-tighter uppercase italic">Pinjaman Saya 📖</h2>
        <div class="bg-blue-600 text-white px-6 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">
            {{ $loans->count() }} Total Aktivitas
        </div>
    </div>

    <div class="grid grid-cols-1 gap-12">
        @forelse($loans as $loan)
        <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden transition-all hover:border-blue-200">
            <div class="p-10">
                <div class="flex flex-col lg:flex-row gap-12">
                    
                    {{-- SISI KIRI: Visual Buku --}}
                    <div class="lg:w-1/4">
                        <div class="relative group">
                            <img src="{{ $loan->book->cover_url ?? ( $loan->book->cover ? asset('storage/'.$loan->book->cover) : 'https://placehold.co/400x600?text='.urlencode($loan->book->judul) ) }}" 
                                 class="w-full aspect-[3/4] object-cover rounded-[2.5rem] shadow-2xl border-8 border-slate-50 transition-transform group-hover:scale-105"
                                 alt="{{ $loan->book->judul }}">
                            
                            <div class="absolute -bottom-4 -right-4 bg-white p-3 rounded-2xl shadow-xl border border-slate-100">
                                @if($loan->status == 'Sudah Dikembalikan' || $loan->status == 'kembali')
                                    <span class="text-green-500 flex items-center gap-1 text-[10px] font-black uppercase tracking-widest">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        DIKEMBALIKAN
                                    </span>
                                @else
                                    <span class="text-orange-500 text-[10px] font-black uppercase tracking-widest">
                                        ● {{ strtoupper($loan->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SISI KANAN: Detail --}}
                    <div class="lg:w-3/4 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-3xl font-black text-slate-900 leading-tight uppercase italic mb-1">{{ $loan->book->judul }}</h4>
                                    <p class="text-sm text-blue-600 font-bold tracking-widest uppercase mb-4 italic">Karya: {{ $loan->book->penulis }}</p>

                                    {{-- AKSES DIGITAL --}}
                                    @if($loan->status == 'dipinjam' || $loan->status == 'sedang dipinjam')
                                        <div class="flex flex-wrap gap-3 mb-6">
                                            <a href="{{ route('loans.download-pdf', $loan->id) }}" 
                                               class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all flex items-center gap-2 shadow-lg shadow-blue-200">
                                                <i class="fas fa-file-pdf"></i> Unduh E-Book (PDF)
                                            </a>
                                            <div class="bg-blue-50 border border-blue-100 px-4 py-2.5 rounded-xl text-[9px] font-bold text-blue-600 uppercase italic flex items-center gap-2">
                                                <i class="fas fa-magic"></i> Auto-Generated System
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($loan->status == 'Sudah Dikembalikan' || $loan->status == 'kembali')
                                    <div class="bg-green-100 text-green-600 p-4 rounded-full shadow-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Panel Info --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                                <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100">
                                    <p class="text-[9px] text-slate-400 font-black uppercase mb-1">Tenggat Kembali</p>
                                    <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($loan->tanggal_tenggat)->format('d M Y') }}</p>
                                </div>
                                
                                <div class="{{ $loan->denda > 0 ? 'bg-red-50 border-red-100' : 'bg-slate-50 border-slate-100' }} p-5 rounded-3xl border">
                                    <p class="text-[9px] {{ $loan->denda > 0 ? 'text-red-400' : 'text-slate-400' }} font-black uppercase mb-1">Denda Terakumulasi</p>
                                    <p class="text-sm font-black {{ $loan->denda > 0 ? 'text-red-600' : 'text-slate-700' }}">
                                        Rp {{ number_format($loan->denda ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="bg-blue-50 p-5 rounded-3xl border border-blue-100">
                                    <p class="text-[9px] text-blue-400 font-black uppercase mb-1">Status Pinjaman</p>
                                    <p class="text-sm font-black text-blue-700 uppercase">
                                        {{ ($loan->status == 'Sudah Dikembalikan' || $loan->status == 'kembali') ? 'Selesai' : $loan->status }}
                                    </p>
                                </div>
                            </div>

                            {{-- KONDISI 1: Form Pengembalian (Jika Sedang Dipinjam) --}}
                            @if($loan->status == 'dipinjam' || $loan->status == 'sedang dipinjam')
                                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl">
                                    <h5 class="text-xs font-black uppercase tracking-[0.2em] mb-4 text-blue-400">Kembalikan Buku & Berikan Ulasan</h5>
                                    <form action="{{ route('pinjaman.kembalikan', $loan->id) }}" method="POST">
                                        @csrf
                                        <div class="flex flex-col md:flex-row gap-6">
                                            <div class="md:w-1/4">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Rating:</p>
                                                <select name="rating" required class="w-full bg-slate-800 border-none rounded-xl text-amber-400 font-bold focus:ring-2 focus:ring-blue-500">
                                                    <option value="5">★★★★★ (Hebat)</option>
                                                    <option value="4">★★★★☆ (Bagus)</option>
                                                    <option value="3">★★★☆☆ (Biasa)</option>
                                                    <option value="2">★★☆☆☆ (Kurang)</option>
                                                    <option value="1">★☆☆☆☆ (Buruk)</option>
                                                </select>
                                            </div>
                                            <div class="md:w-3/4">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Pesan Ulasan:</p>
                                                <textarea name="ulasan" rows="2" required 
                                                    class="w-full bg-slate-800 border-none rounded-2xl p-4 text-sm text-slate-200 placeholder:text-slate-500 focus:ring-2 focus:ring-blue-500"
                                                    placeholder="Tulis ulasan minimal 5 karakter..."></textarea>
                                                <button type="submit" class="mt-4 w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg active:scale-95">
                                                    <i class="fas fa-undo mr-2"></i> KIRIM & SELESAI PENGEMBALIAN
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            {{-- KONDISI 2: Tampilan Feedback & Balasan (Jika Sudah Kembali) --}}
                            @elseif($loan->status == 'Sudah Dikembalikan' || $loan->status == 'kembali')
                                @if($loan->feedback)
                                    <div class="bg-emerald-50 border border-emerald-100 rounded-[2.5rem] p-8">
                                        <div class="flex flex-col gap-6">
                                            {{-- Baris Atas: Ulasan User --}}
                                            <div class="flex justify-between items-start">
                                                <div class="flex items-center gap-4">
                                                    <span class="text-3xl">🏆</span>
                                                    <div>
                                                        <p class="text-emerald-600 font-black text-[10px] uppercase tracking-widest">Ulasan Anda Telah Terkirim</p>
                                                        <p class="text-slate-700 text-sm font-bold italic mt-1">"{{ $loan->feedback->komentar }}"</p>
                                                        <div class="flex gap-1 mt-1 text-amber-400 text-xs">
                                                            @for($i=1; $i<=5; $i++)
                                                                <span>{{ $i <= $loan->feedback->rating ? '⭐' : '☆' }}</span>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if($loan->denda > 0)
                                                <div class="bg-rose-600 text-white px-4 py-2 rounded-xl shadow-lg shadow-rose-200">
                                                    <p class="text-[8px] font-black uppercase opacity-80">Denda Dibayar:</p>
                                                    <p class="text-xs font-black italic">Rp {{ number_format($loan->denda, 0, ',', '.') }}</p>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Baris Bawah: Balasan Admin --}}
                                            @if($loan->feedback->balasan_admin)
                                                <div class="bg-slate-900 p-5 rounded-[2rem] text-white relative ml-6 md:ml-12">
                                                    {{-- Bubble Tail --}}
                                                    <div class="absolute -left-2 top-6 w-0 h-0 border-t-[10px] border-t-transparent border-r-[15px] border-r-slate-900 border-b-[10px] border-b-transparent"></div>
                                                    
                                                    <div class="flex items-start gap-3">
                                                        <div class="bg-blue-600 p-2 rounded-lg">
                                                            <i class="fas fa-user-shield text-[10px]"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-[9px] font-black text-blue-400 uppercase italic tracking-wider">Balasan Admin:</p>
                                                            <p class="text-sm opacity-90 leading-relaxed mt-1 font-medium">{{ $loan->feedback->balasan_admin }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="ml-12">
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase italic flex items-center gap-2">
                                                        <span class="relative flex h-2 w-2">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                        </span>
                                                        Menunggu tanggapan admin...
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    {{-- Tombol Beri Ulasan Jika belum ada data feedback --}}
                                    <div class="bg-slate-50 border border-dashed border-slate-200 rounded-[2.5rem] p-8 text-center">
                                        <button onclick="openModal('{{ $loan->id }}')" class="bg-white border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-xl">
                                            <i class="fas fa-pen-fancy mr-2"></i> Beri Ulasan Sekarang
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-24 bg-white rounded-[4rem] border-4 border-dashed border-slate-100">
            <p class="text-slate-400 font-black uppercase tracking-widest italic">Belum ada aktivitas pinjaman.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection