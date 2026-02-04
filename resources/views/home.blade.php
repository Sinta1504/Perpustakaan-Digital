@extends('layouts.app_custom')

@section('content')
<section class="container mx-auto px-6 py-16 flex flex-col md:flex-row items-center gap-12">
    <div class="md:w-1/2 space-y-6">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-sm font-bold animate-bounce">
            🚀 <span>Selamat Datang di Perpustakaan Digital</span>
        </div>
        <h1 class="text-6xl font-black text-slate-900 leading-tight">
            Eksplorasi Dunia <br>
            <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Lewat Jendela Digital</span>
        </h1>
        <p class="text-lg text-slate-600 leading-relaxed max-w-lg">
            Temukan koleksi buku terbaik, mulai dari teknologi hingga sastra, semua dalam genggaman Anda. Kelola peminjaman dengan mudah dan cepat.
        </p>
        <div class="flex gap-4">
            <a href="/katalog" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold transition-all shadow-xl shadow-blue-200">
                Mulai Menjelajah
            </a>
            <a href="/pinjaman" class="bg-white border border-slate-200 text-slate-700 px-8 py-4 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                Lihat Pinjaman
            </a>
        </div>
    </div>
    
    <div class="md:w-1/2 grid grid-cols-2 gap-4">
        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800" class="rounded-[2rem] shadow-2xl mt-8" alt="Books">
        <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=800" class="rounded-[2rem] shadow-2xl" alt="Library">
    </div>
</section>

<section class="py-12 bg-white border-y border-slate-100">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-4xl font-black text-blue-600">500+</p>
                <p class="text-slate-500 font-bold uppercase text-xs tracking-widest mt-2">Koleksi Buku</p>
            </div>
            <div>
                <p class="text-4xl font-black text-indigo-600">1.2k</p>
                <p class="text-slate-500 font-bold uppercase text-xs tracking-widest mt-2">Total Pinjaman</p>
            </div>
            <div>
                <p class="text-4xl font-black text-blue-600">24/7</p>
                <p class="text-slate-500 font-bold uppercase text-xs tracking-widest mt-2">Akses Digital</p>
            </div>
            <div>
                <p class="text-4xl font-black text-indigo-600">100%</p>
                <p class="text-slate-500 font-bold uppercase text-xs tracking-widest mt-2">Gratis</p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-black text-slate-900 mb-2">Kategori Populer</h2>
            <p class="text-slate-500">Pilih topik yang paling kamu minati hari ini</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <a href="{{ route('katalog', ['search' => 'Teknologi']) }}" 
               class="group relative overflow-hidden bg-gradient-to-br from-blue-100 to-blue-200 p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-blue-200">
                <div class="w-16 h-16 bg-white/80 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    💻
                </div>
                <h3 class="text-xl font-bold text-blue-900 mb-1">Teknologi</h3>
                <p class="text-blue-600/70 font-bold text-sm">120+ Koleksi</p>
                <div class="absolute -bottom-4 -right-4 text-blue-300/20 text-8xl font-black group-hover:scale-110 transition-transform">
                    01
                </div>
            </a>

            <a href="{{ route('katalog', ['search' => 'Bisnis']) }}" 
               class="group relative overflow-hidden bg-gradient-to-br from-orange-100 to-orange-200 p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-orange-200">
                <div class="w-16 h-16 bg-white/80 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm group-hover:bg-orange-600 group-hover:text-white transition-all duration-500">
                    📈
                </div>
                <h3 class="text-xl font-bold text-orange-900 mb-1">Bisnis</h3>
                <p class="text-orange-600/70 font-bold text-sm">85+ Koleksi</p>
                <div class="absolute -bottom-4 -right-4 text-orange-300/20 text-8xl font-black group-hover:scale-110 transition-transform">
                    02
                </div>
            </a>

            <a href="{{ route('katalog', ['search' => 'Desain']) }}" 
               class="group relative overflow-hidden bg-gradient-to-br from-purple-100 to-purple-200 p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-purple-200">
                <div class="w-16 h-16 bg-white/80 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                    🎨
                </div>
                <h3 class="text-xl font-bold text-purple-900 mb-1">Desain</h3>
                <p class="text-purple-600/70 font-bold text-sm">50+ Koleksi</p>
                <div class="absolute -bottom-4 -right-4 text-purple-300/20 text-8xl font-black group-hover:scale-110 transition-transform">
                    03
                </div>
            </a>

            <a href="{{ route('katalog', ['search' => 'Self Dev']) }}" 
               class="group relative overflow-hidden bg-gradient-to-br from-green-100 to-green-200 p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-green-200">
                <div class="w-16 h-16 bg-white/80 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm group-hover:bg-green-600 group-hover:text-white transition-all duration-500">
                    🌱
                </div>
                <h3 class="text-xl font-bold text-green-900 mb-1">Self Dev</h3>
                <p class="text-green-600/70 font-bold text-sm">200+ Koleksi</p>
                <div class="absolute -bottom-4 -right-4 text-green-300/20 text-8xl font-black group-hover:scale-110 transition-transform">
                    04
                </div>
            </a>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-black text-slate-900">Rekomendasi Buku</h2>
                <p class="text-slate-500 mt-2">Buku-buku pilihan yang paling banyak dibaca</p>
            </div>
            <a href="/katalog" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                Lihat Semua Katalog <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach($books as $book)
            <div class="group">
                <div class="relative overflow-hidden rounded-[2rem] aspect-[3/4] mb-4 shadow-lg transition-all group-hover:shadow-2xl bg-slate-100">
                    
                    {{-- BAGIAN YANG DIUBAH: Menggunakan cover_url dari database --}}
                    <img src="{{ $book->cover_url ?? 'https://placehold.co/400x600?text=Cari+Cover...' }}" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                         alt="{{ $book->judul }}"
                         onerror="this.onerror=null;this.src='https://placehold.co/400x600?text=Gambar+Tidak+Ada';">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                        <a href="{{ route('loans.create', $book->id) }}" class="w-full bg-white text-slate-900 py-3 rounded-xl font-bold text-center">
                            Pinjam Sekarang
                        </a>
                    </div>
                </div>
                <h3 class="font-bold text-slate-900 truncate">{{ $book->judul }}</h3>
                <p class="text-sm text-slate-500">{{ $book->penulis }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection