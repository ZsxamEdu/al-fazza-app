<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone', 
        'delivery_address',
        'delivery_date', 
        'notes',
        'order_type',
        'total_amount',
        'payment_status',
        'order_status', 
        'custom_details',
        'payment_method',
        'amount_paid',
        'change_amount',
        'snap_token',
        'token',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'transaction_id');
    }
}
