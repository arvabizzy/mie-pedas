<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // File: app/Models/Menu.php
protected $fillable = [
    'nama_menu',
    'harga',
    'foto',
    'kategori',
    'deskripsi', // Pastikan baris ini ADA
    'stok'
];
}
