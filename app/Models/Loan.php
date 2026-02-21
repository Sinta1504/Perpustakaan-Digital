<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'book_id', 
        'nama_peminjam',   
        'nomor_identitas', 
        'tanggal_pinjam', 
        'tanggal_kembali', 
        'status', 
        'rating',          
        'ulasan',          
        'admin_reply'      // Kolom untuk balasan admin
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}