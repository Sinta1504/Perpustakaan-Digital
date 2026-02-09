@extends('layouts.app_custom')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-slate-900 mb-3 tracking-tighter">Hubungi Kami</h1>
        <p class="text-slate-500 max-w-lg mx-auto text-sm font-medium">Butuh bantuan mengenai peminjaman buku? Kami siap melayani Anda.</p>
    </div>

    {{-- 3 Kotak Berwarna Selaras & Aesthetic --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Kotak 1: Deep Slate (Email) --}}
        <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl group transition-all duration-300 hover:scale-[1.02]">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-xl mb-6 shadow-lg shadow-blue-900/40">
                <span class="text-white">📧</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Email Resmi</h3>
            <p class="text-slate-400 text-xs mb-6 leading-relaxed">Kirimkan pertanyaan teknis atau kerjasama resmi.</p>
            <a href="mailto:support@elib.id" class="text-blue-400 font-bold text-[11px] uppercase tracking-widest hover:text-white transition-colors">
                support@elib.id
            </a>
        </div>

        {{-- Kotak 2: Electric Blue (WhatsApp Admin) --}}
        <div class="bg-blue-600 p-8 rounded-[2.5rem] shadow-xl shadow-blue-200 group transition-all duration-300 hover:scale-[1.02]">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-xl mb-6">
                <span class="text-white">💬</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Hubungi Admin</h3>
            <p class="text-blue-100 text-xs mb-6 leading-relaxed">Tanyakan status peminjaman buku langsung ke Admin.</p>
            <a href="https://wa.me/6282323531345?text=Halo%20Admin%20E-LIB,%20saya%20ingin%20bertanya%20mengenai%20peminjaman%20buku..." 
               target="_blank" 
               class="inline-block bg-white text-blue-600 px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all shadow-md">
                Mulai Chat
            </a>
        </div>

        {{-- Kotak 3: Soft Indigo (Jam Layanan - Sudah Berwarna) --}}
        <div class="bg-indigo-500 p-8 rounded-[2.5rem] shadow-xl shadow-indigo-200 group transition-all duration-300 hover:scale-[1.02]">
            <div class="w-12 h-12 bg-indigo-400 rounded-2xl flex items-center justify-center text-xl mb-6 shadow-md border border-indigo-300">
                <span class="text-white">⏰</span>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Jam Layanan</h3>
            <p class="text-indigo-100 text-xs mb-6 leading-relaxed">Admin aktif melayani pada jam operasional:</p>
            
            <div class="bg-indigo-600/40 backdrop-blur-sm p-4 rounded-2xl border border-indigo-400">
                <p class="text-indigo-200 font-bold text-[9px] uppercase tracking-widest mb-1">Senin - Jumat</p>
                <p class="text-white font-black text-xl tracking-tight">08.00 - 16.00</p>
            </div>
        </div>

    </div>

    {{-- Footer Small --}}
    <div class="mt-12 text-center">
        <p class="text-slate-400 text-[9px] font-black uppercase tracking-[0.4em]">E-LIB v1.0 • Digital Solution</p>
    </div>
</div>
@endsection