@extends('layouts.app_custom')

@section('content')
<div class="relative bg-white overflow-hidden border-b border-slate-100">
    <div class="container mx-auto px-6 py-24 lg:flex items-center gap-12">
        <div class="lg:w-1/2">
            <span class="inline-block px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-sm font-bold mb-6">
                🚀 Selamat Datang di Perpustakaan Digital
            </span>
            <h1 class="text-6xl font-extrabold text-slate-900 leading-tight">
                Eksplorasi Dunia <br><span class="text-blue-600">Lewat Jendela Digital</span>
            </h1>
            <p class="mt-6 text-xl text-slate-500 leading-relaxed">
                Temukan koleksi buku terbaik, mulai dari teknologi hingga sastra, semua dalam genggaman Anda.
            </p>
            
            <div class="mt-10 max-w-md">
                <form action="{{ route('katalog') }}" method="GET" class="relative group">
                    <input type="text" name="search" placeholder="Cari buku favoritmu..." 
                           class="w-full pl-12 pr-4 py-4 bg-slate-100 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all outline-none shadow-sm">
                    <span class="absolute left-4 top-4 text-xl">🔍</span>
                </form>
            </div>
        </div>

        <div class="lg:w-1/2 mt-12 lg:mt-0 relative">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400" class="rounded-3xl shadow-lg" alt="Book 1">
                    <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400" class="rounded-3xl shadow-lg" alt="Book 2">
                </div>
                <div class="pt-12 space-y-4">
                    <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=400" class="rounded-3xl shadow-lg" alt="Book 3">
                    <img src="https://images.unsplash.com/photo-1550399105-c4db5fb85c18?w=400" class="rounded-3xl shadow-lg" alt="Laskar Pelangi">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-6 py-20">
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-3xl font-bold text-slate-900">Rekomendasi Buku</h2>
        <a href="{{ route('katalog') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @forelse($featuredBooks as $book)
        <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
            <div class="h-64 rounded-2xl overflow-hidden mb-4 bg-slate-200">
                <img src="{{ $book->cover }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="{{ $book->judul }}">
            </div>
            <span class="text-[10px] font-bold text-blue-600 uppercase px-2 py-1 bg-blue-50 rounded">{{ $book->kategori }}</span>
            <h3 class="font-bold text-slate-800 mt-2 truncate">{{ $book->judul }}</h3>
            <p class="text-sm text-slate-500">{{ $book->penulis }}</p>
        </div>
        @empty
        <p class="col-span-full text-center text-slate-400">Belum ada buku untuk ditampilkan.</p>
        @endforelse
    </div>
</div>
@endsection