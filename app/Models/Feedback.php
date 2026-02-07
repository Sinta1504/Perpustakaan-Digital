<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan di database.
     */
    protected $table = 'feedbacks';

    /**
     * Kolom-kolom yang boleh diisi secara mass-assignment.
     * Ditambahkan 'book_id' dan 'rating' sesuai perubahan migration terbaru.
     */
    protected $fillable = [
        'user_id', 
        'book_id', 
        'pesan', 
        'kategori', 
        'rating'
    ];

    /**
     * Relasi ke Model User.
     * Menandakan bahwa setiap feedback dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Model Book.
     * Menandakan bahwa feedback/rating ini merujuk pada buku tertentu.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}