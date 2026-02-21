<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users (Menyelesaikan error "Unknown column user_id")
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke tabel books
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            
            // Status peminjaman
            $table->enum('status', ['dipinjam', 'kembali', 'terlambat'])->default('dipinjam');
            
            // Kolom untuk fitur ulasan dan rating (Suara Peminjam)
            $table->text('ulasan')->nullable();
            $table->integer('rating')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};