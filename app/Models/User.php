<?php

namespace App\Models;

use App\Models\Plugins\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'surname',
        'email',
        'password',
        'discord',
        'spigot',
        'discord_suggestion_notifications'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'timestamp',
        'discord_verified' => 'boolean',
        'spigot_verified' => 'boolean',
        'username_changed_at' => 'timestamp'
    ];

    public function getPlugins()
    {
        if ($this->role === "admin") {
            return Plugin::query()
                ->whereNested(function ($closure) {
                    $closure->where('custom', '=', 1)
                        ->orWhere('price', '>', 0);
                });
        }
        return $this->belongsToMany(Plugin::class, 'plugin_user', 'user_id', 'plugin_id')
            ->orWhere('plugins.author', '=', $this->id);
    }
}
