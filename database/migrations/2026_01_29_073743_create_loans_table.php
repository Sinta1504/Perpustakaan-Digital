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
            
            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relasi ke tabel books
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            
            $table->date('tanggal_pinjam');
            $table->date('tanggal_tenggat'); // WAJIB ADA: Untuk acuan denda
            $table->date('tanggal_kembali')->nullable();
            
            // Status peminjaman (Sesuaikan dengan enum di Controller)
            $table->enum('status', ['dipinjam', 'kembali', 'terlambat', 'Sudah Dikembalikan'])->default('dipinjam');
            
            // Fitur Denda (Baru)
            $table->integer('denda')->default(0); // Menyimpan nominal denda
            
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