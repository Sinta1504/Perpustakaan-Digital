<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;     // Sesuai dengan model Peminjaman Anda
use App\Models\Feedback; // Sesuai dengan model Suara Peminjam
use Illuminate\Support\Facades\Auth;

class PinjamanController extends Controller
{
    /**
     * Memproses pengembalian buku, update status, dan simpan ulasan.
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

            // 3. Update status peminjaman menjadi dikembalikan
            $loan->update([
                'status' => 'Sudah Dikembalikan',
                'tanggal_kembali' => now(), // Mencatat tanggal pengembalian real-time
            ]);

            // 4. Simpan ulasan ke tabel Feedback (Suara Peminjam)
            // Menggunakan kolom 'pesan' dan 'kategori' sesuai migration Anda
            Feedback::create([
                'user_id'  => Auth::id(),
                'book_id'  => $loan->book_id,
                'pesan'    => $request->ulasan, // Data dari textarea modal
                'rating'   => $request->rating,
                'kategori' => 'lainnya',      // Mengisi enum kategori yang wajib diisi
            ]);

            // 5. Kembali ke halaman pinjaman dengan pesan sukses
            return redirect()->route('pinjaman')->with('success', 'Buku berhasil dikembalikan dan ulasan Anda telah diterima oleh Admin!');
            
        } catch (\Exception $e) {
            // Menangkap error jika ada kegagalan sistem
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}