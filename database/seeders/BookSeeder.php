<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'judul' => 'Harry Potter and the Philosopher\'s Stone',
                'penulis' => 'J.K. Rowling',
                'kategori' => 'Fantasy',
                'stok' => 15,
                'cover' => 'covers/hp1.jpg'
            ],
            [
                'judul' => 'Start With Why',
                'penulis' => 'Simon Sinek',
                'kategori' => 'Business',
                'stok' => 8,
                'cover' => 'covers/start_with_why.jpg'
            ],
            [
                'judul' => 'The Alchemist',
                'penulis' => 'Paulo Coelho',
                'kategori' => 'Adventure',
                'stok' => 12,
                'cover' => 'covers/alchemist.jpg'
            ],
            [
                'judul' => 'Thinking, Fast and Slow',
                'penulis' => 'Daniel Kahneman',
                'kategori' => 'Psychology',
                'stok' => 6,
                'cover' => 'covers/thinking.jpg'
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}