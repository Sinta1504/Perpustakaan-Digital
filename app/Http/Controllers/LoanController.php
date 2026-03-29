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

    public function create($id)
    {
        $book = Book::findOrFail($id);
        $tanggalPinjam = Carbon::now();
        $tanggalKembali = Carbon::now()->addDays(7); 
        return view('loans.create', compact('book', 'tanggalPinjam', 'tanggalKembali'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'tanggal_kembali' => 'required|date',
        ]);

        $book = Book::findOrFail($request->book_id);

        if ($book->stok <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok buku ini sudah habis!');
        }

        Loan::create([
            'user_id'         => Auth::id(),
            'book_id'         => $request->book_id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => 'dipinjam',
        ]);

        $book->decrement('stok');

        return redirect()->route('pinjaman')->with('success', "Buku berhasil dipinjam! Stok berkurang.");
    }

    /**
     * PROSES KEMBALIKAN BUKU
     * Sudah diperbaiki untuk memastikan ulasan tersimpan di tabel LOANS dan FEEDBACKS
     */
    public function returnBook(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:5',
        ]);

        $loan = Loan::findOrFail($id);

        // 1. Update di tabel LOANS (Agar muncul di halaman Pinjaman Saya)
        // Kita gunakan save() agar lebih aman dan memastikan variabel terisi
        $loan->status = 'kembali';
        $loan->denda = 0;
        $loan->ulasan = $request->ulasan; 
        $loan->rating = $request->rating; 
        $loan->save(); 

        // 2. Kembalikan stok buku
        $loan->book->increment('stok');

        // 3. Simpan ke tabel FEEDBACK (Agar muncul di Dashboard/Suara Peminjam)
        Feedback::create([
            'user_id'  => Auth::id(),
            'book_id'  => $loan->book_id,
            'rating'   => $request->rating,
            'pesan'    => $request->ulasan, // Kolom di tabel Feedback biasanya bernama 'pesan'
            'kategori' => 'Buku', 
        ]);

        return redirect()->route('pinjaman')->with('success', 'Buku dikembalikan dan ulasan disimpan!');
    }
}