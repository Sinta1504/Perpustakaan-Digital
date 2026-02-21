<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Menampilkan semua ulasan di halaman Admin
     */
    public function index()
    {
        // Mengambil semua feedback beserta data user dan buku (Eager Loading)
        $reviews = Feedback::with(['user', 'book'])->latest()->get();
        
        return view('admin.feedback.index', compact('reviews'));
    }

    /**
     * Admin membalas ulasan peminjam
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|min:2',
        ]);

        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->update([
                'admin_reply' => $request->reply
            ]);

            return redirect()->back()->with('success', 'Balasan berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim balasan.');
        }
    }

    /**
     * Admin menghapus ulasan jika mengandung kata kasar/tidak pantas
     */
    public function destroy($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();

            return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus ulasan.');
        }
    }
}