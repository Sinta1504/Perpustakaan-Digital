@extends('layouts.app_custom')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="mb-10 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-900 uppercase italic">Suara Peminjam</h2>
            <p class="text-slate-500 font-medium">Kelola ulasan dan berikan balasan hangat kepada pembaca.</p>
        </div>
        <div class="bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
            <span class="text-slate-600 font-bold text-sm">Total: {{ $reviews->count() }} Ulasan</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        @forelse($reviews as $review)
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-xl transition-all relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-green-500 text-white text-[8px] font-black px-4 py-1 uppercase tracking-widest rounded-bl-2xl">
                Selesai
            </div>

            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-xs">
                        {{ substr($review->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <h5 class="font-black text-slate-900 uppercase text-xs italic">{{ $review->user->name ?? 'User' }}</h5>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Pembaca</p>
                    </div>
                </div>
                <div class="bg-amber-50 px-3 py-1 rounded-full border border-amber-100">
                    <span class="text-amber-500 font-black text-[10px]">⭐ {{ $review->rating }}/5</span>
                </div>
            </div>

            <div class="bg-slate-50 p-5 rounded-[1.5rem] mb-4 italic text-slate-600 text-sm leading-relaxed min-h-[80px]">
                "{{ $review->ulasan ?? '-' }}"
            </div>

            @if($review->admin_reply)
            <div class="bg-blue-50 p-4 rounded-2xl mb-4 border-l-4 border-blue-500">
                <p class="text-[10px] font-black text-blue-600 uppercase mb-1 flex items-center gap-1">
                    <span class="text-xs">💬</span> Balasan Admin:
                </p>
                <p class="text-xs text-slate-600 italic leading-relaxed">"{{ $review->admin_reply }}"</p>
            </div>
            @endif

            <div class="flex items-center justify-between border-t border-slate-50 pt-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-8 bg-slate-100 rounded shadow-sm overflow-hidden">
                        <img src="{{ $review->book->cover_url ?? asset('storage/'.$review->book->cover) }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/150'">
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter truncate w-32">
                        {{ $review->book->judul }}
                    </span>
                </div>
                <span class="text-[9px] text-slate-300 font-bold uppercase">{{ $review->updated_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-dashed border-slate-200">
            <div class="text-5xl mb-4">💬</div>
            <h3 class="font-black text-slate-900 uppercase italic">Belum Ada Ulasan</h3>
            <p class="text-slate-400 text-sm">Review akan muncul di sini otomatis setelah pembaca mengembalikan buku.</p>
        </div>
        @endforelse
    </div>

    @if($reviews->isNotEmpty())
    <div class="bg-slate-800 rounded-[2.5rem] p-8 shadow-xl overflow-hidden border border-slate-700">
        <h3 class="text-xl font-black text-white uppercase italic mb-6">Manajemen Ulasan & Balasan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-white border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-slate-400 text-[10px] uppercase tracking-widest font-black">
                        <th class="px-4 py-3 text-left">Peminjam</th>
                        <th class="px-4 py-3 text-left">Buku</th>
                        <th class="px-4 py-3 text-left">Rating</th>
                        <th class="px-4 py-3 text-left">Ulasan</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $item)
                    <tr class="bg-slate-700/30 hover:bg-slate-700/50 transition-all group">
                        <td class="p-4 text-sm font-bold rounded-l-2xl">{{ $item->user->name }}</td>
                        <td class="p-4 text-sm text-slate-400">{{ $item->book->judul }}</td>
                        <td class="p-4 text-yellow-400 font-bold">
                            @for($i = 0; $i < $item->rating; $i++) ⭐ @endfor
                        </td>
                        <td class="p-4 text-sm text-slate-300 italic">
                            {{ Str::limit($item->ulasan, 40) }}
                            @if($item->admin_reply) 
                                <span class="ml-2 bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-tighter">Terbalas</span> 
                            @endif
                        </td>
                        <td class="p-4 text-right rounded-r-2xl">
                            <div class="flex justify-end gap-3">
                                <button onclick="openReplyModal({{ $item->id }}, '{{ addslashes($item->ulasan) }}')" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded-xl font-black text-[10px] uppercase tracking-tighter transition-all">
                                    Balas
                                </button>
                                
                                <form action="{{ route('admin.feedback.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-3 py-1.5 rounded-xl font-black text-[10px] uppercase tracking-tighter transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<div id="replyModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] max-w-lg w-full p-8 shadow-2xl scale-95 transition-transform duration-300">
        <h3 class="text-xl font-black text-slate-900 uppercase italic mb-2">Balas Ulasan</h3>
        <p id="userReviewText" class="text-sm text-slate-500 italic mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-100"></p>
        
        <form id="replyForm" method="POST">
            @csrf
            <textarea name="reply" rows="4" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 focus:border-blue-500 mb-4 text-sm p-4 text-slate-700" placeholder="Tulis balasan hangat Anda untuk pembaca..." required></textarea>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeReplyModal()" class="flex-1 py-3 text-sm font-bold text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl text-sm font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    Kirim Balasan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Membuka modal balasan dan mengisi konten ulasan asli secara dinamis
     */
    function openReplyModal(id, text) {
        document.getElementById('userReviewText').innerText = '"' + text + '"';
        // Mengarahkan form action ke route reply di FeedbackController
        document.getElementById('replyForm').action = "/admin/feedback/" + id + "/reply";
        
        const modal = document.getElementById('replyModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('div').classList.add('scale-100');
        }, 10);
    }
    
    /**
     * Menutup modal balasan
     */
    function closeReplyModal() {
        const modal = document.getElementById('replyModal');
        modal.querySelector('div').classList.remove('scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    /**
     * Menutup modal jika user mengklik area di luar kotak modal
     */
    window.onclick = function(event) {
        const modal = document.getElementById('replyModal');
        if (event.target == modal) {
            closeReplyModal();
        }
    }
</script>
@endsection