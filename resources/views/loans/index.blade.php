@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    {{-- BAGIAN: Rekomendasi Buku (Hanya muncul di Dashboard) --}}
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

    {{-- HEADER HALAMAN PINJAMAN --}}
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
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Tenggat</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Respon / Ulasan</th>
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
                                    <img src="{{ $coverPath ? $urlGambar : 'https://placehold.co/400x600?text=No+Cover' }}" 
                                         alt="{{ $loan->book->judul ?? 'Buku' }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://placehold.co/400x600?text=No+Cover'">
                                </div>
                                <div>
                                    <h5 class="font-black text-slate-900 uppercase italic text-sm leading-tight">{{ $loan->book->judul ?? 'Buku Dihapus' }}</h5>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $loan->book->penulis ?? 'Unknown Author' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-5">
                            <div class="text-sm font-black text-slate-600 uppercase">
                                {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}
                            </div>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Sirkulasi</span>
                        </td>

                        <td class="px-8 py-5">
                            @if($loan->status === 'dipinjam')
                                <span class="bg-amber-100 text-amber-700 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-amber-200 block w-fit shadow-sm shadow-amber-50">Sedang Dipinjam</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-200 block w-fit shadow-sm shadow-green-50">Sudah Kembali</span>
                                @if($loan->denda > 0)
                                    <p class="text-[9px] font-bold text-orange-600 mt-1 uppercase italic">Denda: Rp {{ number_format($loan->denda, 0, ',', '.') }}</p>
                                @endif
                            @endif
                        </td>

                        {{-- KOLOM ULASAN & BALASAN ADMIN --}}
                        <td class="px-8 py-5">
                            @if($loan->ulasan)
                                <div class="flex flex-col gap-2">
                                    {{-- Bagian Ulasan User --}}
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-blue-600 uppercase flex items-center gap-1">
                                            {{ $loan->rating }}/5 <i class="fas fa-star text-[8px]"></i>
                                        </span>
                                        <p class="text-xs text-slate-500 italic line-clamp-2 mt-0.5 max-w-[220px]">
                                            "{{ $loan->ulasan }}"
                                        </p>
                                    </div>

                                    {{-- BAGIAN BARU: Balasan dari Admin --}}
                                    @if($loan->balasan_admin)
                                        <div class="bg-blue-50/50 border-l-2 border-blue-400 p-2 rounded-r-lg mt-1">
                                            <span class="text-[9px] font-black text-blue-700 uppercase tracking-tighter block mb-0.5">
                                                <i class="fas fa-reply fa-flip-horizontal mr-1"></i> Respon Admin:
                                            </span>
                                            <p class="text-[11px] text-slate-600 font-medium leading-relaxed italic">
                                                {{ $loan->balasan_admin }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-400 italic text-[10px] tracking-tight">Belum ada ulasan</span>
                            @endif
                        </td>

                        <td class="px-8 py-5 text-center">
                            @if($loan->status === 'dipinjam')
                                <button type="button" 
                                    onclick="openReturnModal('{{ $loan->id }}', '{{ addslashes($loan->book->judul ?? 'Buku') }}')"
                                    class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[10px] font-black hover:bg-blue-600 transition-all shadow-lg shadow-slate-100 uppercase tracking-widest flex items-center gap-2 mx-auto active:scale-95">
                                    <i class="fas fa-undo-alt"></i> Kembalikan
                                </button>
                            @else
                                <div class="w-8 h-8 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center">
                            <i class="fas fa-book-open text-slate-100 text-5xl mb-4 block"></i>
                            <span class="font-bold text-slate-300 uppercase tracking-widest text-xs italic">Belum ada riwayat peminjaman buku.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL PENGEMBALIAN BUKU --}}
<div id="returnModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
        <form id="returnForm" method="POST">
            @csrf
            <div class="p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <i class="fas fa-paper-plane text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase italic leading-tight" id="modalBookTitle">Kembalikan Buku</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1">Bantu kami berkembang dengan ulasan jujur Anda.</p>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-3">Seberapa puas Anda?</label>
                        <select name="rating" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500 transition-all appearance-none cursor-pointer">
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                            <option value="4">⭐⭐⭐⭐ (Puas)</option>
                            <option value="3">⭐⭐⭐ (Biasa)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Buruk)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-3">Tulis Ulasan Singkat</label>
                        <textarea name="ulasan" required rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-500 transition-all placeholder:text-slate-300" placeholder="Contoh: Bukunya sangat bermanfaat dan mudah dipahami!"></textarea>
                    </div>
                </div>
            </div>
            <div class="flex border-t border-slate-50 bg-slate-50/50">
                <button type="button" onclick="closeReturnModal()" class="flex-1 px-6 py-6 text-[10px] font-black uppercase text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors tracking-widest">Batal</button>
                <button type="submit" class="flex-1 px-6 py-6 bg-blue-600 text-white text-[10px] font-black uppercase hover:bg-blue-700 transition-all tracking-widest shadow-lg shadow-blue-100">Kirim & Kembalikan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(id, title) {
        document.getElementById('returnForm').action = `/pinjaman/kembalikan/${id}`; 
        document.getElementById('modalBookTitle').innerText = title;
        
        const modal = document.getElementById('returnModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closeReturnModal() {
        const modal = document.getElementById('returnModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    window.onclick = function(event) {
        let modal = document.getElementById('returnModal');
        if (event.target == modal) {
            closeReturnModal();
        }
    }
</script>
@endsection