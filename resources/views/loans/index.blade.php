@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-8">
    
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-8">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter uppercase italic">Daftar Pinjaman Saya</h2>
            <p class="text-slate-500 text-sm font-medium">Pantau dan kelola koleksi buku digital Anda.</p>
        </div>
        <a href="{{ route('katalog') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
            <i class="fas fa-plus text-[10px]"></i> Pinjam Buku Lagi
        </a>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Buku</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tenggat</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status & Unduh</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Respon / Ulasan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        {{-- Kolom Buku --}}
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <img src="{{ $loan->book->cover_url ?? asset('storage/'.$loan->book->cover) }}" class="w-16 h-20 object-cover rounded-xl shadow-md border border-slate-100">
                                <div>
                                    <p class="text-sm font-black text-slate-900 uppercase italic leading-tight">{{ $loan->book->judul }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">{{ $loan->book->penulis }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Tenggat --}}
                        <td class="px-8 py-6">
                            <div>
                                <p class="text-sm font-black text-slate-700 uppercase">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Sirkulasi</p>
                            </div>
                        </td>

                        {{-- Kolom Status & Unduh --}}
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-2">
                                @if($loan->status == 'dipinjam')
                                    <span class="bg-amber-100 text-amber-600 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-wider w-fit">Sedang Dipinjam</span>
                                    <a href="{{ route('loans.download-pdf', $loan->id) }}" class="flex items-center gap-2 text-blue-600 font-black text-[9px] uppercase tracking-widest hover:underline">
                                        <i class="fas fa-file-pdf"></i> Unduh E-Book
                                    </a>
                                @else
                                    <span class="bg-emerald-100 text-emerald-600 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-wider w-fit">Sudah Kembali</span>
                                @endif
                            </div>
                        </td>

                        {{-- Kolom Respon / Ulasan --}}
                        <td class="px-8 py-6">
                            @if(!empty($loan->ulasan))
                                <div class="flex flex-col gap-3 max-w-xs">
                                    <div>
                                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Ulasan Anda:</p>
                                        <p class="text-xs text-slate-600 italic font-medium leading-relaxed">"{{ $loan->ulasan }}"</p>
                                        <div class="flex gap-0.5 mt-1 text-amber-400">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="{{ $i <= $loan->rating ? 'fas' : 'far' }} fa-star text-[8px]"></i>
                                            @endfor
                                        </div>
                                    </div>

                                    @if($loan->balasan_admin)
                                        <div class="bg-blue-50 p-3 rounded-2xl border border-blue-100 relative mt-1">
                                            <div class="absolute -top-1 left-4 w-2 h-2 bg-blue-50 border-t border-l border-blue-100 rotate-45"></div>
                                            <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-1">
                                                <i class="fas fa-reply fa-flip-horizontal"></i> Balasan Admin:
                                            </p>
                                            <p class="text-[11px] text-slate-700 font-bold leading-tight mt-1">{{ $loan->balasan_admin }}</p>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 mt-1 px-1">
                                            <div class="w-1.5 h-1.5 bg-slate-300 rounded-full animate-pulse"></div>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase italic">Menunggu respon admin...</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="opacity-40">
                                    {{-- Diagnosa jika status sudah kembali tapi ulasan kosong --}}
                                    @if($loan->status == 'kembali' || $loan->status == 'Sudah Kembali')
                                        <p class="text-[10px] text-red-500 font-black uppercase italic tracking-widest leading-none">Ulasan Tidak Ditemukan</p>
                                        <p class="text-[8px] text-slate-400 font-medium uppercase mt-1 italic">Silakan cek record database Anda.</p>
                                    @else
                                        <p class="text-[10px] text-slate-300 font-bold uppercase italic tracking-widest leading-none">Belum ada ulasan</p>
                                        <p class="text-[8px] text-slate-200 font-medium uppercase mt-1">Berikan ulasan saat mengembalikan</p>
                                    @endif
                                </div>
                            @endif
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="px-8 py-6 text-center">
                            @if($loan->status == 'dipinjam')
                                <button onclick="toggleModal('modal-{{ $loan->id }}')" class="bg-slate-900 hover:bg-black text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] transition-all flex items-center gap-2 mx-auto shadow-lg shadow-slate-200 active:scale-95">
                                    <i class="fas fa-undo"></i> Kembalikan
                                </button>

                                {{-- Modal Input Ulasan --}}
                                <div id="modal-{{ $loan->id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm text-left">
                                    <div class="flex items-center justify-center min-h-screen px-4">
                                        <div class="bg-white rounded-[2.5rem] p-8 max-w-md w-full shadow-2xl transform transition-all">
                                            <div class="text-center mb-6">
                                                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <i class="fas fa-book-open text-2xl"></i>
                                                </div>
                                                <h3 class="text-xl font-black text-slate-900 uppercase italic">Selesaikan Pinjaman</h3>
                                                <p class="text-slate-400 text-xs font-medium mt-1">Bagikan pengalaman membacamu untuk kami!</p>
                                            </div>

                                            <form action="{{ route('pinjaman.kembalikan', $loan->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Rating Buku</label>
                                                    <select name="rating" class="w-full bg-slate-50 border-2 border-slate-50 rounded-xl text-amber-500 font-black p-3 focus:border-blue-500 focus:bg-white outline-none transition-all">
                                                        <option value="5">★★★★★ (Luar Biasa)</option>
                                                        <option value="4">★★★★☆ (Sangat Baik)</option>
                                                        <option value="3">★★★☆☆ (Cukup Baik)</option>
                                                        <option value="2">★★☆☆☆ (Kurang)</option>
                                                        <option value="1">★☆☆☆☆ (Buruk)</option>
                                                    </select>
                                                </div>
                                                <div class="mb-6">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block tracking-widest">Ulasan Anda</label>
                                                    <textarea name="ulasan" rows="3" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-4 text-sm focus:border-blue-500 focus:bg-white outline-none transition-all" placeholder="Apa pendapatmu tentang isi buku ini?" required></textarea>
                                                </div>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="toggleModal('modal-{{ $loan->id }}')" class="flex-1 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-slate-50 transition-all">Batal</button>
                                                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-200 transition-all active:scale-95">Kirim & Selesai</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-emerald-50 text-emerald-500 w-9 h-9 rounded-full flex items-center justify-center mx-auto border border-emerald-100 shadow-sm shadow-emerald-50">
                                    <i class="fas fa-check text-sm"></i>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i class="fas fa-folder-open text-4xl mb-4"></i>
                                <p class="text-sm font-black uppercase italic tracking-[0.2em] text-slate-400">
                                    Belum ada data pinjaman
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
</script>
@endsection