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
        Schema::table('books', function (Blueprint $table) {
            // Menambahkan kolom pdf_file setelah kolom deskripsi (atau kolom lain yang ada)
            // nullable() digunakan agar buku lama yang belum punya PDF tidak error
            $table->string('pdf_file')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('pdf_file');
        });
    }
};