@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold flex items-center gap-2 animate-fade-in">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900">Daftar Pinjaman Saya</h2>
            <p class="text-slate-500">Riwayat dan status buku yang sedang Anda pinjam.</p>
        </div>
        <a href="{{ route('katalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
            <span>+</span> Pinjam Buku Lagi
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Buku</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Peminjam</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Tenggat</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Denda</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($loans as $loan)
                @php
                    $tenggat = \Carbon\Carbon::parse($loan->tanggal_kembali);
                    $hariIni = \Carbon\Carbon::now()->startOfDay();
                    $selisihHari = $hariIni->diffInDays($tenggat, false);
                    $isWarning = $tenggat->isToday() || $tenggat->isPast();
                    
                    // Hitung Denda jika status masih dipinjam dan sudah lewat tenggat
                    $denda = 0;
                    if ($selisihHari < 0 && $loan->status == 'dipinjam') {
                        $denda = abs($selisihHari) * 2000;
                    }
                @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <img src="{{ Str::startsWith($loan->book->cover, 'http') ? $loan->book->cover : asset('storage/' . $loan->book->cover) }}" class="w-14 h-20 object-cover rounded-xl shadow-md">
                            <div>
                                <p class="font-bold text-slate-900 leading-tight">{{ $loan->book->judul }}</p>
                                <p class="text-[10px] text-blue-600 font-mono mt-1 font-bold uppercase tracking-tighter">
                                    LIB-{{ str_pad($loan->book->id, 4, '0', STR_PAD_LEFT) }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-8 py-6">
                        <p class="font-bold text-slate-800">{{ $loan->nama_peminjam }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $loan->nomor_identitas }}</p>
                    </td>

                    <td class="px-8 py-6 text-center">
                        <p class="font-bold {{ $isWarning && $loan->status == 'dipinjam' ? 'text-red-500' : 'text-slate-700' }}">
                            {{ $tenggat->format('d M Y') }}
                        </p>
                        @if($loan->status == 'dipinjam')
                            @if($isWarning)
                                <span class="text-[9px] font-black text-red-500 uppercase tracking-tighter block animate-bounce mt-1">⚠️ Segera Kembalikan!</span>
                            @else
                                <p class="text-[10px] font-black uppercase tracking-tighter text-slate-400 mt-1">{{ $selisihHari }} Hari Lagi</p>
                            @endif
                        @endif
                    </td>

                    <td class="px-8 py-6 text-center">
                        @if($denda > 0)
                            <div class="inline-block bg-red-50 border border-red-100 px-3 py-1 rounded-full">
                                <p class="text-sm font-black text-red-600">Rp {{ number_format($denda, 0, ',', '.') }}</p>
                            </div>
                        @else
                            <p class="text-sm font-bold text-slate-300">-</p>
                        @endif
                    </td>

                    <td class="px-8 py-6 text-right">
                        <span class="px-4 py-1.5 {{ $loan->status == 'dikembalikan' ? 'bg-green-100 text-green-600 border-green-200' : ($isWarning ? 'bg-red-100 text-red-600 border-red-200' : 'bg-blue-100 text-blue-600 border-blue-200') }} rounded-full text-[10px] font-black uppercase tracking-widest border">
                            {{ $loan->status }}
                        </span>
                    </td>

                    <td class="px-8 py-6 text-center">
                        @if($loan->status == 'dipinjam')
                        <form action="{{ route('loans.return', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah buku ini sudah benar-benar dikembalikan?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-slate-900 hover:bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl transition-all shadow-md active:scale-95">
                                Kembalikan
                            </button>
                        </form>
                        @else
                        <div class="flex flex-col items-center">
                            <span class="text-green-500 text-xl font-bold">✔</span>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Selesai</span>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($loans->isEmpty())
        <div class="py-20 text-center">
            <span class="text-5xl block mb-4">📭</span>
            <p class="text-slate-400 font-bold uppercase text-xs tracking-widest">Belum ada riwayat peminjaman</p>
        </div>
        @endif
    </div>
</div>
@endsection