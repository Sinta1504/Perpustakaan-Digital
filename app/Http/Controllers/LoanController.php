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
     * DASHBOARD: Menampilkan rekomendasi buku
     */
    public function dashboard()
    {
        $recommendedBooks = Book::latest()->take(4)->get();
        return view('dashboard', compact('recommendedBooks'));
    }

    /**
     * PINJAMAN SAYA (USER)
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
     * MONITORING ADMIN (DENGAN FILTER)
     * TARUH KODE PERBAIKAN DI SINI
     */
    public function allLoans(Request $request)
{
    $query = Loan::with(['user', 'book']);

    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    $loans = $query->latest()->get();

    // Pastikan nama variabel 'loans' sesuai dengan yang dipanggil di Blade
    return view('admin.loans', compact('loans'));
}

    /**
     * FORM PINJAM
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

        return redirect()->route('pinjaman')->with('success', "Buku berhasil dipinjam!");
    }

    /**
     * PROSES KEMBALIKAN & OTOMATIS KE FEEDBACK
     */
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

        Feedback::create([
            'user_id'  => Auth::id(),
            'book_id'  => $loan->book_id,
            'rating'   => $request->rating,
            'pesan'    => $request->ulasan,
            'kategori' => 'Peminjaman',
        ]);

        return redirect()->route('pinjaman')->with('success', 'Buku telah dikembalikan!');
    }
}