@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    {{-- 1. HEADER --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="p-4 bg-orange-100 rounded-3xl text-3xl shadow-sm">📣</div>
            <div>
                <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">Suara Peminjam</h2>
                <p class="text-slate-500 font-medium text-sm mt-1">Pantau seluruh ulasan dan masukan koleksi buku secara global.</p>
            </div>
        </div>
    </div>

    {{-- 2. DAFTAR ULASAN --}}
    <div class="grid grid-cols-1 gap-8">
        @forelse($feedbacks as $item)
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 transition-all hover:-translate-y-1 relative overflow-hidden group">
                
                <div class="flex flex-col lg:flex-row justify-between gap-6">
                    <div class="flex flex-col md:flex-row gap-8 flex-1">
                        
                        {{-- INFO BUKU & RATING --}}
                        <div class="flex gap-4 min-w-[220px]">
                            {{-- BOX GAMBAR - SOLUSI UNTUK FILE TANPA EKSTENSI --}}
                            <div class="w-16 h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0 border border-slate-200 shadow-inner flex items-center justify-center">
                                @if($item->book && $item->book->image)
                                    {{-- Menggunakan nama file asli dari database tanpa tambahan .jpg manual --}}
                                    <img src="{{ asset('storage/covers/' . $item->book->image) }}" 
                                         alt="{{ $item->book->judul }}" 
                                         class="w-full h-full object-cover"
                                         {{-- Fallback jika browser tetap minta ekstensi --}}
                                         onerror="this.onerror=null; this.src='{{ asset('storage/covers/' . $item->book->image . '.jpg') }}';">
                                @else
                                    <div class="text-center">
                                        <span class="text-[8px] font-black text-slate-300 uppercase italic leading-none">No<br>Cover</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col justify-center">
                                <h4 class="font-black text-slate-900 uppercase italic text-xs leading-tight mb-1 group-hover:text-blue-600 transition-colors">
                                    {{ $item->book->judul ?? 'Judul Tidak Tersedia' }}
                                </h4>
                                <div class="flex text-amber-400 text-[10px] tracking-tighter">
                                    @for($i=1; $i<=5; $i++)
                                        {{ $i <= ($item->rating ?? 0) ? '★' : '☆' }}
                                    @endfor
                                    <span class="text-slate-400 ml-2 font-bold">{{ $item->rating ?? 0 }}.0</span>
                                </div>
                            </div>
                        </div>

                        {{-- IDENTITAS PEMINJAM --}}
                        <div class="flex items-center gap-4 border-l border-slate-100 pl-0 md:pl-8">
                            <div class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center font-black text-xs uppercase shadow-lg shadow-slate-200">
                                {{ substr($item->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm leading-none mb-1">{{ $item->user->name ?? 'Anonim' }}</h4>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $item->user->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- METADATA & STATUS --}}
                    <div class="flex flex-row lg:flex-col items-center lg:items-end justify-between lg:justify-start gap-4">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                            {{ $item->created_at ? $item->created_at->format('d M Y') : '26 Mar 2026' }}
                        </p>
                        <span class="px-4 py-2 rounded-full text-[9px] font-black uppercase italic tracking-wider {{ $item->admin_reply ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-orange-50 text-orange-600 border border-orange-100 animate-pulse' }}">
                            {{ $item->admin_reply ? '✅ Sudah Dibalas' : '⏳ Pending' }}
                        </span>
                    </div>
                </div>

                {{-- PESAN --}}
                <div class="mt-8 p-6 bg-slate-50/80 rounded-[2rem] border border-slate-100 relative shadow-inner">
                    <span class="absolute -top-3 left-8 px-4 bg-white text-[9px] font-black text-blue-600 uppercase tracking-widest italic border border-slate-100 rounded-full shadow-sm">
                        Pesan User
                    </span>
                    <p class="text-slate-600 leading-relaxed italic text-sm font-medium">
                        "{{ $item->pesan ?? $item->message }}"
                    </p>
                    
                    @if($item->admin_reply)
                        <div class="mt-6 p-5 bg-white rounded-2xl border-l-4 border-blue-600 shadow-sm">
                            <small class="block font-black text-[9px] text-blue-600 uppercase tracking-widest mb-2">Respon Admin:</small>
                            <p class="text-slate-700 text-sm font-bold">{{ $item->admin_reply }}</p>
                        </div>
                    @endif
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="mt-8 flex items-center justify-end gap-3">
                    <button onclick="openReplyModal({{ $item->id }}, '{{ addslashes($item->pesan ?? $item->message) }}')" 
                            class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase italic tracking-widest hover:bg-blue-600 transition-all active:scale-95">
                        {{ $item->admin_reply ? 'Ubah Balasan' : 'Balas Ulasan' }}
                    </button>
                    
                    <form action="{{ route('admin.feedback.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-3 bg-rose-50 text-rose-500 rounded-2xl hover:bg-rose-500 hover:text-white transition-all border border-rose-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-slate-50 rounded-[3rem] p-24 text-center border-4 border-dashed border-slate-200">
                <div class="text-7xl mb-6 opacity-20 italic font-black text-slate-400 uppercase tracking-tighter">Belum Ada Ulasan.</div>
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL BALASAN --}}
<div id="replyModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] max-w-lg w-full p-10 shadow-2xl border border-slate-100">
        <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-8">Balas Ulasan</h3>
        <form id="replyForm" method="POST">
            @csrf
            <textarea name="reply" rows="5" class="w-full rounded-[1.5rem] border-slate-100 bg-slate-50 p-6 mb-6 text-sm font-medium focus:ring-4 focus:ring-blue-50 outline-none" placeholder="Tulis balasan admin..." required></textarea>
            <div class="flex gap-4">
                <button type="button" onclick="closeReplyModal()" class="flex-1 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-100">Kirim Balasan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReplyModal(id, text) {
        document.getElementById('replyForm').action = "/admin/feedback/" + id + "/reply";
        document.getElementById('replyModal').classList.remove('hidden');
    }
    function closeReplyModal() { document.getElementById('replyModal').classList.add('hidden'); }
    window.onclick = function(e) { if (e.target == document.getElementById('replyModal')) closeReplyModal(); }
</script>
@endsection