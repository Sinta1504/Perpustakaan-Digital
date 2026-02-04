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
     * Daftar Pinjaman User yang Sedang Login
     */
    public function index()
    {
        $loans = Loan::with('book')->where('user_id', Auth::id())->latest()->get();
        return view('pinjaman', compact('loans'));
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

        // Kurangi stok buku secara otomatis
        $book->decrement('stok');

        return redirect()->route('pinjaman')->with('success', 'Buku berhasil dipinjam!');
    }

    /**
     * Memproses Pengembalian Buku
     */
    public function returnBook(Loan $loan)
    {
        if ($loan->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Tambah kembali stok buku saat dikembalikan
        $loan->book->increment('stok');

        $loan->update(['status' => 'dikembalikan']);
        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNGSI KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */

    /**
     * Melihat Semua Transaksi Peminjaman (Global)
     */
    public function allLoans()
    {
        $loans = Loan::with(['book', 'user'])->latest()->get();
        return view('admin.semua_pinjaman', compact('loans'));
    }

    /**
     * Statistik Inventori & Manajemen User Inaktif
     */
    public function inventory()
    {
        // 1. Buku yang paling sering dipinjam (Top 5)
        $frequentBooks = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)->get();

        // 2. Buku dengan kondisi rusak
        $damagedBooks = Book::where('kondisi', 'rusak')->get();

        // 3. Buku yang belum dikembalikan (melewati tenggat waktu hari ini)
        $unreturnedBooks = Loan::with(['book', 'user'])
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', now())
            ->get();

        // 4. User tidak aktif (tidak meminjam buku dalam 5 bulan terakhir)
        $inactiveUsers = User::whereDoesntHave('loans', function($query) {
            $query->where('tanggal_pinjam', '>=', now()->subMonths(5));
        })->where('role', '!=', 'admin')->get();

        return view('admin.inventory', compact('frequentBooks', 'damagedBooks', 'unreturnedBooks', 'inactiveUsers'));
    }

    /**
     * Mengubah Status Aktif/Nonaktif User
     */
    public function toggleUserStatus(User $user)
    {
        // Pastikan kolom 'is_active' sudah ada di tabel users
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return back()->with('success', 'Status akun ' . $user->name . ' berhasil diperbarui!');
    }
}