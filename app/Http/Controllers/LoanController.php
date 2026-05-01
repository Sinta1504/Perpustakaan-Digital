<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LoanController extends Controller
{
    /**
     * DASHBOARD: Statistik Admin & Rekomendasi User
     */
    public function dashboard()
    {
        $recommendedBooks = Book::latest()->take(4)->get();
        $feedbacks = Feedback::with(['book', 'user'])->latest()->take(3)->get();

        $labels = [];
        $dataPinjaman = [];
        for ($i = 4; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('F');
            $dataPinjaman[] = Loan::whereMonth('created_at', $month->month)
                                  ->whereYear('created_at', $month->year)
                                  ->count();
        }

        $topBooks = Book::withCount('loans')->orderBy('loans_count', 'desc')->take(3)->get();
        $leastBooks = Book::withCount('loans')->orderBy('loans_count', 'asc')->take(3)->get();

        return view('dashboard', compact(
            'recommendedBooks', 
            'feedbacks', 
            'labels', 
            'dataPinjaman', 
            'topBooks',
            'leastBooks'
        ));
    }

    /**
     * Tampilan Pinjaman Saya (User)
     */
    public function index()
    {
        // PERBAIKAN: Menghapus 'feedback' dari eager loading karena kolom loan_id belum ada di database
        $loans = Loan::with(['book']) 
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        $rekomendasi_buku = Book::latest()->take(4)->get();
                    
        return view('loans.index', compact('loans', 'rekomendasi_buku'));
    }

    /**
     * Tampilan Monitoring (Admin)
     */
    public function allLoans(Request $request)
    {
        // PERBAIKAN: Menghapus 'feedback' agar tidak error saat query
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
        return view('loans.create', compact('book'));
    }

    /**
     * PROSES SIMPAN PINJAMAN
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'identitas' => 'required|string|max:50',
        ]);

        $userId = Auth::id();
        $bookId = $request->book_id;

        $hasEverBorrowed = Loan::where('user_id', $userId)
                                ->where('book_id', $bookId)
                                ->exists();

        if ($hasEverBorrowed) {
            return redirect()->back()->with('error', 'Anda sudah pernah meminjam buku ini sebelumnya.');
        }

        $book = Book::findOrFail($bookId);
        if ($book->stok <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok buku ini sedang habis!');
        }

        Loan::create([
            'user_id'         => $userId,
            'book_id'         => $bookId,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status'          => 'dipinjam',
            'identitas'       => $request->identitas,
        ]);

        $book->decrement('stok');
        return redirect()->route('pinjaman')->with('success', "Buku {$book->judul} berhasil dipinjam!");
    }

    /**
     * PROSES KEMBALIKAN BUKU (DENGAN DENDA & ULASAN)
     */
    public function returnBook(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:2',
        ]);

        $loan = Loan::findOrFail($id);

        if ($loan->status === 'kembali') {
            return redirect()->back()->with('info', 'Buku ini sudah dikembalikan.');
        }

        $denda = 0;
        $tglTenggat = \Carbon\Carbon::parse($loan->tanggal_kembali);
        
        if (now()->greaterThan($tglTenggat)) {
            $hariTerlambat = now()->diffInDays($tglTenggat);
            $denda = $hariTerlambat * 1000;
        }

        $loan->update([
            'status' => 'kembali',
            'tanggal_nyata_kembali' => now(),
            'ulasan' => $request->ulasan,
            'rating' => $request->rating,
            'denda'  => $denda,
        ]);

        $loan->book->increment('stok');

        // Note: Simpan feedback tetap jalan, namun tidak direlasikan via query 'with' di atas
        Feedback::create([
            'user_id'  => Auth::id(),
            'book_id'  => $loan->book_id,
            'loan_id'  => $loan->id,
            'rating'   => $request->rating,
            'pesan'    => $request->ulasan, 
            'kategori' => 'Buku', 
        ]);

        return redirect()->route('pinjaman')->with('success', 'Buku berhasil dikembalikan! ' . ($denda > 0 ? "Anda dikenakan denda Rp " . number_format($denda) : ""));
    }

    /**
     * FUNGSI DOWNLOAD E-BOOK (PDF)
     */
    public function downloadPdf($id)
    {
        $loan = Loan::with('book')->findOrFail($id);

        if ($loan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk dokumen ini.');
        }

        $pdf = Pdf::loadView('pdf.ebook_template', compact('loan'));
        return $pdf->download('E-Book - ' . $loan->book->judul . '.pdf');
    }
}