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
     */
    protected $fillable = [
        'judul', 
        'penulis', 
        'kategori', 
        'stok', 
        'cover',      // Untuk upload manual
        'cover_url',  // Untuk gambar otomatis
        'status',     // Untuk statistik buku (Tersedia/Rusak/Hilang)
        'deskripsi'
    ];

    /**
     * Relasi ke model Loan (Peminjaman).
     * Satu buku bisa memiliki banyak catatan peminjaman.
     * Digunakan untuk menghitung "Buku Terpopuler" menggunakan withCount('loans').
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}