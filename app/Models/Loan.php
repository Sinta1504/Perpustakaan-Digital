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
        'denda',           
        'rating',          
        'ulasan',          
        'balasan_admin'  // <-- Gunakan nama ini agar sesuai dengan file Blade kamu
    ];

    /**
     * Casting format kolom agar otomatis menjadi objek Carbon.
     */
    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tanggal_kembali' => 'date', 
    ];

    /**
     * Accessor untuk menampilkan format denda dalam Rupiah.
     */
    public function getFormatDendaAttribute()
    {
        return 'Rp ' . number_format($this->denda ?? 0, 0, ',', '.');
    }

    /**
     * Relasi ke model Book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}