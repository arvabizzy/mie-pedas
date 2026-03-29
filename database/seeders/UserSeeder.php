<?php

namespace DatabaseSeeder;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User ADMIN
        User::create([
            'name'     => 'Admin Ganteng',
            'username' => 'admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Passwordnya ini
            'role'     => 'admin',
        ]);

        // 2. Buat User KASIR
        User::create([
            'name'     => 'Kasir Toko',
            'username' => 'kasir',
            'email'    => 'kasir@gmail.com',
            'password' => Hash::make('password123'), // Passwordnya sama
            'role'     => 'kasir',
        ]);
    }
}
