<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PluginUser extends Model
{

    protected $table = 'plugin_user';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'plugin_id',
        'order_id',
        'date'
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
