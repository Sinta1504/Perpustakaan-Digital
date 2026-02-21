<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📋 Monitoring Pinjaman Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="font-bold text-lg uppercase tracking-wider text-slate-700">Daftar Sirkulasi Global</h3>
                    
                    <form action="{{ route('admin.loans') }}" method="GET" class="flex gap-2">
                        <select name="status" class="rounded-lg border-gray-300 text-sm">
                            <option value="">Semua Status</option>
                            <option value="aktif">Sedang Dipinjam</option>
                            <option value="kembali">Sudah Kembali</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700">
                            Filter
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-black uppercase tracking-widest text-slate-500 border-b">
                                <th class="p-4">Nama Peminjam</th>
                                <th class="p-4">No. Buku</th>
                                <th class="p-4">Judul Buku</th>
                                <th class="p-4">Tenggat Waktu</th>
                                <th class="p-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($allLoans as $loan)
                                <tr class="border-b hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-semibold text-slate-800">{{ $loan->user->name }}</td>
                                    <td class="p-4 font-mono text-blue-600 font-bold">#BK-{{ $loan->book->id }}</td>
                                    <td class="p-4 text-slate-600">{{ $loan->book->judul }}</td>
                                    <td class="p-4">
                                        <span class="{{ $loan->tenggat < now() && !$loan->is_returned ? 'text-red-500 font-bold' : 'text-slate-500' }}">
                                            {{ \Carbon\Carbon::parse($loan->tenggat)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($loan->is_returned)
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">Dikembalikan</span>
                                        @else
                                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">Dipinjam</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 italic">Belum ada data peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>