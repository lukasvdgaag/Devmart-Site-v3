<?php

namespace App\Models\Plugins;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PluginPayment extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'gaagjescraft.mygcnt_payments';
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'plugin_id' => 'int',
        'payment_amount' => 'int',
        'created_at' => 'datetime',
        'verified' => 'boolean'
    ];

    public function getPlugin(): BelongsTo {
        return $this->belongsTo(Plugin::class, 'plugin_id');
    }


}
