<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Nama tabel di database (pastikan sesuai migration Anda)
    protected $table = 'feedbacks'; 

    // WAJIB ADA: Daftarkan kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'book_id',
        'pesan',      // Sesuaikan dengan migration (pesan)
        'rating',
        'kategori',
        'admin_reply'
    ];

    // Relasi agar admin bisa melihat nama user
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi agar admin bisa melihat judul buku
    public function book() {
        return $this->belongsTo(Book::class);
    }
}