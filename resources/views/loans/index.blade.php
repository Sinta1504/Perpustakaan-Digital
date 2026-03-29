@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    {{-- BAGIAN: Rekomendasi Buku (Dashboard) --}}
    @if(request()->routeIs('dashboard'))
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 uppercase italic">Rekomendasi Buku</h2>
                    <p class="text-slate-500 text-sm font-medium">Buku-buku pilihan yang paling banyak dibaca</p>
                </div>
                <a href="{{ route('katalog') }}" class="text-slate-400 hover:text-blue-600 font-bold text-xs uppercase tracking-widest transition-colors flex items-center gap-2">
                    Lihat Semua Katalog <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($rekomendasi_buku as $buku)
                <div class="relative group bg-white p-4 rounded-[2.5rem] shadow-sm border border-slate-50 transition-all hover:shadow-2xl hover:-translate-y-1">
                    <div class="relative overflow-hidden rounded-[2rem] aspect-[3/4] bg-slate-100 mb-4 shadow-inner">
                        @php 
                            $urlCover = $buku->cover; 
                            $pathCover = \Illuminate\Support\Str::startsWith($urlCover, 'http') ? $urlCover : asset('storage/' . $urlCover);
                        @endphp
                        
                        <img src="{{ $urlCover ? $pathCover : 'https://placehold.co/400x600?text=No+Cover' }}" 
                             alt="{{ $buku->judul }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             onerror="this.src='https://placehold.co/400x600?text=Cover+Error'">

                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-[2px]">
                            <a href="{{ route('loans.create', $buku->id) }}" class="bg-white text-slate-900 px-6 py-3 rounded-full font-black text-[10px] uppercase tracking-tighter shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-blue-600 hover:text-white">
                                Pinjam Sekarang
                            </a>
                        </div>
                    </div>
                    <div class="px-2">
                        <h4 class="font-black text-sm uppercase italic leading-tight text-slate-800 line-clamp-1">{{ $buku->judul }}</h4>
                        <p class="text-[10px] text-slate-400 font-bold italic mt-1 uppercase tracking-wider">{{ $buku->penulis }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <hr class="border-slate-100 mb-12">
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic">Daftar Pinjaman</h2>
            <p class="text-slate-500 font-medium">Pantau dan kelola koleksi buku digital Anda.</p>
        </div>
        <a href="{{ route('katalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-[2rem] font-black shadow-xl shadow-blue-100 transition-all flex items-center gap-3 uppercase tracking-widest text-xs">
            <span class="text-xl">+</span> Pinjam Buku Lagi
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-r-xl shadow-sm flex items-center gap-3">
            <span class="text-xl">✅</span>
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- TABEL DAFTAR PINJAMAN --}}
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-12">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Buku</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Peminjam</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tenggat</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Respon/Ulasan</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-20 flex-shrink-0 bg-slate-100 rounded-lg overflow-hidden shadow-sm border border-slate-200">
                                    @php
                                        $coverPath = $loan->book->cover ?? '';
                                        $urlGambar = \Illuminate\Support\Str::startsWith($coverPath, 'http') 
                                                     ? $coverPath 
                                                     : asset('storage/' . $coverPath);
                                    @endphp

                                    @if($coverPath)
                                        <img src="{{ $urlGambar }}" 
                                             alt="{{ $loan->book->judul ?? 'Buku' }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://placehold.co/400x600?text=No+Cover'">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-200">
                                            <span class="text-[10px] text-slate-400 font-bold uppercase italic">No Cover</span>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h5 class="font-black text-slate-900 uppercase italic text-sm leading-tight">
                                        {{ $loan->book->judul ?? 'Buku Dihapus' }}
                                    </h5>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                        {{ $loan->book->penulis ?? 'Unknown Author' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-5 text-sm font-bold text-slate-700">{{ $loan->user->name ?? 'User' }}</td>
                        
                        <td class="px-8 py-5">
                            <div class="text-sm font-black text-slate-600 uppercase">
                                {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}
                            </div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Sirkulasi</span>
                        </td>

                        <td class="px-8 py-5">
                            @if($loan->status === 'dipinjam')
                                <span class="bg-amber-100 text-amber-700 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-200 block w-fit">Sedang Dipinjam</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-200 block w-fit">Sudah Kembali</span>
                                @if($loan->denda > 0)
                                    <p class="text-[9px] font-bold text-orange-600 mt-1">Denda Dibayar: Rp {{ number_format($loan->denda, 0, ',', '.') }}</p>
                                @endif
                            @endif
                        </td>

                        {{-- BAGIAN YANG DIPERBAIKI: Menampilkan Ulasan & Rating --}}
                        <td class="px-8 py-5">
                            @if($loan->ulasan)
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-blue-600 uppercase">
                                        {{ $loan->rating }}/5 ⭐
                                    </span>
                                    <p class="text-xs text-slate-500 italic line-clamp-1 mt-0.5">
                                        "{{ $loan->ulasan }}"
                                    </p>
                                </div>
                            @else
                                <span class="text-slate-400 italic text-[10px]">Belum ada ulasan</span>
                            @endif
                        </td>

                        <td class="px-8 py-5 text-center">
                            @if($loan->status === 'dipinjam')
                                <button type="button" 
                                    onclick="openReturnModal('{{ $loan->id }}', '{{ addslashes($loan->book->judul ?? 'Buku') }}')"
                                    class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[10px] font-black hover:bg-blue-600 transition-all shadow-lg shadow-slate-100 uppercase tracking-widest flex items-center gap-2 mx-auto">
                                    <i class="fas fa-undo-alt"></i> Kembalikan
                                </button>
                            @else
                                <span class="text-green-500 bg-green-50 p-2 rounded-full text-xs shadow-inner inline-block"><i class="fas fa-check"></i></span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center font-bold text-slate-400 uppercase tracking-widest text-xs">
                            Belum ada riwayat peminjaman buku.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div id="returnModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
        <form id="returnForm" method="POST">
            @csrf
            <div class="p-8">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-black text-slate-900 uppercase italic" id="modalBookTitle">Kembalikan Buku</h3>
                    <p class="text-slate-500 text-sm font-medium">Berikan ulasan Anda untuk menyelesaikan.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-2">Rating</label>
                        <select name="rating" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                            <option value="4">⭐⭐⭐⭐ (Puas)</option>
                            <option value="3">⭐⭐⭐ (Biasa)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Buruk)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-2">Ulasan</label>
                        <textarea name="ulasan" required rows="3" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-500" placeholder="Apa pendapatmu tentang buku ini?"></textarea>
                    </div>
                </div>
            </div>
            <div class="flex border-t border-slate-50">
                <button type="button" onclick="closeReturnModal()" class="flex-1 px-6 py-5 text-xs font-black uppercase text-slate-400 hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-6 py-5 bg-blue-600 text-white text-xs font-black uppercase hover:bg-blue-700 transition-colors">Kirim & Selesai</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(id, title) {
        document.getElementById('returnForm').action = `/pinjaman/kembalikan/${id}`; 
        document.getElementById('modalBookTitle').innerText = title;
        document.getElementById('returnModal').classList.remove('hidden');
    }
    
    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        let modal = document.getElementById('returnModal');
        if (event.target == modal) {
            closeReturnModal();
        }
    }
</script>
@endsection