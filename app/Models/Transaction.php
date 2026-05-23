<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_email',
        'order_type',
        'total_amount',
        'payment_status',
        'payment_method',
        'amount_paid',
        'snap_token', // <-- PASTIKAN INI ADA
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
