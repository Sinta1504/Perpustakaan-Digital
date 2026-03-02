@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">Pelayanan Pengguna</h2>
        <p class="text-slate-500 text-sm font-medium">Kelola status keanggotaan dan verifikasi akun pembaca.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Pengguna</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/50 transition-all">
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-[10px] font-black uppercase">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="font-bold text-slate-800 uppercase text-xs italic">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-xs text-slate-500 font-medium">{{ $user->email }}</td>
                    <td class="px-8 py-5">
                        @if($user->is_active)
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[9px] font-black uppercase">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-[9px] font-black uppercase">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-8 py-5 text-center">
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                            @csrf
                            @if($user->is_active)
                                <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-red-600 transition-all">
                                    Nonaktifkan Akun
                                </button>
                            @else
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all">
                                    Aktifkan & Verifikasi
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection