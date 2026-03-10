<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index()
    {
        // Mengambil ulasan terbaru dengan data user dan buku
        // Pastikan relasi 'user' dan 'book' sudah ada di Model Feedback
        $feedbacks = Feedback::with(['user', 'book'])->latest()->get();
        
        // PERBAIKAN: Mengarahkan ke admin.feedbacks (resources/views/admin/feedbacks.blade.php)
        return view('admin.feedbacks', compact('feedbacks'));
    }

    public function reply(Request $request, $id)
    {
        // Validasi input balasan
        $request->validate([
            'reply' => 'required|min:2'
        ]);

        try {
            $feedback = Feedback::findOrFail($id);
            
            // Mengupdate kolom admin_reply di database
            $feedback->update([
                'admin_reply' => $request->reply
            ]);

            return redirect()->back()->with('success', 'Balasan berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim balasan: ' . $e->getMessage());
        }
    }

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