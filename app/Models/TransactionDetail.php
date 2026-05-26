<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty',
        'price',
        'subtotal',
    ];

    public function product()
    {
        // Memberi tahu bahwa kolom 'product_id' terhubung ke tabel Product
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relasi kembali ke Transaksi Utama
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }


}
