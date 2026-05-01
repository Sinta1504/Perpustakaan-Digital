<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;     // Model Peminjaman
use App\Models\Feedback; // Model Suara Peminjam
use App\Models\Book;     // Model Buku
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;       // Library untuk mengolah tanggal

class PinjamanController extends Controller
{
    /**
     * Menampilkan Daftar Pinjaman User (Halaman Index)
     */
    public function pinjaman()
    {
        // Mengambil data pinjaman milik user yang sedang login
        $loans = Loan::with('book')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('loans.index', compact('loans'));
    }

    /**
     * Menampilkan Dashboard dengan Rekomendasi Buku dan Suara Peminjam
     */
    public function index()
    {
        $recommendedBooks = Book::take(4)->get(); 
        $feedbacks = Feedback::with(['book', 'user'])->latest()->take(3)->get();

        return view('dashboard', compact('recommendedBooks', 'feedbacks'));
    }

    /**
     * Memproses pengembalian buku, update status, denda, dan ulasan.
     */
    public function kembalikan(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'ulasan' => 'required|min:5',
        ]);

        try {
            // 2. Cari data pinjaman
            $loan = Loan::findOrFail($id);

            // Keamanan: Pastikan milik user yang login
            if ($loan->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
            }

            // --- LOGIKA DENDA OTOMATIS ---
            $tgl_tenggat = Carbon::parse($loan->tanggal_tenggat)->startOfDay();
            $hari_ini = Carbon::now()->startOfDay(); 
            $denda = 0;

            if ($hari_ini->gt($tgl_tenggat)) {
                $selisih_hari = $hari_ini->diffInDays($tgl_tenggat);
                $denda = $selisih_hari * 2000; 
            }

            // 3. UPDATE TABEL LOANS (Agar muncul di index.blade.php)
            // Pastikan kolom 'ulasan' dan 'rating' ada di migrasi tabel loans Anda
            $loan->update([
                'status' => 'Sudah Dikembalikan',
                'tanggal_kembali' => Carbon::now(),
                'denda' => $denda,
                'ulasan' => $request->ulasan, // SIMPAN KE SINI JUGA
                'rating' => $request->rating, // SIMPAN KE SINI JUGA
            ]);

            // 4. SIMPAN KE TABEL FEEDBACK (Untuk Suara Peminjam di Dashboard)
            Feedback::create([
                'user_id'  => Auth::id(),
                'book_id'  => $loan->book_id,
                'pesan'    => $request->ulasan, 
                'rating'   => $request->rating,
                'kategori' => 'lainnya',        
            ]);

            // 5. Response sukses
            $pesan_sukses = 'Buku berhasil dikembalikan!';
            if ($denda > 0) {
                $pesan_sukses .= ' Denda keterlambatan: Rp ' . number_format($denda, 0, ',', '.');
            }

            return redirect()->back()->with('success', $pesan_sukses);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}