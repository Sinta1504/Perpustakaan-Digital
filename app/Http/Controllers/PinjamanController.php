<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;     // Model Peminjaman Anda
use App\Models\Feedback; // Model Suara Peminjam
use App\Models\Book;     // TAMBAHKAN INI agar bisa memanggil data buku
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;       // Library untuk mengolah tanggal

class PinjamanController extends Controller
{
    /**
     * Menampilkan Dashboard dengan Rekomendasi Buku dan Suara Peminjam
     */
    public function index()
    {
        // 1. Mengambil data buku untuk bagian rekomendasi
        $recommendedBooks = Book::take(4)->get(); 

        // 2. Mengambil ulasan terbaru lengkap dengan relasi buku (untuk gambar) dan user
        $feedbacks = Feedback::with(['book', 'user'])->latest()->take(3)->get();

        // 3. Mengirim data ke view dashboard
        return view('dashboard', compact('recommendedBooks', 'feedbacks'));
    }

    /**
     * Memproses pengembalian buku, update status, hitung denda otomatis, dan simpan ulasan.
     */
    public function kembalikan(Request $request, $id)
    {
        // 1. Validasi input dari form
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'ulasan' => 'required|min:5',
        ]);

        try {
            // 2. Cari data pinjaman
            $loan = Loan::findOrFail($id);

            // Keamanan tambahan: Pastikan yang mengembalikan adalah yang meminjam
            if ($loan->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk tindakan ini.');
            }

            // --- LOGIKA DENDA OTOMATIS (Update 25 Maret 2026) ---
            $tgl_tenggat = Carbon::parse($loan->tanggal_tenggat)->startOfDay();
            $hari_ini = Carbon::now()->startOfDay(); 
            
            $denda = 0;

            if ($hari_ini->gt($tgl_tenggat)) {
                $selisih_hari = $hari_ini->diffInDays($tgl_tenggat);
                $denda = $selisih_hari * 2000; // Denda Rp 2.000 per hari
            }
            // ----------------------------------------------------

            // 3. Update status peminjaman
            $loan->update([
                'status' => 'Sudah Dikembalikan',
                'tanggal_kembali' => Carbon::now(),
                'denda' => $denda,
            ]);

            // 4. Simpan ulasan ke tabel Feedback
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
                $pesan_sukses .= ' Anda dikenakan denda keterlambatan sebesar Rp ' . number_format($denda, 0, ',', '.');
            }

            return redirect()->route('pinjaman')->with('success', $pesan_sukses);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}