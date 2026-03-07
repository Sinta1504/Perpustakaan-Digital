<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Menambahkan kolom review dan rating ke tabel loans
            $table->text('review')->nullable()->after('status');
            $table->integer('rating')->nullable()->after('review');
        });
    }

    /**
     * Batalkan migrasi (Hapus kolom jika rollback).
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['review', 'rating']);
        });
    }
};