<?php

namespace App\Models\Plugins;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PluginSale extends Model
{

    protected $table = 'plugin_sale';
    public $timestamps = false;

    protected $fillable = [
        'plugin',
        'percentage',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'plugin' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'percentage' => 'float'
    ];

    public function getStartDate(): Carbon {
        return Carbon::parse($this->start_date);
    }

    public function getEndDate(): Carbon {
        return Carbon::parse($this->end_date);
    }

    public function getPlugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class, 'plugin');
    }

    public function isRunning(): bool
    {
        return Carbon::now()->isBetween($this->start_date, $this->end_date);
    }

}
