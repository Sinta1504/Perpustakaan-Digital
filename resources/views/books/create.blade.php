@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="mb-10">
            <a href="{{ route('admin.inventory') }}" class="text-blue-600 font-black text-[10px] uppercase italic tracking-widest hover:underline flex items-center gap-2 mb-4">
                ← Kembali ke Inventori
            </a>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">Tambah Koleksi Buku 📚</h2>
            <p class="text-slate-500 font-medium text-sm">Lengkapi formulir di bawah untuk menambah buku baru ke sistem E-LIB.</p>
        </div>

        <div class="bg-white rounded-[3rem] p-10 md:p-14 shadow-xl border border-slate-100">
            {{-- Tambahkan enctype untuk mendukung upload file gambar --}}
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Judul Lengkap Buku</label>
                        <input type="text" name="judul" required placeholder="Contoh: Belajar Laravel 11 untuk Pemula" 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Penulis</label>
                        <input type="text" name="penulis" required placeholder="Nama Lengkap Penulis" 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Kategori</label>
                        <select name="kategori" required 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all outline-none appearance-none cursor-pointer">
                            <option value="">Pilih Kategori</option>
                            <option value="Teknologi">TEKNOLOGI</option>
                            <option value="Sastra">SASTRA</option>
                            <option value="Sains">SAINS</option>
                            <option value="Bisnis">BISNIS</option>
                            <option value="Desain">DESAIN</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Jumlah Stok</label>
                        <input type="number" name="stok" required min="1" placeholder="0" 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">File Cover Buku</label>
                        <input type="file" name="cover" required 
                            class="w-full px-4 py-3 bg-slate-50 border-none rounded-2xl font-bold text-slate-500 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase italic tracking-widest mb-2 ml-4">Sinopsis / Deskripsi</label>
                    <textarea name="sinopsis" rows="4" placeholder="Tuliskan ringkasan singkat isi buku agar pembaca tertarik..." 
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-none font-bold text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all outline-none resize-none"></textarea>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-4">
                    <button type="submit" class="flex-grow bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-200 transition transform hover:scale-[1.02] active:scale-95 text-[11px] uppercase italic tracking-widest">
                        Simpan Buku Sekarang
                    </button>
                    <a href="{{ route('admin.inventory') }}" class="md:w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-400 font-black py-5 rounded-2xl transition text-center text-[11px] uppercase italic tracking-widest">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection