<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIC ROUTES (Bisa diakses tanpa login) ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rute Katalog (Menampilkan semua buku)
Route::get('/katalog', [BookController::class, 'index'])->name('katalog');
Route::get('/katalog/{book}', [BookController::class, 'show'])->name('books.show');


// --- 2. AUTHENTICATED ROUTES (User & Admin) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [LoanController::class, 'dashboard'])->name('dashboard');
    Route::get('/home', [LoanController::class, 'dashboard'])->name('home');

    // Mencegah error Sidebar
    Route::get('/hubungi-kami', function () {
        return view('contact.index'); 
    })->name('contact.index');

    // Profile Management (Sisi User)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur User (Pinjaman & Pengembalian)
    Route::get('/pinjaman', [LoanController::class, 'index'])->name('pinjaman');
    Route::get('/pinjam/{id}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/pinjam', [LoanController::class, 'store'])->name('loans.store');
    Route::post('/pinjaman/kembalikan/{id}', [LoanController::class, 'returnBook'])->name('pinjaman.kembalikan');
    
    // Feedback Store (User kirim ulasan)
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');


    // --- 3. ADMIN ONLY ROUTES (Hanya Role Admin) ---
    Route::middleware(['admin'])->group(function () {
        
        // Monitoring & Layanan Utama
        Route::get('/admin/monitoring-pinjaman', [LoanController::class, 'allLoans'])->name('admin.loans');
        Route::get('/admin/inventori', [BookController::class, 'inventory'])->name('admin.inventory');
        
        // FITUR: Pusat Layanan Pengguna (Pusat Bantuan)
        Route::get('/admin/support', function () {
            return view('admin.support');
        })->name('admin.support');
        
        // Pelayanan Pengguna (Kelola Akun Aktif/Nonaktif)
        Route::get('/admin/layanan-pengguna', [ProfileController::class, 'manageUsers'])->name('admin.users.index');
        Route::post('/admin/layanan-pengguna/toggle/{id}', [ProfileController::class, 'toggleUserStatus'])->name('admin.users.toggle');
        
        // Fitur Suara Peminjam (Feedback & Review)
        Route::get('/admin/suara-peminjam', [FeedbackController::class, 'index'])->name('admin.feedback.index');
        Route::post('/admin/feedback/{id}/reply', [FeedbackController::class, 'reply'])->name('admin.feedback.reply');
        Route::delete('/admin/feedback/{id}', [FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
        
        // --- PERBAIKAN CRUD INVENTORI BUKU ---
        // Kita menggunakan Resource agar tombol edit (books.edit) dan hapus (books.destroy) aktif otomatis
        Route::resource('books', BookController::class)->except(['index', 'show']);
        
        // Rute tambahan agar link lama Anda tetap jalan
        Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create.manual');
    });
});

require __DIR__.'/auth.php';