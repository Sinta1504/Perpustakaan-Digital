@extends('layouts.app_custom') {{-- Tetap pakai layout asli Anda --}}

@section('content')
<style>
    /* CSS untuk menyesuaikan dengan style Monitoring E-Lib */
    .header-feedback { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
    .header-feedback h2 { font-size: 24px; font-weight: 800; margin: 0; color: #1E293B; text-transform: uppercase; italic; }
    .header-feedback p { color: #64748B; margin: 5px 0 0 0; font-size: 14px; }
    
    .btn-filter-outline { background: white; border: 1px solid #E2E8F0; padding: 8px 16px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px; }
    .btn-filter-primary { background: #3B82F6; color: white; border: none; padding: 8px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; margin-left: 10px; font-size: 12px; }

    .card-list { background: white; border-radius: 12px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .list-th { display: grid; grid-template-columns: 250px 200px 1fr 180px; padding: 15px 20px; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; font-size: 11px; font-weight: 700; color: #64748B; letter-spacing: 0.5px; text-transform: uppercase; }
    
    .list-tr { display: grid; grid-template-columns: 250px 200px 1fr 180px; padding: 20px; border-bottom: 1px solid #F1F5F9; align-items: start; transition: 0.2s; }
    .list-tr:hover { background: #FBFCFE; }

    .book-item { display: flex; gap: 12px; }
    .book-thumb { width: 45px; height: 60px; background: #EDF2F7; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #A0AEC0; font-weight: bold; flex-shrink: 0; }
    .book-info strong { display: block; font-size: 13px; margin-bottom: 4px; color: #1E293B; text-transform: uppercase; }
    .stars { color: #FBBF24; font-size: 11px; }

    .user-item { display: flex; gap: 10px; align-items: center; }
    .avatar-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px; background-color: #3B82F6; }
    
    .review-box { padding-right: 30px; }
    .review-text { font-size: 13px; color: #475569; line-height: 1.5; margin: 0; font-style: italic; }
    
    .admin-reply-box { margin-top: 10px; padding: 10px; background: #F0F9FF; border-left: 3px solid #3B82F6; border-radius: 4px; }
    .admin-reply-box small { display: block; font-weight: 800; color: #3B82F6; font-size: 9px; text-transform: uppercase; margin-bottom: 3px; }
    .admin-reply-box p { font-size: 12px; color: #334155; margin: 0; }

    .date-text { font-size: 12px; font-weight: 700; color: #1E293B; }
    .status-tag { display: inline-block; margin-top: 8px; padding: 2px 10px; border-radius: 12px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
    .status-read { background: #ECFDF5; color: #059669; }
    .status-pending { background: #FFF7ED; color: #C2410C; }

    .btn-action-reply { margin-top: 10px; background: none; border: none; color: #3B82F6; font-weight: 800; font-size: 10px; cursor: pointer; text-transform: uppercase; padding: 0; }
    .btn-action-reply:hover { text-decoration: underline; }
</style>

<div class="container mx-auto px-6 py-8">
    <div class="header-feedback">
        <div>
            <h2 class="italic">Suara Peminjam</h2>
            <p>Pantau seluruh ulasan dan masukan koleksi buku digital secara global.</p>
        </div>
        <div>
            <button class="btn-filter-outline">SEMUA STATUS</button>
            <button class="btn-filter-primary">FILTER</button>
        </div>
    </div>

    <div class="card-list">
        <div class="list-th">
            <span>Buku & Rating</span>
            <span>Peminjam</span>
            <span>Ulasan & Respon</span>
            <span>Tanggal</span>
        </div>

        @forelse($feedbacks as $item)
        <div class="list-tr">
            <div class="book-item">
                <div class="book-thumb">BUKU</div>
                <div class="book-info">
                    <strong>{{ $item->book->judul }}</strong>
                    <div class="stars">
                        @for($i=1; $i<=5; $i++)
                            {{ $i <= $item->rating ? '★' : '☆' }}
                        @endfor
                        <span style="color:#94A3B8; margin-left: 5px;">{{ $item->rating }}.0</span>
                    </div>
                </div>
            </div>

            <div class="user-item">
                <div class="avatar-circle">
                    {{ substr($item->user->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <strong style="font-size: 12px; text-transform: uppercase;">{{ $item->user->name ?? 'User' }}</strong>
                    <div style="font-size: 10px; color: #64748B;">{{ $item->user->email ?? '-' }}</div>
                </div>
            </div>

            <div class="review-box">
                <p class="review-text">"{{ $item->pesan ?? '-' }}"</p>
                
                @if($item->admin_reply)
                <div class="admin-reply-box">
                    <small>Respon Admin:</small>
                    <p>{{ $item->admin_reply }}</p>
                </div>
                @endif

                <button onclick="openReplyModal({{ $item->id }}, '{{ addslashes($item->pesan) }}')" class="btn-action-reply">
                    {{ $item->admin_reply ? 'Ubah Balasan' : 'Balas Ulasan' }}
                </button>
            </div>

            <div class="date-text">
                {{ $item->created_at->format('d M Y') }}
                <br>
                <span class="status-tag {{ $item->admin_reply ? 'status-read' : 'status-pending' }}">
                    {{ $item->admin_reply ? 'Sudah Dibalas' : 'Pending' }}
                </span>
            </div>
        </div>
        @empty
        <div class="p-20 text-center">
            <h3 class="font-black text-slate-400 uppercase italic">Belum Ada Ulasan Masuk</h3>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL BALASAN (Tetap menggunakan sistem Anda) --}}
<div id="replyModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] max-w-lg w-full p-8 shadow-2xl">
        <h3 class="text-xl font-black text-slate-900 uppercase italic mb-6">Balas Ulasan</h3>
        <form id="replyForm" method="POST">
            @csrf
            <textarea name="reply" rows="4" class="w-full rounded-2xl border-slate-200 p-4 mb-4 text-sm" placeholder="Tulis balasan..." required></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="closeReplyModal()" class="flex-1 py-3 text-sm font-bold text-slate-400 uppercase">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white rounded-xl text-sm font-black uppercase">Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReplyModal(id, text) {
        document.getElementById('replyForm').action = "/admin/feedback/" + id + "/reply";
        document.getElementById('replyModal').classList.remove('hidden');
    }
    function closeReplyModal() {
        document.getElementById('replyModal').classList.add('hidden');
    }
</script>
@endsection