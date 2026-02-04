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
        // 1. Bersihkan data lama agar tidak duplikat saat dijalankan ulang
        Schema::disableForeignKeyConstraints();
        Book::truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Data Buku Lengkap dengan Deskripsi
        $books = [
            [
                'judul' => 'Filosofi Teras',
                'penulis' => 'Henry Manampiring',
                'kategori' => 'Self Development',
                'stok' => 12,
                'cover' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=800',
                'deskripsi' => 'Sebuah buku yang memperkenalkan filsafat Stoisisme dalam konteks modern di Indonesia, membantu pembaca menjadi lebih tenang dan tangguh dalam menghadapi tantangan hidup.'
            ],
            [
                'judul' => 'Atomic Habits',
                'penulis' => 'James Clear',
                'kategori' => 'Productivity',
                'stok' => 5,
                'cover' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=800',
                'deskripsi' => 'Panduan praktis untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk dengan memanfaatkan kekuatan perubahan kecil sebesar satu persen setiap harinya.'
            ],
            [
                'judul' => 'Bumi Manusia',
                'penulis' => 'Pramoedya Ananta Toer',
                'kategori' => 'Sastra',
                'stok' => 8,
                'cover' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=800',
                'deskripsi' => 'Kisah epik berlatar zaman penjajahan Belanda yang menceritakan perjuangan Minke, seorang pemuda pribumi, melawan penindasan dan ketidakadilan demi cinta dan kehormatan.'
            ],
            [
                'judul' => 'Laskar Pelangi',
                'penulis' => 'Andrea Hirata',
                'kategori' => 'Novel',
                'stok' => 10,
                'cover' => 'https://images.unsplash.com/photo-1550399105-c4db5fb85c18?q=80&w=800',
                'deskripsi' => 'Menceritakan tentang persahabatan sepuluh anak dari keluarga miskin di Pulau Belitung yang memiliki semangat juang tinggi untuk menempuh pendidikan di sekolah yang hampir roboh.'
            ],
        ];

        // 3. Masukkan data ke Database
        foreach ($books as $book) {
            Book::create($book);
        }
    }
}