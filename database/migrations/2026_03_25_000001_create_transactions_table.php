<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users');
        $table->string('nama_pelanggan')->nullable(); // Tambahan untuk struk
        $table->integer('total_harga');
        $table->integer('pajak'); // Tambahkan ini agar tercatat di struk
        $table->integer('bayar');
        $table->integer('kembalian');
        $table->timestamps(); // Ini otomatis jadi Tanggal & Jam
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
