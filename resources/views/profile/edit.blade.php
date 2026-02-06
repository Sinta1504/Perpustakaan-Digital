@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto space-y-8">
        
        <div>
            <h2 class="text-3xl font-black text-slate-900 leading-tight">
                {{ __('Pengaturan Profil') }}
            </h2>
            <p class="text-slate-500">Kelola informasi akun dan keamanan Sinta, Zara, Andhika, maupun Admin di sini.</p>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 mb-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-blue-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg shadow-blue-200 uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-900">{{ Auth::user()->name }}</h3>
                    <p class="text-slate-500">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-2 px-4 py-1 rounded-full bg-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-600 border border-slate-200">
                        Status: {{ Auth::user()->role ?? 'User' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="p-8 bg-white border border-slate-100 shadow-sm rounded-[2.5rem]">
                <div class="max-w-xl">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Profil</h3>
                    @include('profile.partials.update-profile-information-form')
                    <p class="mt-4 text-[10px] text-red-500 italic font-medium">
                        *Pastikan email menggunakan huruf kecil (lowercase) saat melakukan update.
                    </p>
                </div>
            </div>

            <div class="p-8 bg-white border border-slate-100 shadow-sm rounded-[2.5rem]">
                <div class="max-w-xl">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Ubah Kata Sandi</h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-8 bg-white border border-red-50 shadow-sm rounded-[2.5rem]">
                <div class="max-w-xl">
                    <h3 class="text-lg font-bold text-red-600 mb-4">Zona Berbahaya</h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection