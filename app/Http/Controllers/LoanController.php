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
    public function dashboard()
    {
        $recommendedBooks = Book::latest()->take(4)->get();
        return view('dashboard', compact('recommendedBooks'));
    }

    public function index()
    {
        $loans = Loan::with(['book'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();
        return view('loans.index', compact('loans'));
    }

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

    /**
     * PERBAIKAN: Fitur Stok Berkurang Otomatis
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'tanggal_kembali' => 'required|date',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Cek stok sebelum memproses
        if ($book->stok <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok buku ini sudah habis!');
        }

        // 1. Simpan Data Pinjaman
        Loan::create([
            'user_id'         => Auth::id(),
            'book_id'         => $request->book_id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => $request->tanggal_kembali,
            'status'          => 'dipinjam',
        ]);

        // 2. LOGIKA STOK: Kurangi stok buku secara otomatis
        $book->decrement('stok');

        return redirect()->route('pinjaman')->with('success', "Buku berhasil dipinjam! Stok berkurang menjadi $book->stok.");
    }

    public function returnBook(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:5',
        ]);

        $loan = Loan::findOrFail($id);

        $loan->update([
            'status' => 'kembali', 
            'tanggal_kembali' => now(),
            'ulasan' => $request->ulasan, 
            'rating' => $request->rating,
        ]);

        // LOGIKA STOK: Tambah kembali stok saat buku dikembalikan
        $loan->book->increment('stok');

        Feedback::create([
            'user_id'  => Auth::id(),
            'book_id'  => $loan->book_id,
            'rating'   => $request->rating,
            'pesan'    => $request->ulasan,
            'kategori' => 'Buku', 
        ]);

        return redirect()->route('pinjaman')->with('success', 'Buku dikembalikan! Stok bertambah.');
    }
}