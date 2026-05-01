<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans'; 

    protected $fillable = [
        'user_id', 
        'book_id', 
        'nama_peminjam',   
        'nomor_identitas', 
        'tanggal_pinjam', 
        'tanggal_kembali', 
        'status', 
        'denda',           
        'rating',          
        'ulasan',          
        'balasan_admin' // Pastikan ini ada
    ];

    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tanggal_kembali' => 'date', 
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}