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
            $table->string('kategori'); 
            $table->string('nama');
            $table->string('tipe'); 
            $table->integer('harga');
            $table->decimal('rating', 2, 1)->default(4.9);
            $table->string('gambar');
            $table->integer('stok')->default(0); 
            $table->text('deskripsi')->nullable();
            $table->text('bahan')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
