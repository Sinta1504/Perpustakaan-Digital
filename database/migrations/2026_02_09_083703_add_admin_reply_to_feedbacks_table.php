<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedbacks', function (Blueprint $blueprint) {
            // Menambahkan kolom untuk menyimpan balasan admin (boleh kosong/nullable)
            $blueprint->text('admin_reply')->nullable()->after('pesan');
        });
    }

    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $blueprint) {
            $blueprint->dropColumn('admin_reply');
        });
    }
};