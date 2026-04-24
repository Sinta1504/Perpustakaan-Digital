<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ContactController; // 1. IMPORT CONTROLLER BARU
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIC ROUTES ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route Katalog & Detail Buku
Route::get('/katalog', [BookController::class, 'index'])->name('books.index');
Route::get('/katalog-alias', [BookController::class, 'index'])->name('katalog'); 
Route::get('/katalog/{id}', [BookController::class, 'show'])->name('books.show');


// --- 2. AUTHENTICATED ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    /**
     * DASHBOARD UTAMA
     */
    Route::get('/dashboard', [LoanController::class, 'dashboard'])->name('dashboard');
    Route::get('/home', [LoanController::class, 'dashboard'])->name('home');

    // PERBAIKAN ROUTE HUBUNGI KAMI
    Route::get('/hubungi-kami', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/hubungi-kami', [ContactController::class, 'update'])->name('contact.update');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur User (Pinjaman & Pengembalian)
    Route::get('/pinjaman', [LoanController::class, 'index'])->name('pinjaman');
    Route::get('/pinjam/{id}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/pinjam', [LoanController::class, 'store'])->name('loans.store');
    
    // --- TAMBAHAN: ROUTE DOWNLOAD PDF E-BOOK ---
    Route::get('/pinjaman/download-pdf/{id}', [LoanController::class, 'downloadPdf'])->name('loans.download-pdf');
    
    // Proses Kembalikan Buku
    Route::post('/pinjaman/kembalikan/{id}', [PinjamanController::class, 'kembalikan'])->name('pinjaman.kembalikan');
    
    // Feedback Store
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');


    // --- 3. ADMIN ONLY ROUTES ---
    Route::middleware(['admin'])->group(function () {
        
        Route::get('/admin/monitoring-pinjaman', [LoanController::class, 'allLoans'])->name('admin.loans');
        Route::get('/admin/inventori', [BookController::class, 'inventory'])->name('admin.inventory');
        
        Route::get('/admin/support', function () {
            return view('admin.support');
        })->name('admin.support');
        
        Route::get('/admin/layanan-pengguna', [ProfileController::class, 'manageUsers'])->name('admin.users.index');
        Route::post('/admin/layanan-pengguna/toggle/{id}', [ProfileController::class, 'toggleUserStatus'])->name('admin.users.toggle');

        Route::get('/admin/layanan-pengguna/{id}/respon', [ProfileController::class, 'showRespon'])->name('admin.support.respon');
        Route::post('/admin/layanan-pengguna/{id}/jawab', [ProfileController::class, 'storeRespon'])->name('admin.support.jawab');
        
        Route::get('/admin/suara-peminjam', [FeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::post('/admin/feedback/{id}/reply', [FeedbackController::class, 'reply'])->name('admin.feedback.reply');
        Route::delete('/admin/feedback/{id}', [FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
        
        // CRUD INVENTORI
        Route::resource('books', BookController::class)->except(['index', 'show']);
        Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create.manual');
    });
});

require __DIR__.'/auth.php';