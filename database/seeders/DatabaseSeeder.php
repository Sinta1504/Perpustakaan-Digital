<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan BookSeeder (Data Buku)
        $this->call([
            BookSeeder::class,
        ]);

        // 2. Akun ADMIN
        User::create([
            'name'     => 'Administrator Perpustakaan',
            'email'    => 'admin@test.com',
            'password' => Hash::make('admin123'), // Passwordnya: admin123
            'role'     => 'admin',
        ]);

        // 3. Akun USER (Sinta)
        User::create([
            'name'     => 'Sinta Pembaca',
            'email'    => 'sinta@test.com',
            'password' => Hash::make('user123'), // Passwordnya: user123
            'role'     => 'user',
        ]);

        // 4. Akun USER (Zara)
        User::create([
            'name'     => 'Zara Penulis',
            'email'    => 'zara@test.com',
            'password' => Hash::make('user123'),
            'role'     => 'user',
        ]);
    }
}