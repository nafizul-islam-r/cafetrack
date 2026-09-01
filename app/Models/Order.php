<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'user_name',
        'user_email',
        'user_student_id',
        'order_type',
        'payment_method',
        'payment_status',
        'order_status',
        'token_number',
        'bkash_transaction_id',
        'items',
        'subtotal',
        'total',
    ];

    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
