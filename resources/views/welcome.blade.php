<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-LIB | Perpustakaan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        .floating { animation: floating 3s ease-in-out infinite; }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-white text-slate-900">

    <nav class="container mx-auto px-6 py-6 flex justify-between items-center relative z-10">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 p-2 rounded-xl shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <span class="text-2xl font-black tracking-tighter text-blue-900 uppercase italic">E-LIB</span>
        </div>

        <div class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    {{-- Jika sudah login (Admin/Zara/Sinta), muncul tombol Dashboard --}}
                    <a href="{{ url('/dashboard') }}" class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-bold text-sm uppercase tracking-widest shadow-xl hover:scale-105 transition-all">
                        Masuk Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-sm text-slate-600 hover:text-blue-600 uppercase tracking-widest transition-all">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold text-sm uppercase tracking-widest shadow-xl shadow-blue-100 hover:scale-105 transition-all">Daftar Baru</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <section class="container mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12 relative">
        <div class="md:w-3/5 relative z-10">
            <div class="inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-full mb-6">
                <p class="text-[10px] font-black uppercase tracking-[0.2em]">Platform Literasi Digital Masa Kini</p>
            </div>
            <h1 class="text-6xl md:text-7xl font-black text-slate-900 leading-[1.1] tracking-tighter mb-8 uppercase italic">
                Eksplorasi Dunia <br>
                <span class="text-blue-600 underline decoration-slate-200">Lewat Perpustakaan Digital</span>
            </h1>
            <p class="text-lg text-slate-500 leading-relaxed mb-10 max-w-xl">
                Temukan ribuan koleksi buku terbaik, kelola peminjaman dengan sistem cerdas, dan bagikan pengalaman membaca Anda dalam satu platform yang terintegrasi.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white text-center px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-blue-200 hover:-translate-y-1 transition-all">
                        Lanjutkan Membaca
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-slate-900 text-white text-center px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] shadow-2xl hover:-translate-y-1 transition-all">
                        Mulai Jelajahi Katalog
                    </a>
                    <a href="#fitur" class="bg-white border-2 border-slate-100 text-center px-10 py-5 rounded-[2rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">
                        Pelajari Fitur
                    </a>
                @endauth
            </div>
        </div>

        <div class="md:w-2/5 relative">
            <div class="floating relative z-10">
                <div class="bg-blue-600 rounded-[3rem] p-4 rotate-3 shadow-2xl shadow-blue-200">
                    <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=800&q=80" 
                         alt="Digital Library" 
                         class="rounded-[2.5rem] -rotate-3 transition-transform hover:rotate-0 duration-500">
                </div>
                <div class="absolute -bottom-10 -left-10 bg-white p-8 rounded-[2.5rem] shadow-2xl border border-slate-50 hidden sm:block">
                    <p class="text-4xl font-black text-blue-600 tracking-tighter italic">100%</p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Digital & Efisien</p>
                </div>
            </div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-blue-50 rounded-full -z-0 opacity-50 blur-3xl"></div>
        </div>
    </section>

    <section id="fitur" class="bg-slate-50 py-24 relative overflow-hidden">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter">Penjelasan Singkat Fitur</h2>
                <div class="w-24 h-2 bg-blue-600 mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                    <div class="text-4xl mb-6 group-hover:scale-110 transition-transform inline-block">📖</div>
                    <h3 class="text-xl font-black uppercase italic tracking-tighter mb-4">Sistem Peminjaman</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Pinjam buku favorit Anda hanya dengan beberapa klik. Sistem otomatis akan menghitung tenggat waktu secara akurat.</p>
                </div>

                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                    <div class="text-4xl mb-6 group-hover:scale-110 transition-transform inline-block">⭐</div>
                    <h3 class="text-xl font-black uppercase italic tracking-tighter mb-4">Ulasan Pengguna</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Berikan rating dan komentar pada buku yang telah Anda baca melalui fitur "Suara Peminjam" yang interaktif.</p>
                </div>

                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all group">
                    <div class="text-4xl mb-6 group-hover:scale-110 transition-transform inline-block">🛡️</div>
                    <h3 class="text-xl font-black uppercase italic tracking-tighter mb-4">Panel Kontrol</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Kelola inventori buku dan pantau seluruh aktivitas peminjaman melalui dashboard Admin yang komprehensif.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 text-center border-t border-slate-50">
        <div class="container mx-auto px-6">
            <div class="flex justify-center gap-8 mb-8">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Katalog</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tentang Kami</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kontak</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.5em]">© 2026 E-LIB Perpustakaan Digital</p>
        </div>
    </footer>

</body>
</html>