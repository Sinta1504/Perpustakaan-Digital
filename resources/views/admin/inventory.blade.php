@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h2 class="text-3xl font-black text-slate-900 mb-10">Panel Inventori & Kendali Admin</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">🔥 Paling Sering Dipinjam</h3>
            <div class="space-y-4">
                @foreach($frequentBooks as $book)
                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl">
                    <span class="font-bold text-slate-700">{{ $book->judul }}</span>
                    <span class="bg-blue-100 text-blue-600 px-4 py-1 rounded-full text-xs font-black">{{ $book->loans_count }} Kali</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">👤 User Inaktif (>5 Bulan)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-xs uppercase">
                            <th class="pb-4">Nama</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inactiveUsers as $user)
                        <tr class="border-t border-slate-50">
                            <td class="py-4 font-bold text-slate-700">{{ $user->name }}</td>
                            <td class="py-4">
                                <span class="{{ $user->is_active ? 'text-green-500' : 'text-red-500' }} text-xs font-bold">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-4">
                                <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="text-[10px] font-black uppercase tracking-tighter {{ $user->is_active ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} px-3 py-1 rounded-lg">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 lg:col-span-2">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">⚠️ Peminjaman Melewati Tenggat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($unreturnedBooks as $loan)
                <div class="p-6 border-2 border-red-50 rounded-3xl bg-red-50/30">
                    <p class="font-black text-slate-900">{{ $loan->book->judul }}</p>
                    <p class="text-sm text-red-600 font-bold">Peminjam: {{ $loan->user->name }}</p>
                    <p class="text-xs text-slate-400 mt-2">Harusnya kembali: {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y') }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection