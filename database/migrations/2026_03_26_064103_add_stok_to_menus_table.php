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
        // Perbaikan: Kita ingin menambah kolom 'stok' ke tabel 'menus',
        // BUKAN membuat tabel 'transaction_details' lagi.
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'stok')) {
                $table->integer('stok')->default(0)->after('harga');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
};
