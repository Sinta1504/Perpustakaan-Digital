@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12" x-data="{ showEditPassword: false }">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-extrabold text-slate-900 mb-8">Profil Pengguna</h2>

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-12 text-white flex items-center gap-6">
                <div class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-4xl border-2 border-white/30">
                    👤
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ Auth::user()->name }}</h3>
                    <p class="text-blue-100">Administrator Perpustakaan</p>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8" x-show="!showEditPassword">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <p class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">{{ Auth::user()->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                        <p class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Status Akun</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-black uppercase tracking-tighter">Aktif</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Bergabung Sejak</label>
                        <p class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">
                            {{ Auth::user()->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>

                <div class="mt-12 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span>🛡️</span> Keamanan Akun
                    </h4>

                    <div x-show="!showEditPassword">
                        <p class="text-sm text-slate-500 mb-4">Anda dapat memperbarui kata sandi secara berkala untuk menjaga keamanan akun administrator.</p>
                        <button @click="showEditPassword = true" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition shadow-sm">
                            Ubah Password
                        </button>
                    </div>

                    <div x-show="showEditPassword" x-cloak>
                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Password Saat Ini</label>
                                <input type="password" name="current_password" class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-blue-500 shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Password Baru</label>
                                <input type="password" name="password" class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-blue-500 shadow-sm" required>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-2 rounded-xl border-slate-200 focus:ring-blue-500 shadow-sm" required>
                            </div>

                            <div class="flex items-center gap-3 mt-6">
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition shadow-sm">
                                    Simpan Password
                                </button>
                                <button type="button" @click="showEditPassword = false" class="bg-white border border-slate-200 text-slate-700 px-6 py-2 rounded-xl font-bold hover:bg-slate-100 transition shadow-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection