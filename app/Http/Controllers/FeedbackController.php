<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Menampilkan daftar semua masukan untuk Admin.
     */
    public function index()
    {
        // Mengambil semua feedback beserta data user dan buku terkait secara efisien
        $feedbacks = Feedback::with(['user', 'book'])->latest()->get();
        
        return view('admin.feedbacks', compact('feedbacks'));
    }

    /**
     * Menampilkan halaman form Hubungi Kami (User).
     */
    public function create()
    {
        return view('contact.index');
    }

    /**
     * Menyimpan masukan (rating buku atau saran sistem) dari user ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'pesan' => 'required|string|min:5',
            'kategori' => 'required|in:saran,kritik,lainnya',
            'rating' => 'nullable|integer|min:1|max:5', // Rating bersifat opsional (untuk buku)
            'book_id' => 'nullable|exists:books,id'     // ID buku bersifat opsional (jika saran sistem)
        ]);

        // 2. Simpan ke database
        Feedback::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'pesan' => $request->pesan,
            'kategori' => $request->kategori,
            'rating' => $request->rating ?? 5 // Default rating 5 jika tidak diisi
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