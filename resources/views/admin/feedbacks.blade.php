@extends('layouts.app_custom')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- Header Dinamis --}}
    <div class="flex justify-between items-end mb-12 border-b border-slate-100 pb-8">
        <div>
            <h1 class="text-4xl font-black text-slate-900 mb-2 tracking-tighter uppercase italic">Suara Peminjam</h1>
            <p class="text-slate-500 text-sm font-medium">Respon ulasan pengguna untuk meningkatkan layanan E-LIB.</p>
        </div>
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-100">
            💬 {{ $feedbacks->count() }} Total Ulasan
        </div>
    </div>

    {{-- Looping Data dari Database --}}
    <div class="space-y-8">
        @forelse($feedbacks as $item)
        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden transition-all hover:border-blue-200">
            <div class="p-10 flex flex-col md:flex-row gap-10">
                
                {{-- Info User Dinamis --}}
                <div class="md:w-1/3">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center font-black text-white text-lg shadow-inner">
                            {{ substr($item->user->name, 0, 2) }}
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">{{ $item->user->name }}</h4>
                            <p class="text-[10px] text-blue-600 uppercase font-black tracking-widest">Peminjam Buku</p>
                        </div>
                    </div>
                    <div class="flex gap-1 mb-2 text-amber-400 text-xs">
                        @for($i=0; $i<$item->rating; $i++) ★ @endfor
                    </div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-tighter">
                        {{ $item->created_at->format('d M Y') }}
                    </p>
                </div>

                <div class="md:w-2/3 flex flex-col">
                    {{-- INFO BUKU DAN PENULIS (DI PERBAIKI DI SINI) --}}
                    <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 rounded-[1.5rem] border border-slate-100 shadow-sm">
                        <img src="{{ $item->book->cover_url ?? asset('storage/'.$item->book->cover) }}" 
                             class="w-12 h-16 object-cover rounded-xl shadow-md border-2 border-white"
                             onerror="this.src='https://placehold.co/400x600?text=No+Cover'">
                        <div>
                            <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">Mengulas Buku:</p>
                            <h4 class="font-bold text-slate-900 leading-tight">{{ $item->book->judul }}</h4>
                            <p class="text-xs text-slate-500 font-medium italic">Karya: {{ $item->book->penulis }}</p>
                        </div>
                    </div>

                    {{-- Pesan dari User --}}
                    <div class="bg-white p-6 rounded-[2rem] mb-6 border border-blue-50 italic text-slate-600 text-sm leading-relaxed shadow-sm relative">
                        <span class="absolute -top-3 left-6 bg-white px-2 text-blue-300 text-2xl font-serif">“</span>
                        {{ $item->pesan }}
                        <span class="absolute -bottom-6 right-6 text-blue-300 text-2xl font-serif">”</span>
                    </div>

                    {{-- Logika Tampilan Balasan --}}
                    @if($item->admin_reply)
                        {{-- Jika Sudah Ada Balasan --}}
                        <div class="bg-slate-900 p-6 rounded-[2rem] text-white shadow-2xl relative border-l-8 border-blue-600">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em]">Respon Admin:</span>
                            </div>
                            <p class="text-sm font-medium leading-relaxed italic text-slate-300">
                                "{{ $item->admin_reply }}"
                            </p>
                        </div>
                    @else
                        {{-- Jika Belum Ada Balasan (Form Input) --}}
                        <form action="{{ route('admin.feedback.reply', $item->id) }}" method="POST" class="relative">
                            @csrf
                            <textarea name="reply" rows="2" required
                                class="w-full bg-blue-50/50 border-2 border-blue-100 rounded-[2rem] p-6 text-sm text-slate-700 focus:outline-none focus:border-blue-400 transition-all placeholder:text-blue-300 font-medium"
                                placeholder="Tulis balasan untuk {{ $item->user->name }}..."></textarea>
                            
                            <button type="submit" class="absolute right-4 bottom-4 bg-blue-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg">
                                Balas Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        {{-- Tampilan jika belum ada ulasan sama sekali --}}
        <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
            <p class="text-slate-400 font-bold uppercase tracking-widest italic">Belum ada suara peminjam yang masuk</p>
        </div>
        @endforelse
    </div>
</div>
@endsection