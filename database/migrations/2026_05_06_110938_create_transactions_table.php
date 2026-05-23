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
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // ID Kasir
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->enum('order_type', ['kasir', 'online']);
            $table->integer('total_amount');
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('snap_token')->nullable(); // Khusus Midtrans (Payment Gateway)
            $table->string('payment_method')->default('Cash');
            $table->integer('amount_paid')->nullable();
            $table->integer('change_amount')->nullable();
            $table->timestamps(); // Berguna banget untuk Filter Laporan Harian!
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
