@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto max-w-4xl">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h2 class="text-4xl font-black text-slate-900 uppercase italic mb-4">Hubungi Kami</h2>
        <p class="text-slate-500 font-medium">Punya kendala teknis atau ide fitur baru? Kami siap mendengarkan.</p>
    </div>

    {{-- Info Kontak --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 text-center shadow-sm">
            <div class="text-3xl mb-3">📍</div>
            <h4 class="font-black text-slate-900 uppercase text-xs">Lokasi</h4>
            <p class="text-slate-500 text-xs mt-2 italic">Semarang, Jawa Tengah</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 text-center shadow-sm">
            <div class="text-3xl mb-3">✉️</div>
            <h4 class="font-black text-slate-900 uppercase text-xs">Email</h4>
            <p class="text-slate-500 text-xs mt-2 italic">support@elib.com</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 text-center shadow-sm">
            <div class="text-3xl mb-3">📞</div>
            <h4 class="font-black text-slate-900 uppercase text-xs">WhatsApp</h4>
            <p class="text-slate-500 text-xs mt-2 italic">+62 812 3456 789</p>
        </div>
    </div>

    {{-- Form Masukan Sistem --}}
    <div class="bg-slate-900 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
        {{-- Dekorasi --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        
        <form action="{{ route('feedback.store') }}" method="POST" class="relative z-10 space-y-6">
            @csrf
            {{-- Karena ini saran sistem, book_id otomatis null (kosong) --}}
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Kategori Pesan</label>
                    <select name="kategori" required class="w-full bg-slate-800 border-2 border-slate-700 rounded-2xl text-white px-6 py-4 font-bold focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                        <option value="saran">💡 SARAN SISTEM</option>
                        <option value="kritik">🔥 KRITIK SISTEM</option>
                        <option value="lainnya">💬 LAINNYA</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Rating Aplikasi (1-5)</label>
                    <select name="rating" class="w-full bg-slate-800 border-2 border-slate-700 rounded-2xl text-white px-6 py-4 font-bold focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                        <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                        <option value="3">⭐⭐⭐ (Cukup)</option>
                        <option value="2">⭐⭐ (Kurang)</option>
                        <option value="1">⭐ (Buruk)</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Isi Masukan / Pesan</label>
                <textarea name="pesan" rows="4" required placeholder="Tuliskan detail masukan Anda di sini (min. 5 karakter)..." 
                          class="w-full bg-slate-800 border-2 border-slate-700 rounded-3xl text-white px-6 py-4 font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shadow-blue-900/40 transform hover:-translate-y-1">
                Kirim Masukan Sekarang
            </button>
        </form>
    </div>
</div>
@endsection