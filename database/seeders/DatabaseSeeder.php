<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // Mengisi data buku
    $this->call([
        BookSeeder::class,
    ]);

    // Membuat akun admin otomatis
    \App\Models\User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'), // Passwordnya adalah: password
    ]);
}
}
