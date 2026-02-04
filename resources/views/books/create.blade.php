@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('katalog') }}" class="text-blue-600 font-bold text-sm hover:underline flex items-center gap-2 mb-2">
                ← Kembali ke Katalog
            </a>
            <h2 class="text-3xl font-black text-slate-900">Tambah Koleksi Buku 📚</h2>
            <p class="text-slate-500">Lengkapi formulir di bawah untuk menambah buku baru ke sistem E-LIB.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl border border-slate-100">
            <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Judul Buku</label>
                        <input type="text" name="judul" required placeholder="Contoh: Belajar Laravel 11 untuk Pemula" 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Penulis</label>
                        <input type="text" name="penulis" required placeholder="Nama Lengkap Penulis" 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Kategori</label>
                        <select name="kategori" required 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none appearance-none">
                            <option value="">Pilih Kategori</option>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Sastra">Sastra</option>
                            <option value="Sains">Sains</option>
                            <option value="Bisnis">Bisnis</option>
                            <option value="Desain">Desain</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Jumlah Stok</label>
                        <input type="number" name="stok" required min="1" placeholder="Masukan angka (Contoh: 10)" 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">URL Cover Buku</label>
                        <input type="text" name="cover" required placeholder="https://link-gambar.jpg" 
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Sinopsis / Deskripsi</label>
                    <textarea name="deskripsi" rows="4" placeholder="Tuliskan ringkasan singkat isi buku agar pembaca tertarik..." 
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none resize-none"></textarea>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-4">
                    <button type="submit" class="flex-grow bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-blue-200 transition transform hover:scale-[1.02] active:scale-95">
                        SIMPAN BUKU SEKARANG
                    </button>
                    <a href="{{ route('katalog') }}" class="md:w-1/3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-5 rounded-2xl transition text-center">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection