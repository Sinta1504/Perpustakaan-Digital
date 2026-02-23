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
     * DASHBOARD: Menampilkan rekomendasi buku di halaman utama setelah login
     */
    public function dashboard()
    {
        $recommendedBooks = Book::latest()->take(4)->get();
        return view('dashboard', compact('recommendedBooks'));
    }

    /**
     * PINJAMAN SAYA: Halaman daftar buku yang sedang/pernah dipinjam oleh user
     */
    public function index()
    {
        $loans = Loan::with(['book'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        // Pastikan folder views/loans/index.blade.php tersedia
        return view('loans.index', compact('loans'));
    }

    /**
     * MONITORING ADMIN: Melihat semua data peminjaman dari semua user
     */
    public function allLoans()
    {
        $loans = Loan::with(['user', 'book'])->latest()->get();
        return view('admin.loans', compact('loans'));
    }

    /**
     * FORM PINJAM: Menampilkan halaman konfirmasi pinjam buku
     */
    public function create($id)
    {
        $book = Book::findOrFail($id);
        $tanggalPinjam = Carbon::now();
        $tanggalKembali = Carbon::now()->addDays(7); 
        return view('loans.create', compact('book', 'tanggalPinjam', 'tanggalKembali'));
    }

    /**
     * PROSES SIMPAN PINJAMAN: Menyimpan data pinjaman baru ke database
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

        return redirect()->route('pinjaman')->with('success', "Buku berhasil dipinjam!");
    }

    /**
     * PROSES KEMBALIKAN & OTOMATIS KE SUARA PEMINJAM (FEEDBACK)
     * Ini akan mengupdate status di tabel Loans DAN membuat data baru di tabel Feedbacks
     */
    public function returnBook(Request $request, $id)
    {
        // Validasi input rating dan ulasan
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:5',
        ]);

        $loan = Loan::findOrFail($id);

        // 1. Update status peminjaman menjadi 'kembali' di tabel Loans
        $loan->update([
            'status' => 'kembali', 
            'tanggal_kembali' => now(),
            'ulasan' => $request->ulasan, 
            'rating' => $request->rating,
        ]);

        // 2. Simpan ulasan ke tabel Feedbacks secara otomatis
        Feedback::create([
            'user_id'  => Auth::id(),
            'book_id'  => $loan->book_id,
            'rating'   => $request->rating,
            'pesan'    => $request->ulasan,
            'kategori' => 'Peminjaman',
        ]);

        return redirect()->route('pinjaman')->with('success', 'Buku telah dikembalikan dan ulasan terkirim ke Suara Peminjam!');
    }
}