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
     * DASHBOARD: Menampilkan Rekomendasi Buku & Suara Peminjam
     */
    public function dashboard()
    {
        $recommendedBooks = Book::latest()->take(4)->get();

        $feedbacks = Feedback::with(['book', 'user'])
                            ->latest()
                            ->take(3)
                            ->get();

        return view('dashboard', compact('recommendedBooks', 'feedbacks'));
    }

    /**
     * Tampilan Pinjaman Saya (User)
     */
    public function index()
    {
        $loans = Loan::with(['book'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();
                    
        return view('loans.index', compact('loans'));
    }

    /**
     * Tampilan Monitoring (Admin)
     */
    public function allLoans(Request $request)
    {
        $query = Loan::with(['user', 'book']);
        if ($request->has('status') && $request->status != '' && $request->status != 'Semua Status') {
            $query->where('status', strtolower($request->status));
        }
        $loans = $query->latest()->get();
        return view('admin.loans', compact('loans'));
    }

    /**
     * FORM PEMINJAMAN
     * Menampilkan halaman konfirmasi sebelum meminjam
     */
    public function create($id)
    {
        // Mengambil data buku berdasarkan ID
        $book = Book::findOrFail($id);
        
        // Mengirim data ke view loans.create
        return view('loans.create', compact('book'));
    }

    /**
     * PROSES SIMPAN PINJAMAN
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'identitas' => 'required|string|max:50', // Menyesuaikan input baru di form
        ]);

        $book = Book::findOrFail($request->book_id);

        // Proteksi jika stok habis
        if ($book->stok <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok buku ini sudah habis!');
        }

        Loan::create([
            'user_id'         => Auth::id(),
            'book_id'         => $request->book_id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => now()->addDays(7), // Otomatis set 7 hari dari sekarang
            'status'          => 'dipinjam',
            'identitas'       => $request->identitas, // Simpan NIM/NIK jika kolom tersedia di tabel loans
        ]);

        // Kurangi stok buku
        $book->decrement('stok');

        return redirect()->route('pinjaman')->with('success', "Buku {$book->judul} berhasil dipinjam!");
    }

    /**
     * PROSES KEMBALIKAN BUKU
     */
    public function returnBook(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:5',
        ]);

        $loan = Loan::findOrFail($id);

        if ($loan->status === 'kembali') {
            return redirect()->back()->with('info', 'Buku ini sudah dikembalikan.');
        }

        // Update status pinjaman
        $loan->update([
            'status' => 'kembali',
            'denda'  => 0, // Logika denda bisa ditambahkan di sini jika perlu
            'ulasan' => $request->ulasan,
            'rating' => $request->rating,
        ]);

        // Kembalikan stok buku
        $loan->book->increment('stok');

        // Simpan ulasan ke tabel Feedback untuk ditampilkan di dashboard
        Feedback::create([
            'user_id'  => Auth::id(),
            'book_id'  => $loan->book_id,
            'rating'   => $request->rating,
            'pesan'    => $request->ulasan, 
            'kategori' => 'Buku', 
        ]);

        return redirect()->route('pinjaman')->with('success', 'Terima kasih! Buku telah dikembalikan.');
    }
}