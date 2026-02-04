@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-900">Daftar Pinjaman</h2>
            <p class="text-slate-500">Pantau siapa saja yang sedang meminjam koleksi buku digital.</p>
        </div>
        
        <a href="{{ route('katalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 transition flex items-center gap-2">
            <span class="text-xl">+</span> Pinjam Buku Lagi
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Buku</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Peminjam</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tenggat</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Denda</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 rounded-xl bg-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                                    <img src="{{ \Illuminate\Support\Str::startsWith($loan->book->cover, 'http') ? $loan->book->cover : asset('storage/' . $loan->book->cover) }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://placehold.co/400x600?text=No+Cover'">
                                </div>
                                <span class="font-bold text-slate-900 text-sm leading-tight">{{ $loan->book->judul }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 text-sm">{{ $loan->user->name }}</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $loan->user->email }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm font-bold text-slate-600">
                            {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-black {{ $loan->penalty > 0 ? 'text-red-600' : 'text-slate-300' }}">
                                Rp {{ number_format($loan->penalty ?? 0, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            @if($loan->status === 'borrowed')
                                <span class="bg-amber-50 text-amber-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">Sedang Dipinjam</span>
                            @else
                                <span class="bg-green-50 text-green-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">Sudah Kembali</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if(auth()->user()->role === 'admin' && $loan->status === 'borrowed')
                            <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST">
                                @csrf
                                <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-blue-600 transition-all shadow-md shadow-slate-200">
                                    Selesaikan
                                </button>
                            </form>
                            @else
                            <span class="text-slate-300 text-xs font-bold">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-32 text-center">
                            <div class="text-6xl mb-6">📬</div>
                            <h3 class="text-xl font-bold text-slate-900">Belum ada riwayat</h3>
                            <p class="text-slate-400">Semua data peminjaman buku akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection