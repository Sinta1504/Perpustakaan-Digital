@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-[3rem] p-10 shadow-xl border border-slate-100">
        
        {{-- Pesan Sukses Pengembalian --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900 uppercase italic">Berikan Respon</h2>
            <p class="text-slate-500 font-medium tracking-tight">
                Bagaimana pengalaman Anda membaca buku <span class="text-blue-600 font-bold">"{{ $loan->book->title }}"</span>?
            </p>
        </div>

        {{-- Form ini akan mengirim data ke storeReview di LoanController --}}
        <form action="{{ route('loans.review.store', $loan->id) }}" method="POST">
            @csrf
            
            {{-- Pilihan Rating --}}
            <div class="mb-8">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-4 text-center">Rating Buku</label>
                <div class="flex justify-center gap-4">
                    <select name="rating" required class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-3 font-bold text-amber-500 outline-none focus:ring-2 focus:ring-amber-400 transition-all">
                        <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                        <option value="4">⭐⭐⭐⭐ (Puas)</option>
                        <option value="3">⭐⭐⭐ (Cukup)</option>
                        <option value="2">⭐⭐ (Kurang)</option>
                        <option value="1">⭐ (Kecewa)</option>
                    </select>
                </div>
            </div>

            {{-- Input Ulasan/Respon --}}
            <div class="mb-8">
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Ulasan / Pesan untuk Admin</label>
                <textarea 
                    name="ulasan" 
                    rows="4" 
                    required 
                    class="w-full bg-slate-50 border border-slate-200 rounded-[2rem] p-6 focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-300"
                    placeholder="Tuliskan ulasan Anda agar admin tahu kualitas buku ini..."></textarea>
                @error('ulasan')
                    <p class="text-red-500 text-xs mt-2 ml-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full bg-blue-600 text-white font-black uppercase tracking-widest text-xs py-5 rounded-2xl shadow-lg shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                    Kirim Ulasan & Selesai
                </button>
                
                <a href="{{ route('pinjaman') }}" class="w-full bg-slate-100 text-slate-500 text-center font-black uppercase tracking-widest text-xs py-5 rounded-2xl hover:bg-slate-200 transition-all">
                    Lewati (Kembali ke Daftar)
                </a>
            </div>
        </form>
    </div>
</div>
@endsection