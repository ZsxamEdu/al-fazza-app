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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // bolu, pastry, cookies, roti
            $table->string('nama');
            $table->integer('harga');
            $table->string('gambar');
            $table->integer('stok')->default(0); // Stok awal selalu 0
            $table->text('deskripsi')->nullable();
            $table->text('bahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
