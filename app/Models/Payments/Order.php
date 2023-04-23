<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends \Illuminate\Database\Eloquent\Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'plugin_id',
        'order_id',
        'payment_amount',
        'status',
        'breakdown'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function plugin()
    {
        return $this->belongsTo(\App\Models\Plugins\Plugin::class);
    }

}
