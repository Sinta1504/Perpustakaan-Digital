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
     * Pastikan 'stok' sudah masuk di sini agar fungsi tambah buku tidak error.
     */
    protected $fillable = [
        'judul', 
        'penulis', 
        'kategori', 
        'stok', 
        'cover', 
        'deskripsi'
    ];

    /**
     * Relasi ke model Loan (Peminjaman).
     * Satu buku bisa memiliki banyak catatan peminjaman.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}