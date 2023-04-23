<?php

namespace App\Models\Payments;

class Payment extends \Illuminate\Database\Eloquent\Model
{

    protected $fillable = [
        'user_id',
        'plugin_id',
        'order_id',
        'transaction_id',
        'payment_amount',
        'payment_fee',
        'payment_status',
        'item_id',
        'email',
        'platform',
        'verified'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function plugin()
    {
        return $this->belongsTo(\App\Models\Plugins\Plugin::class);
    }

    public function order() {
        return $this->belongsTo(\App\Models\Payments\Order::class);
    }

}
