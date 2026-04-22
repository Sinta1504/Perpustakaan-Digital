@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">

    @if(auth()->user()->role == 'admin')
        {{-- =========================================================== --}}
        {{-- TAMPILAN KHUSUS ADMIN: FORM PENGATURAN KONTAK              --}}
        {{-- =========================================================== --}}
        <div class="mb-12">
            <h2 class="text-4xl font-black text-slate-800 uppercase italic tracking-tighter leading-none">Konfigurasi Jalur Dukungan</h2>
            <p class="text-slate-500 font-medium mt-3 text-lg">Kelola informasi kontak yang akan ditampilkan pada halaman bantuan User.</p>
        </div>

        <form action="#" method="POST"> 
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Edit Email Resmi --}}
                <div class="bg-slate-900 p-10 rounded-[3rem] text-white shadow-2xl transition-all hover:ring-4 hover:ring-blue-500/20">
                    <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center mb-8 text-blue-400">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <h4 class="font-black uppercase italic mb-4 text-sm tracking-widest">Update Email Resmi</h4>
                    <input type="email" name="email" value="SUPPORT@ELIB.ID" 
                           class="w-full bg-slate-800 border-none rounded-2xl py-4 px-5 text-blue-400 font-black focus:ring-2 focus:ring-blue-500 transition-all uppercase tracking-wider">
                </div>

                {{-- Edit WhatsApp Admin --}}
                <div class="bg-blue-600 p-10 rounded-[3rem] text-white shadow-2xl shadow-blue-100 transition-all hover:ring-4 hover:ring-white/20">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-8">
                        <i class="fab fa-whatsapp text-3xl text-white"></i>
                    </div>
                    <h4 class="font-black uppercase italic mb-4 text-sm tracking-widest text-blue-100">Update WhatsApp</h4>
                    <input type="text" name="whatsapp" value="6282323531345" 
                           class="w-full bg-white/10 border-none rounded-2xl py-4 px-5 text-white font-black placeholder:text-blue-200 focus:ring-2 focus:ring-white transition-all tracking-widest">
                    <p class="text-[10px] mt-3 font-bold text-blue-100 uppercase opacity-70 italic">* Gunakan kode negara tanpa tanda +</p>
                </div>

                {{-- Edit Waktu Layanan --}}
                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm transition-all hover:shadow-xl">
                    <div class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mb-8 text-rose-500 border border-rose-100">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <h4 class="font-black text-slate-800 uppercase italic mb-4 text-sm tracking-widest">Set Jam Operasional</h4>
                    <input type="text" name="jam_layanan" value="08.00 - 16.00" 
                           class="w-full bg-slate-50 border-none rounded-2xl py-4 px-5 text-blue-600 font-black focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </div>

            <div class="mt-12 text-right">
                <button type="submit" class="group bg-slate-900 text-white px-12 py-5 rounded-3xl font-black uppercase italic tracking-widest hover:bg-blue-600 transition-all shadow-2xl shadow-slate-200 flex items-center gap-3 ml-auto">
                    <span>Simpan Konfigurasi</span>
                    <i class="fas fa-check-circle transition-transform group-hover:scale-125"></i>
                </button>
            </div>
        </form>

    @else
        {{-- =========================================================== --}}
        {{-- TAMPILAN KHUSUS USER: HALAMAN INFO HUBUNGI KAMI            --}}
        {{-- =========================================================== --}}
        <div class="text-center mb-16">
            <h1 class="text-5xl font-black text-slate-900 mb-4 tracking-tighter italic uppercase">Hubungi Kami</h1>
            <p class="text-slate-500 font-medium max-w-2xl mx-auto text-lg">Layanan dukungan E-LIB siap membantu Anda setiap saat melalui jalur komunikasi resmi kami.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {{-- Card Email --}}
            <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl transition-transform hover:-translate-y-2 group">
                <div class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center mb-12 shadow-lg shadow-blue-500/10 transition-transform group-hover:rotate-12">
                    <i class="fas fa-envelope text-2xl text-blue-400"></i>
                </div>
                <h3 class="text-2xl font-black mb-4 uppercase italic">Email Resmi</h3>
                <p class="text-slate-400 text-sm font-medium mb-8">Kirimkan pertanyaan teknis atau kerjasama melalui email resmi kami.</p>
                <a href="mailto:SUPPORT@ELIB.ID" class="text-blue-400 font-black text-sm uppercase tracking-widest hover:text-white transition-colors underline decoration-2 underline-offset-8">SUPPORT@ELIB.ID</a>
            </div>

            {{-- Card WhatsApp --}}
            <div class="bg-blue-600 rounded-[3rem] p-10 text-white shadow-2xl shadow-blue-200 transition-transform hover:-translate-y-2 group">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-12 shadow-lg transition-transform group-hover:scale-110">
                    <i class="fab fa-whatsapp text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black mb-4 uppercase italic">Hubungi Admin</h3>
                <p class="text-blue-100 text-sm font-medium mb-8">Tanyakan status peminjaman atau bantuan buku langsung kepada Admin kami.</p>
                
                @php
                    $pesanWA = "Halo Admin E-LIB, saya " . Auth::user()->name . " ingin bertanya mengenai layanan perpustakaan...";
                    $urlWA = "https://wa.me/6282323531345?text=" . urlencode($pesanWA);
                @endphp

                <a href="{{ $urlWA }}" target="_blank" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-slate-100 transition-all flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-lg"></i> Mulai Chat
                </a>
            </div>

            {{-- Card Jam Layanan --}}
            <div class="bg-white border border-slate-100 rounded-[3rem] p-10 shadow-sm transition-transform hover:-translate-y-2 group">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-12 shadow-sm border border-slate-100 transition-colors group-hover:bg-rose-50">
                    <i class="fas fa-clock text-2xl text-rose-500"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase italic">Jam Layanan</h3>
                <p class="text-slate-500 text-sm font-medium mb-8">Admin kami aktif melayani pada jam operasional kantor berikut:</p>
                
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 group-hover:border-rose-100 transition-colors">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Senin - Jumat</p>
                    <p class="text-3xl font-black text-blue-600 tracking-tighter">08.00 - 16.00</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-2 italic tracking-wider">Waktu Indonesia Barat (WIB)</p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection