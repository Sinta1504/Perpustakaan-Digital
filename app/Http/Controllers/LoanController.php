<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * DASHBOARD: Halaman utama setelah login
     * Menampilkan HANYA rekomendasi buku
     */
    public function dashboard()
    {
        // Mengambil 4 buku terbaru untuk ditampilkan di grid rekomendasi
        $recommendedBooks = Book::latest()->take(4)->get();

        // Mengirim ke view 'dashboard' (Bukan loans.index agar tidak campur)
        return view('dashboard', compact('recommendedBooks'));
    }

    /**
     * DAFTAR PINJAMAN USER: Halaman "Pinjaman Saya"
     */
    public function index()
    {
        // Mengambil daftar pinjaman milik user yang sedang login
        $loans = Loan::with(['book'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('pinjaman', compact('loans'));
    }

    /**
     * PANEL ADMIN: Monitoring Semua Pinjaman (Admin Only)
     */
    public function allLoans()
    {
        $loans = Loan::with(['user', 'book'])->latest()->get();
        
        return view('admin.loans', compact('loans'));
    }

    /**
     * FORM PINJAM: Menampilkan detail sebelum konfirmasi
     */
    public function create($id)
    {
        $book = Book::findOrFail($id);
        $tanggalPinjam = Carbon::now();
        $tanggalKembali = Carbon::now()->addDays(7); 
        
        return view('loans.create', compact('book', 'tanggalPinjam', 'tanggalKembali'));
    }

    /**
     * PROSES SIMPAN PINJAMAN
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'tanggal_kembali' => 'required|date',
        ]);

        Loan::create([
            'user_id'         => Auth::id(),
            'book_id'         => $request->book_id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => 'dipinjam',
        ]);

        return redirect()->route('pinjaman')->with('success', "Buku berhasil dipinjam! Selamat membaca.");
    }

    /**
     * KEMBALIKAN BUKU & SIMPAN REVIEW
     */
    public function returnBook(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:5',
        ]);

        $loan = Loan::findOrFail($id);

        // 1. Update data di tabel Loans
        $loan->update([
            'status' => 'kembali', 
            'tanggal_kembali' => now(),
            'ulasan' => $request->ulasan, 
            'rating' => $request->rating,
        ]);

        // 2. Masukkan ke tabel Feedbacks (untuk fitur Suara Peminjam)
        Feedback::create([
            'user_id'  => Auth::id(),
            'book_id'  => $loan->book_id,
            'rating'   => $request->rating,
            'pesan'    => $request->ulasan,
            'kategori' => 'Peminjaman',
        ]);

        return redirect()->route('pinjaman')->with('success', 'Buku telah dikembalikan. Terima kasih atas ulasannya!');
    }

    /**
     * ADMIN: Suara Peminjam
     */
    public function suaraPeminjam()
    {
        $reviews = Feedback::with(['user', 'book'])->latest()->get();

        return view('admin.feedbacks', compact('reviews'));
    }
}