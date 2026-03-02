@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic">Layanan Pengguna</h2>
            <p class="text-slate-500 font-medium">Pusat bantuan dan penanganan kendala teknis peminjam.</p>
        </div>
        <div class="flex gap-3">
            <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase italic">Tiket Aktif</p>
                <p class="text-xl font-black text-rose-600">12</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 border-bottom border-slate-100">
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase italic tracking-widest">Peminjam</th>
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase italic tracking-widest">Kategori Kendala</th>
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase italic tracking-widest">Status</th>
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase italic tracking-widest text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <tr class="hover:bg-slate-50/50 transition-all">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-xs">A</div>
                            <div>
                                <h5 class="font-black text-slate-900 uppercase text-xs italic">Andi User</h5>
                                <p class="text-[9px] text-slate-400 font-bold uppercase">andi@test.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <h5 class="font-black text-slate-900 uppercase text-xs italic">Gagal Akses E-Book</h5>
                        <p class="text-xs text-slate-500 italic">"Buku tidak muncul setelah klik pinjam"</p>
                    </td>
                    <td class="px-8 py-6">
                        <span class="bg-rose-50 text-rose-500 px-4 py-1 rounded-full border border-rose-100 font-black text-[10px] uppercase italic">Urgent</span>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <button class="text-blue-600 font-black text-[10px] uppercase hover:underline">Respon Bantuan</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection