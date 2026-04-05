<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Data Admin
        User::create([
            'name' => 'Admin HotNoodle',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Ganti password sesukamu
            'role' => 'admin',
        ]);

        // Data Kasir
        User::create([
            'name' => 'Kasir Ganteng',
            'username' => 'kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}
