@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">Daftar Sirkulasi</h2>
            <p class="text-slate-500 text-sm font-medium">Pantau seluruh koleksi buku digital secara global.</p>
        </div>
        
        {{-- Form Filter --}}
        <form action="{{ route('admin.loans') }}" method="GET" class="flex gap-4">
            <select name="status" class="bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Semua Status" {{ request('status') == 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Kembali</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                Filter
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Buku</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Peminjam</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tenggat</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                {{-- LOGIKA COVER KATALOG --}}
                                <div class="w-14 h-20 bg-slate-100 rounded-lg flex-shrink-0 overflow-hidden shadow-md border border-slate-200 flex items-center justify-center">
                                    @php
                                        $cover = $loan->book->cover;
                                        $url = \Illuminate\Support\Str::startsWith($cover, ['http', 'https']) 
                                               ? $cover 
                                               : ($cover ? asset('storage/' . $cover) : null);
                                    @endphp
                                    @if($url)
                                        <img src="{{ $url }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x600?text=Error'">
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Buku</span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-800 uppercase italic text-sm leading-tight">{{ $loan->book->judul }}</h4>
                                    <p class="text-[10px] text-blue-600 font-bold mt-1 tracking-widest uppercase italic">ID: #BK-{{ $loan->book->id }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-black text-[10px] uppercase shadow-sm border border-blue-100">
                                    {{ substr($loan->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 uppercase italic text-sm">{{ $loan->user->name }}</p>
                                    <p class="text-[9px] text-slate-400 font-medium tracking-tighter">{{ $loan->user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <p class="font-black text-slate-700 uppercase italic text-sm">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</p>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Sirkulasi</p>
                        </td>

                        <td class="px-8 py-6 text-center">
                            @if($loan->status == 'dipinjam')
                                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                                    Sedang Dipinjam
                                </span>
                            @else
                                <span class="bg-green-50 text-green-600 border border-green-100 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                                    Sudah Kembali
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center font-bold text-slate-400 uppercase tracking-[0.3em] text-xs">
                            Belum ada aktivitas sirkulasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection