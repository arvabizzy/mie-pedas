<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
            // Isinya cukup segini aja:
Schema::create('menus', function (Blueprint $table) {
    $table->id();
    $table->string('nama_menu');
    $table->integer('harga');
    $table->string('foto')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
