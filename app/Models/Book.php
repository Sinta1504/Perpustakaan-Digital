<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment).
     * Saya telah menambahkan 'cover_url' dan 'status' agar sinkron dengan Controller.
     */
    protected $fillable = [
        'judul', 
        'penulis', 
        'kategori', 
        'stok', 
        'cover',      // Untuk upload manual
        'cover_url',  // UNTUK GAMBAR OTOMATIS (PENTING!)
        'status',     // UNTUK STATISTIK BUKU RUSAK (PENTING!)
        'deskripsi'
    ];

    /**
     * Relasi ke model Loan (Peminjaman).
     * Satu buku bisa memiliki banyak catatan peminjaman.
     * Ini digunakan untuk menghitung "Buku Terpopuler" di Inventori.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}