<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PluginUser extends Model
{

    protected $table = 'plugin_user';

    protected $fillable = [
        'user_id',
        'plugin_id',
        'payment_id',
        'date'
    ];

}
