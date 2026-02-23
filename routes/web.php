<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

// --- 1. PUBLIC ROUTES ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/katalog', [BookController::class, 'index'])->name('katalog');
Route::get('/katalog/{book}', [BookController::class, 'show'])->name('books.show');

// --- 2. AUTHENTICATED ROUTES (User & Admin) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [LoanController::class, 'dashboard'])->name('dashboard');
    Route::get('/home', [LoanController::class, 'dashboard'])->name('home');

    // FIX ERROR: Tambahkan rute ini agar Sidebar tidak error lagi
    Route::get('/hubungi-kami', function () {
        return view('contact.index'); 
    })->name('contact.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur User (Pinjaman)
    Route::get('/pinjaman', [LoanController::class, 'index'])->name('pinjaman');
    Route::get('/pinjam/{id}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/pinjam', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/pinjaman/kembalikan/{id}', [LoanController::class, 'returnBook'])->name('pinjaman.kembalikan');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // --- 3. ADMIN ONLY ROUTES ---
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/monitoring-pinjaman', [LoanController::class, 'allLoans'])->name('admin.loans');
        Route::get('/admin/layanan-pengguna', [ProfileController::class, 'manageUsers'])->name('admin.users.index');
        Route::get('/admin/inventori', [BookController::class, 'inventory'])->name('admin.inventory');
        Route::get('/admin/suara-peminjam', [FeedbackController::class, 'index'])->name('admin.feedback.index');
        
        // CRUD Buku
        Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create');
        Route::post('/buku/tambah', [BookController::class, 'store'])->name('books.store');
        Route::get('/buku/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/buku/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/buku/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });
});

require __DIR__.'/auth.php';