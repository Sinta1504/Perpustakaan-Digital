@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto max-w-2xl">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">Edit Data Buku</h2>
        <p class="text-slate-500 font-medium text-sm">Perbarui informasi koleksi perpustakaan Anda.</p>
    </div>

    <div class="bg-white rounded-[3rem] p-10 shadow-xl border border-slate-100">
        <form action="{{ route('books.update', $book->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Judul Buku</label>
                    <input type="text" name="judul" value="{{ $book->judul }}" required
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Penulis</label>
                        <input type="text" name="penulis" value="{{ $book->penulis }}" required
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Kategori</label>
                        <input type="text" name="kategori" value="{{ $book->kategori }}" required
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Stok Unit</label>
                        <input type="number" name="stok" value="{{ $book->stok }}" required min="0"
                            class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Kondisi</label>
                        <select name="status" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 transition-all">
                            <option value="baik" {{ $book->status == 'baik' ? 'selected' : '' }}>BAIK</option>
                            <option value="rusak" {{ $book->status == 'rusak' ? 'selected' : '' }}>RUSAK</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Sinopsis</label>
                    <textarea name="sinopsis" rows="4" 
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl font-bold text-slate-900 focus:ring-2 focus:ring-amber-500 transition-all">{{ $book->sinopsis }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-6">
                    <a href="{{ route('admin.inventory') }}" 
                        class="py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[11px] uppercase italic text-center tracking-widest hover:bg-slate-200 transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                        class="py-4 bg-amber-500 text-white rounded-2xl font-black text-[11px] uppercase italic tracking-widest shadow-lg shadow-amber-200 hover:bg-amber-600 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection