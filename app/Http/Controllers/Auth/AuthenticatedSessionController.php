<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses masuk (Login).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Membuat ulang sesi untuk mencegah session fixation
        $request->session()->regenerate();

        // Diarahkan ke dashboard (Admin atau Sinta akan ke sini dulu)
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Proses keluar (Logout).
     * Fungsi ini dimodifikasi agar membersihkan sesi secara total.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout dari guard web
        Auth::guard('web')->logout();

        // Menghapus semua data sesi (Sangat penting agar role Admin tidak tertinggal)
        $request->session()->invalidate(); 

        // Membuat ulang token CSRF baru untuk keamanan
        $request->session()->regenerateToken(); 

        // Kembali ke halaman beranda setelah logout berhasil
        return redirect('/'); 
    }
}