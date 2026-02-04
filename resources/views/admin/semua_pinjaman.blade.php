@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-bold">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-900">Manajemen Semua Pinjaman</h2>
            <p class="text-slate-500">Pantau aktivitas seluruh member perpustakaan.</p>
        </div>
        <div class="bg-slate-100 px-4 py-2 rounded-xl text-xs font-bold text-slate-600">
            Total Transaksi: {{ $loans->count() }}
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Buku & Akun</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Identitas Peminjam</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Tenggat</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                    <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($loans as $loan)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-6">
                        <p class="font-bold text-slate-900">{{ $loan->book->judul }}</p>
                        <p class="text-[10px] text-blue-600 font-bold italic">Akun: {{ $loan->user->name }}</p>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-bold text-slate-800">{{ $loan->nama_peminjam }}</p>
                        <p class="text-[10px] text-slate-400 tracking-wider">{{ $loan->nomor_identitas }}</p>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="font-mono font-bold text-slate-600">
                            {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border {{ $loan->status == 'dikembalikan' ? 'bg-green-100 text-green-600 border-green-200' : 'bg-orange-100 text-orange-600 border-orange-200' }}">
                            {{ $loan->status }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        @if($loan->status == 'dipinjam')
                        <form action="{{ route('loans.return', $loan->id) }}" method="POST" onsubmit="return confirm('Selesaikan peminjaman ini?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[10px] font-bold hover:bg-blue-600 hover:text-white transition-all">
                                Tandai Kembali
                            </button>
                        </form>
                        @else
                        <span class="text-green-500 font-bold text-sm">Selesai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection