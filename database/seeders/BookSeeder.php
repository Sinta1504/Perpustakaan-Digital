<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newBooks = [
            [
                'judul' => 'Harry Potter and the Philosopher\'s Stone',
                'penulis' => 'J.K. Rowling',
                'kategori' => 'Fantasy', // Kolom ini wajib ada!
                'stok' => 15,
                'cover_url' => 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Kisah seorang anak laki-laki penyihir.'
            ],
            [
                'judul' => 'Start With Why',
                'penulis' => 'Simon Sinek',
                'kategori' => 'Business',
                'stok' => 8,
                'cover_url' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Menjelajahi inspirasi dalam kepemimpinan.'
            ],
            [
                'judul' => 'The Alchemist',
                'penulis' => 'Paulo Coelho',
                'kategori' => 'Adventure',
                'stok' => 12,
                'cover_url' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Perjalanan mengejar mimpi.'
            ],
            [
                'judul' => 'Thinking, Fast and Slow',
                'penulis' => 'Daniel Kahneman',
                'kategori' => 'Psychology',
                'stok' => 6,
                'cover_url' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=1000&auto=format&fit=crop',
                'deskripsi' => 'Dua sistem yang menggerakkan cara kita berpikir.'
            ],
        ];

        foreach ($newBooks as $book) {
            Book::updateOrCreate(
                ['judul' => $book['judul']],
                $book
            );
        }
    }
}