<?php

namespace Database\Seeders; // Pakai ini, JANGAN DatabaseSeeder

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
        // Buat atau Update Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Kunci pengecekan
            [
                'name'     => 'Admin Ganteng',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // Buat atau Update Kasir
        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'], // Kunci pengecekan
            [
                'name'     => 'Kasir Toko',
                'username' => 'kasir',
                'password' => Hash::make('password123'),
                'role'     => 'kasir',
            ]
        );
    }
}
