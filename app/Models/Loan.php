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
        'tanggal_tenggat', // Tambahkan ini agar bisa menyimpan tanggal tenggat
        'tanggal_kembali', 
        'status', 
        'denda',           // Tambahkan ini agar bisa menyimpan nominal denda
        'rating',          
        'ulasan',          
        'admin_reply'      // Kolom untuk balasan admin
    ];

    /**
     * Casting format kolom agar otomatis menjadi objek Carbon.
     */
    protected $casts = [
        'tanggal_pinjam'  => 'date',
        'tanggal_tenggat' => 'date',
        'tanggal_kembali' => 'datetime',
    ];

    /**
     * Accessor untuk menampilkan format denda dalam Rupiah.
     * Panggil di Blade dengan: $loan->format_denda
     */
    public function getFormatDendaAttribute()
    {
        return 'Rp ' . number_format($this->denda ?? 0, 0, ',', '.');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}