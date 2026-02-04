<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
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
| Authenticated Routes (Semua yang login: Admin & User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard & Profil
    Route::get('/dashboard', [LoanController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', function () { return view('profil'); })->name('profil');
    
    // Fitur Peminjaman untuk User
    Route::get('/pinjaman', [LoanController::class, 'index'])->name('pinjaman');
    Route::get('/pinjam/{book}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/pinjam/{book}', [LoanController::class, 'store'])->name('loans.store');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

    /*
    |----------------------------------------------------------------------
    | Khusus Admin Only
    |----------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        // Dashboard Admin & Laporan
        Route::get('/admin/semua-pinjaman', [LoanController::class, 'allLoans'])->name('admin.loans');
        
        // --- ROUTE INVENTORI ---
        Route::get('/admin/inventori', [LoanController::class, 'inventory'])->name('admin.inventory');
        Route::patch('/admin/user/{user}/toggle', [LoanController::class, 'toggleUserStatus'])->name('admin.user.toggle');

        // CRUD Buku
        Route::get('/buku/tambah', [BookController::class, 'create'])->name('books.create');
        Route::post('/buku/tambah', [BookController::class, 'store'])->name('books.store');
        Route::get('/buku/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/buku/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/buku/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Profile Settings
    |----------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';