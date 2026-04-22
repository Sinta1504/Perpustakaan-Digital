<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * DASHBOARD: Menampilkan Statistik untuk Admin & Rekomendasi untuk User
     */
    public function dashboard()
    {
        // 1. Persiapkan data dasar untuk User (Rekomendasi & Feedback)
        $recommendedBooks = Book::latest()->take(4)->get();
        $feedbacks = Feedback::with(['book', 'user'])->latest()->take(3)->get();

        // 2. Logika Data Grafik (Peminjaman 5 Bulan Terakhir)
        $labels = [];
        $dataPinjaman = [];
        for ($i = 4; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('F'); // Nama Bulan
            $dataPinjaman[] = Loan::whereMonth('created_at', $month->month)
                                  ->whereYear('created_at', $month->year)
                                  ->count();
        }

        // 3. Data untuk Buku Terpopuler (Top 3 berdasarkan jumlah peminjaman terbanyak)
        $topBooks = Book::withCount('loans')
                        ->orderBy('loans_count', 'desc')
                        ->take(3)
                        ->get();

        // 4. Data untuk Buku Kurang Diminati (Bottom 3 berdasarkan jumlah peminjaman terkecil)
        $leastBooks = Book::withCount('loans')
                        ->orderBy('loans_count', 'asc')
                        ->take(3)
                        ->get();

        // Kirim semua data ke view dashboard.blade.php
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
        $query = Loan::with(['user', 'book']);
        
        if ($request->has('status') && $request->status != '' && $request->status != 'Semua Status') {
            $query->where('status', strtolower($request->status));
        }
        
        $loans = $query->latest()->get();
        return view('admin.loans', compact('loans'));
    }

    /**
     * FORM PEMINJAMAN
     */
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

        $book = Book::findOrFail($request->book_id);

        if ($book->stok <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok buku ini sudah habis!');
        }

        Loan::create([
            'user_id'         => Auth::id(),
            'book_id'         => $request->book_id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status'          => 'dipinjam',
            'identitas'       => $request->identitas,
        ]);

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
            'ulasan' => 'required|string|min:2',
        ]);

        $loan = Loan::findOrFail($id);

        if ($loan->status === 'kembali') {
            return redirect()->back()->with('info', 'Buku ini sudah dikembalikan.');
        }

        $loan->update([
            'status' => 'kembali',
            'tanggal_nyata_kembali' => now(),
            'ulasan' => $request->ulasan,
            'rating' => $request->rating,
            'denda'  => 0,
        ]);

        $loan->book->increment('stok');

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