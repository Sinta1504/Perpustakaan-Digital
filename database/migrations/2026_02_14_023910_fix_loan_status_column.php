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
    Schema::table('loans', function (Blueprint $table) {
        // Mengubah status menjadi string agar fleksibel, 
        // atau pastikan 'kembali' masuk dalam daftar ENUM
        $table->string('status')->default('dipinjam')->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
