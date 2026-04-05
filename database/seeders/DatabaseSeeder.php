<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil UserSeeder yang sudah lo buat tadi
        $this->call([
            UserSeeder::class,
            // Nanti kalau lo buat MenuSeeder, tinggal tambah baris di bawah ini:
            // MenuSeeder::class,
        ]);
    }
}
