<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Menambahkan kolom cover_url setelah kolom penulis
            // Kolom ini dibuat nullable agar tidak error jika buku belum punya cover
            $table->string('cover_url')->nullable()->after('penulis');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('cover_url');
        });
    }
};