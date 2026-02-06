<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Tampilan Dashboard Utama
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $overdueBooks = Loan::with('book')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<=', now())
            ->get();

        $topBorrowers = User::withCount('loans')->orderBy('loans_count', 'desc')->take(5)->get();
        $totalBuku = Book::count();
        $totalPinjaman = Loan::count();

        return view('dashboard', compact('topBorrowers', 'totalBuku', 'totalPinjaman', 'overdueBooks'));
    }

    /**
     * Daftar Pinjaman: User melihat MILIKNYA sendiri
     */
    public function index()
    {
        $loans = Loan::with(['user', 'book'])
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('loans.index', compact('loans'));
    }

    /**
     * FUNGSI ADMIN: Admin melihat SEMUA pinjaman
     */
    public function allLoans()
    {
        $loans = Loan::with(['user', 'book'])->latest()->get();
        return view('loans.index', compact('loans'));
    }

    /**
     * Menampilkan Form Konfirmasi Peminjaman
     */
    public function create(Book $book)
    {
        $tanggalPinjam = now()->format('d F Y');
        $tanggalKembali = now()->addDays(7)->format('d F Y');

        return view('loans.create', compact('book', 'tanggalPinjam', 'tanggalKembali'));
    }

    /**
     * Memproses Penyimpanan Data Pinjam
     */
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'identitas' => 'required|string|max:50',
        ]);

        if ($book->stok <= 0) {
            return redirect()->route('katalog')->with('error', 'Maaf, stok buku ini sudah habis.');
        }

        Loan::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'nama_peminjam' => Auth::user()->name,
            'nomor_identitas' => $request->identitas,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali' => Carbon::now()->addDays(7),
            'status' => 'dipinjam',
        ]);

        // Kurangi stok menggunakan kolom 'stok' sesuai database Anda
        $book->decrement('stok');

        return redirect()->route('pinjaman')->with('success', 'Buku berhasil dipinjam!');
    }

    /**
     * FUNGSI PENGEMBALIAN BUKU (DIPERBAIKI)
     * Digunakan oleh Admin (Selesaikan) dan User (Kembalikan)
     */
        public function returnBook($id)
    {
        $loan = Loan::findOrFail($id);
        
        if (auth()->user()->id !== $loan->user_id && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Akses ditolak.');
        }

        // PERBAIKAN: Gunakan 'dikembalikan' jika 'kembali' menyebabkan truncation error
        $loan->update([
            'status' => 'dikembalikan', 
            'tanggal_kembali' => now()
        ]);

        if ($loan->book) {
            $loan->book->increment('stok');
        }

        return back()->with('success', 'Buku berhasil dikembalikan!');
    }
    /*
    |--------------------------------------------------------------------------
    | FUNGSI KHUSUS ADMIN LAINNYA
    |--------------------------------------------------------------------------
    */

    public function inventory()
    {
        $frequentBooks = Book::withCount('loans')->orderBy('loans_count', 'desc')->take(5)->get();
        $damagedBooks = Book::where('kondisi', 'rusak')->get();
        $unreturnedBooks = Loan::with(['book', 'user'])->where('status', 'dipinjam')->whereDate('tanggal_kembali', '<', now())->get();

        $inactiveUsers = User::whereDoesntHave('loans', function($query) {
            $query->where('tanggal_pinjam', '>=', now()->subMonths(5));
        })->where('role', '!=', 'admin')->get();

        return view('admin.inventory', compact('frequentBooks', 'damagedBooks', 'unreturnedBooks', 'inactiveUsers'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Status akun berhasil diperbarui!');
    }
}