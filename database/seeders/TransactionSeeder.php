<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat data transaksi utama
        Transaction::insert([
            [
                'invoice_number' => 'INV-001',
                'user_id' => 2,
                'customer_name' => 'Budi Santoso',
                'customer_email' => 'budi@example.com',
                'customer_phone' => '08123456789',
                'delivery_address' => 'Beli di Tempat (Kasir)',
                'delivery_date' => Carbon::now()->format('Y-m-d'),
                'notes' => '-',
                'order_type' => 'kasir',
                'total_amount' => 135000,
                'payment_status' => 'success',
                'order_status' => 'selesai',
                'custom_details' => null,
                'snap_token' => null,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'invoice_number' => 'INV-002',
                'user_id' => null,
                'customer_name' => 'Siti Aminah',
                'customer_email' => 'siti@example.com',
                'customer_phone' => '085712341234',
                'delivery_address' => 'Jl. Kebon Jeruk No. 15, Jakarta',
                'delivery_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'notes' => 'Tolong dikirim agak sore.',
                'order_type' => 'online',
                'total_amount' => 250000,
                'payment_status' => 'success',
                'order_status' => 'dikirim',
                'custom_details' => null,
                'snap_token' => 'dummy_snap_token_123',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'invoice_number' => 'ALF-CUST-999',
                'user_id' => null,
                'customer_name' => 'Rina Custom',
                'customer_email' => 'rina@example.com',
                'customer_phone' => '081199998888',
                'delivery_address' => 'Jl. Mawar Merah No 2, Bandung',
                'delivery_date' => '2026-06-01',
                'notes' => 'Krimnya jangan terlalu tebal ya.',
                'order_type' => 'custom-order',
                'total_amount' => 300000,
                'payment_status' => 'success',
                'order_status' => 'diproses',
                'custom_details' => 'Ukuran: 24 cm | Bentuk: Bulat | Rasa: Bolu Coklat | Isian: Selai Strawberry | Tema: Spiderman Merah Biru | Tulisan: "Happy Birthday Ke-5"',
                'snap_token' => 'dummy_snap_token_custom',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        // 2. Buat data rincian rotinya (Isi dari keranjang belanja)
        // Kita asumsikan product_id 1 dan 2 itu sudah ada dari ProductSeeder
        DB::table('transaction_details')->insert([
            // Isi keranjang untuk INV-001 (Transaksi ID 1)
            [
                'transaction_id' => 1,
                'product_id' => 1, // Misalnya Cheese Cake
                'qty' => 10,
                'price' => 13500,
                'subtotal' => 135000,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            // Isi keranjang untuk INV-002 (Transaksi ID 2)
            [
                'transaction_id' => 2,
                'product_id' => 2, // Misalnya Meses
                'qty' => 10,
                'price' => 11900,
                'subtotal' => 119000,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'transaction_id' => 2,
                'product_id' => 1, 
                'qty' => 9,
                'price' => 13500,
                'subtotal' => 131000, // Total keranjang INV-002 = 250.000
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            // Isi keranjang untuk INV-003 (Transaksi ID 3)
            [
                'transaction_id' => 3,
                'product_id' => 1, 
                'qty' => 6,
                'price' => 13500,
                'subtotal' => 81000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
