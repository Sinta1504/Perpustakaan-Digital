@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto">
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic">Daftar Pinjaman</h2>
            <p class="text-slate-500 font-medium">Pantau dan kelola koleksi buku digital yang sedang dipinjam.</p>
        </div>
        
        <a href="{{ route('katalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[2rem] font-black shadow-xl shadow-blue-100 transition-all flex items-center gap-3 uppercase tracking-widest text-xs">
            <span class="text-xl">+</span> Pinjam Buku Lagi
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg shadow-green-100 flex items-center gap-3">
            <i class="fas fa-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Utama --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-12">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Buku</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Peminjam</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tenggat</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi & Rating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-16 rounded-xl bg-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                                    @if($loan->book)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($loan->book->cover, 'http') ? $loan->book->cover : asset('storage/' . $loan->book->cover) }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://placehold.co/400x600?text=No+Cover'">
                                    @else
                                        <img src="https://placehold.co/400x600?text=Deleted" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-slate-900 text-sm leading-tight uppercase italic">{{ $loan->book->judul ?? 'Buku Dihapus' }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase italic">{{ $loan->book->penulis ?? 'Unknown Author' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black text-[10px] uppercase shadow-sm">
                                    {{ substr($loan->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900 text-sm">{{ $loan->user->name ?? 'User Tidak Ditemukan' }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $loan->user->email ?? '-' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-5 text-sm font-black text-slate-600 uppercase">
                            {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->translatedFormat('d M Y') }}
                        </td>

                        <td class="px-8 py-5">
                            @if($loan->status === 'dipinjam')
                                <span class="bg-amber-100 text-amber-700 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-200">Sedang Dipinjam</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-200">Sudah Kembali</span>
                            @endif
                        </td>

                        <td class="px-8 py-5 text-center">
                            @if($loan->status === 'dipinjam')
                                <form action="{{ route('loans.return', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[10px] font-black hover:bg-blue-600 transition-all shadow-lg shadow-slate-100 uppercase tracking-widest flex items-center gap-2 mx-auto">
                                        <i class="fas fa-undo-alt"></i>
                                        {{ auth()->user()->role === 'admin' ? 'Selesaikan' : 'Kembalikan' }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('feedback.store') }}" method="POST" class="flex flex-col gap-2 items-center bg-slate-50 p-3 rounded-2xl border border-slate-100 w-full max-w-[180px] mx-auto">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $loan->book_id }}">
                                    <input type="hidden" name="kategori" value="saran">
                                    <select name="rating" required class="text-[10px] font-black border-slate-200 rounded-lg p-1 w-full outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                        <option value="4">⭐⭐⭐⭐</option>
                                        <option value="3">⭐⭐⭐</option>
                                        <option value="2">⭐⭐</option>
                                        <option value="1">⭐</option>
                                    </select>
                                    <input type="text" name="pesan" required placeholder="Review buku..." class="text-[10px] border-slate-200 rounded-lg p-2 w-full outline-none focus:ring-1 focus:ring-blue-500 font-medium italic">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[9px] font-black py-2 px-3 rounded-lg uppercase w-full shadow-sm transition-colors">Kirim Rating</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-32 text-center">
                            <div class="text-6xl mb-6">📬</div>
                            <h3 class="text-xl font-black text-slate-900 uppercase italic">Belum ada riwayat</h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection