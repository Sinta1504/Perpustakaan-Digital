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
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="kembali" {{ request('status') == 'kembali' ? 'selected' : '' }}>Sudah Kembali</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">
                            Filter
                        </button>
                        @if(request('status'))
                            <a href="{{ route('admin.loans') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-300 transition">Reset</a>
                        @endif
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
                                    <td class="p-4 font-semibold text-slate-800">
                                        {{ $loan->user->name ?? 'User Terhapus' }}
                                    </td>
                                    <td class="p-4 font-mono text-blue-600 font-bold">#BK-{{ $loan->book->id }}</td>
                                    <td class="p-4 text-slate-600">{{ $loan->book->judul }}</td>
                                    <td class="p-4">
                                        @php
                                            $tenggat = \Carbon\Carbon::parse($loan->tanggal_kembali);
                                            $isOverdue = $tenggat->isPast() && $loan->status == 'dipinjam';
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-red-500 font-extrabold' : 'text-slate-500 font-medium' }}">
                                            {{ $tenggat->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($loan->status == 'kembali')
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-green-200">Dikembalikan</span>
                                        @else
                                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-orange-200">Dipinjam</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 italic font-medium">Belum ada data peminjaman yang sesuai.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>