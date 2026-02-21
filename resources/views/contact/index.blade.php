@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-16">
        <h1 class="text-5xl font-black text-slate-900 mb-4 tracking-tighter">Hubungi Kami</h1>
        <p class="text-slate-500 font-medium max-w-2xl mx-auto">Layanan dukungan E-LIB siap membantu Anda setiap saat melalui jalur komunikasi resmi kami.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        {{-- Card Email --}}
        <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl transition-transform hover:-translate-y-2">
            <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center mb-12 shadow-lg shadow-blue-500/10">
                <i class="fas fa-envelope text-2xl text-blue-400"></i>
            </div>
            <h3 class="text-2xl font-black mb-4 uppercase italic">Email Resmi</h3>
            <p class="text-slate-400 text-sm font-medium mb-8">Kirimkan pertanyaan teknis atau kerjasama melalui email resmi kami.</p>
            <a href="mailto:SUPPORT@ELIB.ID" class="text-blue-400 font-black text-sm uppercase tracking-widest hover:text-white transition-colors">SUPPORT@ELIB.ID</a>
        </div>

        {{-- Card WhatsApp dengan Pesan Otomatis --}}
        <div class="bg-blue-600 rounded-[3rem] p-10 text-white shadow-2xl shadow-blue-200 transition-transform hover:-translate-y-2">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-12 shadow-lg">
                <i class="fab fa-whatsapp text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black mb-4 uppercase italic">Hubungi Admin</h3>
            <p class="text-blue-100 text-sm font-medium mb-8">Tanyakan status peminjaman atau bantuan buku langsung kepada Admin kami.</p>
            
            {{-- Pesan Otomatis: Halo Admin E-LIB, saya [Nama User] ingin bertanya... --}}
            @php
                $pesanWA = "Halo Admin E-LIB, saya " . Auth::user()->name . " ingin bertanya mengenai layanan perpustakaan...";
                $urlWA = "https://wa.me/6282323531345?text=" . urlencode($pesanWA);
            @endphp

            <a href="{{ $urlWA }}" target="_blank" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-slate-100 transition-all flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp text-lg"></i> Mulai Chat
            </a>
        </div>

        {{-- Card Jam Layanan --}}
        <div class="bg-slate-50 border border-slate-100 rounded-[3rem] p-10 transition-transform hover:-translate-y-2">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-12 shadow-sm border border-slate-100">
                <i class="fas fa-clock text-2xl text-rose-500"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase italic">Jam Layanan</h3>
            <p class="text-slate-500 text-sm font-medium mb-8">Admin kami aktif melayani pada jam operasional kantor berikut:</p>
            
            <div class="bg-white p-6 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Senin - Jumat</p>
                <p class="text-3xl font-black text-blue-600 tracking-tighter">08.00 - 16.00</p>
                <p class="text-[9px] font-bold text-slate-400 uppercase mt-2">Waktu Indonesia Barat</p>
            </div>
        </div>
    </div>
</div>
@endsection