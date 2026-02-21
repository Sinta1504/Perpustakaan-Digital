<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PinjamanController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| 1. Public / Guest Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/katalog', [BookController::class, 'index'])->name('katalog');
Route::get('/katalog/{book}', [BookController::class, 'show'])->name('books.show');


/*
|--------------------------------------------------------------------------
| 2. Authenticated Routes (Semua User Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (Home)
    Route::get('/home', [LoanController::class, 'dashboard'])->name('home');
    Route::get('/dashboard', [LoanController::class, 'dashboard'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur Khusus User Biasa (Zara, Sinta, Andhika)
    // Di UI Sidebar nanti, menu ini harus disembunyikan dari Admin menggunakan @if(Auth::user()->role !== 'admin')
    Route::get('/pinjaman', [LoanController::class, 'index'])->name('pinjaman');
    Route::get('/pinjam/{id}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/pinjam', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/pinjaman/kembalikan/{id}', [LoanController::class, 'returnBook'])->name('pinjaman.kembalikan');

    // Hubungi Kami
    Route::get('/hubungi-kami', function () {
        return view('contact.index'); 
    })->name('contact.index');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    /*
    |--------------------------------------------------------------------------
    | 3. Admin Only Routes (Moderasi & Kontrol Sistem)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        
        // --- MONITORING PINJAMAN (Dahulu Panel Admin) ---
        // Menampilkan: Nama Peminjam, No Buku, Judul, Tenggat, Status, & Filter
        Route::get('/admin/monitoring-pinjaman', [LoanController::class, 'allLoans'])->name('admin.loans');

        // --- LAYANAN PENGGUNA (Blokir Akun & Verifikasi Update) ---
        Route::get('/admin/layanan-pengguna', [ProfileController::class, 'manageUsers'])->name('admin.users.index');
        Route::patch('/admin/layanan-pengguna/{user}/toggle-status', [ProfileController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::patch('/admin/layanan-pengguna/{user}/verify', [ProfileController::class, 'verifyUser'])->name('admin.users.verify');

        // --- SUARA PEMINJAM (Manajemen Feedback/Ulasan) ---
        Route::get('/admin/suara-peminjam', [FeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::post('/admin/suara-peminjam/{id}/reply', [FeedbackController::class, 'reply'])->name('admin.feedback.reply');
        Route::delete('/admin/suara-peminjam/{id}', [FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');

        // --- INVENTORI BUKU ---
        Route::get('/admin/inventori', [BookController::class, 'inventory'])->name('admin.inventory');
        Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create');
        Route::post('/buku/tambah', [BookController::class, 'store'])->name('books.store');
        Route::get('/buku/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/buku/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/buku/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });
});

require __DIR__.'/auth.php';