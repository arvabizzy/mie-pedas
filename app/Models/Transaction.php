<?php

namespace App\Models;

use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pelanggan',
        'total_harga',
        'pajak',
        'bayar',
        'kembalian'
    ];

    /**
     * Relasi ke Detail Transaksi (PENTING UNTUK STRUK)
     */
    public function transactionDetails()
{
    // Nama fungsi harus SAMA PERSIS dengan yang dipanggil di controller
    return $this->hasMany(TransactionDetail::class);
}

    /**
     * Relasi ke User (Kasir yang melayani)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
