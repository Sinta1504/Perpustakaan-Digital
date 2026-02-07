<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // CATATAN: Book::truncate() dihapus agar data lama tidak hilang

        // Data 4 Buku Baru untuk Ditambahkan
        $newBooks = [
            [
                'judul' => 'Harry Potter and the Philosopher\'s Stone',
                'penulis' => 'J.K. Rowling',
                'kategori' => 'Fantasy',
                'stok' => 15,
                'cover' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Kisah seorang anak laki-laki yang mengetahui pada hari ulang tahunnya yang ke-11 bahwa dia adalah penyihir yatim piatu dari dua penyihir kuat.'
            ],
            [
                'judul' => 'Start With Why',
                'penulis' => 'Simon Sinek',
                'kategori' => 'Business',
                'stok' => 8,
                'cover' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Menjelajahi bagaimana para pemimpin besar menginspirasi tindakan dengan memberikan pertanyaan mendasar: Mengapa kita melakukan apa yang kita lakukan?'
            ],
            [
                'judul' => 'The Alchemist',
                'penulis' => 'Paulo Coelho',
                'kategori' => 'Adventure',
                'stok' => 12,
                'cover' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Perjalanan seorang penggembala Spanyol bernama Santiago menuju piramida di Mesir untuk mencari harta karun setelah bermimpi berulang kali.'
            ],
            [
                'judul' => 'Thinking, Fast and Slow',
                'penulis' => 'Daniel Kahneman',
                'kategori' => 'Psychology',
                'stok' => 6,
                'cover' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Buku ini menjelaskan dua sistem yang menggerakkan cara kita berpikir: Sistem 1 yang cepat dan emosional, serta Sistem 2 yang lebih lambat dan logis.'
            ],
        ];

        // Masukkan hanya buku baru ke Database
        foreach ($newBooks as $book) {
            Book::create($book);
        }
    }
}