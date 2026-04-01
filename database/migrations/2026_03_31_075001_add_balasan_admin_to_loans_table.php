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
        Schema::table('loans', function (Blueprint $table) {
            // Menambahkan kolom balasan setelah kolom ulasan
            $table->text('balasan_admin')->nullable()->after('ulasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('balasan_admin');
        });
    }
};