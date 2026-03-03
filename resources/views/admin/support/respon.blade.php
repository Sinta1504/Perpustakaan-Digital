@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto max-w-3xl">
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">Respon Kendala</h2>
        <p class="text-slate-500 font-medium text-sm">Berikan solusi teknis untuk peminjam.</p>
    </div>

    <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-slate-100">
        <div class="flex items-center gap-4 mb-8 p-6 bg-slate-50 rounded-3xl">
            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-black">
                {{ strtoupper(substr($ticket->user_name, 0, 1)) }}
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase italic">Peminjam</p>
                <p class="font-black text-slate-900 italic">{{ $ticket->user_name }} ({{ $ticket->user_email }})</p>
            </div>
        </div>

        <div class="mb-8">
            <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Masalah yang Dilaporkan</label>
            <div class="px-6 py-4 bg-rose-50 rounded-2xl border border-rose-100">
                <p class="font-black text-rose-600 italic uppercase text-xs">{{ $ticket->kategori_kendala }}</p>
                <p class="text-slate-600 text-sm mt-1 italic">"{{ $ticket->pesan_kendala }}"</p>
            </div>
        </div>

        <form action="{{ route('admin.support.jawab', $ticket->id) }}" method="POST">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Jawaban Solusi</label>
                <textarea name="jawaban" rows="5" required
                    placeholder="Tuliskan instruksi solusi di sini..."
                    class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-8">
                <a href="{{ route('admin.users.index') }}" class="py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[11px] uppercase italic text-center tracking-widest">Batal</a>
                <button type="submit" class="py-4 bg-blue-600 text-white rounded-2xl font-black text-[11px] uppercase italic tracking-widest shadow-lg shadow-blue-200">Kirim Respon</button>
            </div>
        </form>
    </div>
</div>
@endsection