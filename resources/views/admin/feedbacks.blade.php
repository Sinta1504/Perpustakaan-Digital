@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto">
    <div class="mb-10">
        <h2 class="text-3xl font-black text-slate-900 uppercase italic">Manajemen Suara Peminjam</h2>
        <p class="text-slate-500 font-medium">Lihat rating buku dan masukan sistem dari pengguna.</p>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">User</th>
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">Buku / Kategori</th>
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">Rating & Pesan</th>
                    <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($feedbacks as $fb)
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-5">
                        <span class="font-bold text-slate-900 text-sm block">{{ $fb->user->name }}</span>
                        <span class="text-[10px] text-slate-400 uppercase font-bold">{{ $fb->user->role }}</span>
                    </td>
                    <td class="px-8 py-5">
                        @if($fb->book)
                            <span class="text-blue-600 font-black text-xs uppercase italic">{{ $fb->book->judul }}</span>
                        @else
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[9px] font-black uppercase">{{ $fb->kategori }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-5">
                        <div class="flex flex-col gap-1">
                            <div class="text-amber-400 text-xs">
                                @for($i=1; $i<=5; $i++)
                                    {{ $i <= $fb->rating ? '⭐' : '☆' }}
                                @endfor
                            </div>
                            <p class="text-sm text-slate-600 font-medium italic">"{{ $fb->pesan }}"</p>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <form action="{{ route('admin.feedback.destroy', $fb->id) }}" method="POST" onsubmit="return confirm('Hapus feedback ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 transition-colors text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection