<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Menampilkan daftar semua masukan untuk Admin (Suara Peminjam).
     */
    public function index()
    {
        // Mengambil semua feedback dari database, diurutkan dari yang terbaru
        // Memuat relasi 'user' untuk nama peminjam dan 'book' untuk judul buku
        $feedbacks = Feedback::with(['user', 'book'])->latest()->get();
        
        return view('admin.feedbacks', compact('feedbacks'));
    }

    /**
     * Menyimpan balasan Admin terhadap ulasan User.
     */
    public function reply(Request $request, $id)
    {
        // 1. Validasi input balasan
        $request->validate([
            'reply' => 'required|string|min:2',
        ]);

        // 2. Cari data feedback berdasarkan ID dan update kolom admin_reply
        $feedback = Feedback::findOrFail($id);
        $feedback->update([
            'admin_reply' => $request->reply 
        ]);

        // 3. Kembali ke halaman dengan pesan sukses
        return back()->with('success', 'Balasan hangat Anda berhasil dikirim ke peminjam!');
    }

    /**
     * Menampilkan halaman form Hubungi Kami (User).
     */
    public function create()
    {
        return view('contact.index');
    }

    /**
     * Menyimpan ulasan (rating buku atau saran sistem) dari user ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari user
        $request->validate([
            'pesan' => 'required|string|min:5',
            'kategori' => 'required|in:saran,kritik,lainnya',
            'rating' => 'nullable|integer|min:1|max:5', 
            'book_id' => 'nullable|exists:books,id'     
        ]);

        // 2. Simpan ulasan ke database
        Feedback::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'pesan' => $request->pesan,
            'kategori' => $request->kategori,
            'rating' => $request->rating ?? 5 
        ]);

        // 3. Kembali dengan pesan sukses
        $pesanSukses = $request->book_id ? 'Rating buku berhasil terkirim!' : 'Terima kasih atas masukan Anda!';
        return back()->with('success', $pesanSukses);
    }

    /**
     * Menghapus feedback (khusus Admin).
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success', 'Masukan berhasil dihapus.');
    }
}