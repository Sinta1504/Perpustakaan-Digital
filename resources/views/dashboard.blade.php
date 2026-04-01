@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">

    {{-- 1. Header Dashboard: Banner Sambutan --}}
    <div class="relative overflow-hidden bg-slate-900 rounded-[3rem] p-10 mb-12 text-white shadow-2xl">
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-black leading-tight mb-2">
                Halo, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-slate-400 text-lg font-medium max-w-lg">
                Mau baca buku apa hari ini? Temukan koleksi terbaik untuk menemani waktu luangmu.
            </p>
        </div>
        {{-- Dekorasi Elemen Background --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 blur-[100px] rounded-full -mr-20 -mt-20"></div>
    </div>

    {{-- 2. Bagian Judul Rekomendasi --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                Rekomendasi Buku 📚
            </h2>
            <p class="text-slate-500 font-medium mt-1">
                Buku-buku pilihan yang paling banyak dibaca oleh pengguna lain.
            </p>
        </div>
        
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-sm group">
            Lihat Semua Katalog 
            <span class="group-hover:translate-x-1 transition-transform">→</span>
        </a>
    </div>

    {{-- 3. Grid Kartu Buku (Tampilan Baru Versi Katalog) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($recommendedBooks as $book)
        <div class="bg-white rounded-[2.5rem] p-6 shadow-xl hover:shadow-2xl transition-all duration-500 border border-slate-50 group">
            
            {{-- Visual Buku --}}
            <div class="relative mb-6 overflow-hidden rounded-[2rem] aspect-[3/4] bg-slate-100 flex items-center justify-center">
                @php
                    $imageSrc = Str::startsWith($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover);
                @endphp
                
                <img src="{{ $imageSrc }}" 
                     alt="{{ $book->judul }}" 
                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                     onerror="this.src='https://placehold.co/400x600?text={{ urlencode($book->judul) }}'">
                
                {{-- Badge Kategori --}}
                <span class="absolute top-4 left-4 bg-blue-600 text-white px-4 py-1.5 rounded-xl font-black text-[9px] uppercase tracking-widest shadow-lg">
                    {{ $book->kategori }}
                </span>
            </div>

            {{-- Info Buku --}}
            <div class="px-2">
                <p class="text-blue-400 font-black text-[9px] uppercase tracking-[0.2em] mb-1">Informasi Buku</p>
                <h3 class="text-xl font-black text-slate-900 uppercase italic leading-tight mb-1 truncate" title="{{ $book->judul }}">
                    {{ $book->judul }}
                </h3>
                <p class="text-slate-400 font-bold text-[11px] uppercase tracking-widest mb-6">
                    Karya: <span class="text-slate-600">{{ $book->penulis }}</span>
                </p>

                {{-- Info Stok & ID (Mini Version) --}}
                <div class="flex gap-2 mb-6">
                    <div class="bg-slate-50 px-3 py-2 rounded-xl border border-slate-100 flex-1 text-center">
                        <p class="text-[8px] text-slate-400 font-black uppercase tracking-tighter">Stok</p>
                        <p class="text-[10px] font-black text-green-500">{{ $book->stok }} Tersedia</p>
                    </div>
                    <div class="bg-slate-50 px-3 py-2 rounded-xl border border-slate-100 flex-1 text-center">
                        <p class="text-[8px] text-slate-400 font-black uppercase tracking-tighter">ID</p>
                        <p class="text-[10px] font-black text-slate-800">#BK-{{ $book->id }}</p>
                    </div>
                </div>

                {{-- Tombol Pinjam --}}
                <a href="{{ route('loans.create', $book->id) }}" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-[0_15px_30px_rgba(37,99,235,0.2)] transition transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 uppercase tracking-widest text-[10px]">
                    <span>📖</span> Pinjam Sekarang
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($recommendedBooks->isEmpty())
    <div class="bg-slate-100 rounded-[3rem] p-20 text-center border-4 border-dashed border-slate-200">
        <p class="text-slate-400 font-black uppercase tracking-widest italic">Belum ada buku rekomendasi hari ini.</p>
    </div>
    @endif

    {{-- 4. Bagian Suara Peminjam --}}
    <div class="mt-24 mb-10">
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
            Suara Peminjam 💬
        </h2>
        <p class="text-slate-500 font-medium mt-1">
            Apa kata mereka tentang koleksi buku di E-LIB?
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        @foreach($feedbacks as $feedback)
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
            <div class="flex items-start gap-4 mb-4">
                {{-- Menampilkan Gambar Buku --}}
                <div class="w-16 h-20 flex-shrink-0 bg-slate-50 rounded-xl overflow-hidden shadow-inner border border-slate-100">
                    @php
                        $coverBook = $feedback->book->cover ?? '';
                        $urlFeedbackCover = \Illuminate\Support\Str::startsWith($coverBook, 'http') 
                                            ? $coverBook 
                                            : asset('storage/' . $coverBook);
                    @endphp
                    <img src="{{ $coverBook ? $urlFeedbackCover : 'https://placehold.co/400x600?text=No+Cover' }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                         alt="Cover Buku">
                </div>

                <div class="flex-1">
                    <h5 class="font-black text-slate-900 uppercase italic text-xs line-clamp-1">
                        {{ $feedback->book->judul ?? 'Buku Tanpa Judul' }}
                    </h5>
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest mt-1">
                        {{ $feedback->rating }}/5 ⭐
                    </p>
                    <div class="mt-2">
                        <p class="text-xs text-slate-600 font-medium italic line-clamp-3">
                            "{{ $feedback->pesan }}"
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info User Peminjam --}}
            <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-[10px] font-black uppercase">
                    {{ substr($feedback->user->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-800 uppercase italic">{{ $feedback->user->name ?? 'Anonim' }}</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Peminjam Setia</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection