<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (peminjam)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Menghubungkan ke tabel books (opsional, jika review untuk buku tertentu)
            $table->foreignId('book_id')->nullable()->constrained()->onDelete('cascade'); 
            
            // Isi ulasan atau pesan dari user
            $table->text('pesan');
            
            // Penilaian bintang 1-5
            $table->integer('rating')->default(5);
            
            // Pengelompokan jenis masukan
            $table->enum('kategori', ['saran', 'kritik', 'lainnya']);
            
            // Kolom untuk menampung jawaban/balasan dari Admin
            $table->text('admin_reply')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};