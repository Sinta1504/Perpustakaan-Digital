<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [BookController::class, 'home'])->name('home');
Route::get('/katalog', [BookController::class, 'index'])->name('katalog');
Route::get('/katalog/{book}', [BookController::class, 'show'])->name('books.show');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Semua Pengguna Terdaftar)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [LoanController::class, 'dashboard'])->name('dashboard');
    
    /**
     * FITUR PROFIL SAYA
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * FITUR PEMINJAMAN & PENGEMBALIAN
     */
    Route::get('/pinjaman', [LoanController::class, 'index'])->name('pinjaman');
    Route::get('/pinjam/{book}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/pinjam/{book}', [LoanController::class, 'store'])->name('loans.store');
    
    // Rute utama pengembalian buku
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

    /**
     * FITUR HUBUNGI KAMI & FEEDBACK
     * create: Menampilkan form (Halaman Hubungi Kami)
     * store: Menyimpan rating buku atau saran sistem
     */
    Route::get('/hubungi-kami', [FeedbackController::class, 'create'])->name('contact.index');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    /*
    |----------------------------------------------------------------------
    | KHUSUS ADMIN ONLY
    |----------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        
        // Manajemen Feedback Admin
        Route::get('/admin/feedback', [FeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::delete('/admin/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');

        // Panel Manajemen Pinjaman Admin
        Route::get('/admin/semua-pinjaman', [LoanController::class, 'allLoans'])->name('admin.loans');
        
        // Admin menggunakan POST untuk tombol "Selesaikan"
        Route::post('/admin/loans/return/{id}', [LoanController::class, 'returnBook'])->name('admin.loans.return');

        // Inventori & Manajemen User
        Route::get('/admin/inventori', [LoanController::class, 'inventory'])->name('admin.inventory');
        Route::patch('/admin/user/{user}/toggle', [LoanController::class, 'toggleUserStatus'])->name('admin.user.toggle');

        // CRUD Manajemen Buku
        Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create');
        Route::post('/buku/tambah', [BookController::class, 'store'])->name('books.store');
        Route::get('/buku/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/buku/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/buku/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });
});

require __DIR__.'/auth.php';