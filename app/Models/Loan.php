<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'nama_peminjam',
        'nomor_identitas',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    /**
     * Relasi ke model Book
     * Satu data peminjaman dimiliki oleh satu buku
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relasi ke model User (Opsional)
     * Satu data peminjaman dimiliki oleh satu user/admin
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}