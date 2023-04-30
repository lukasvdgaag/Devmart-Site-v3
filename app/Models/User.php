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
        'discord_id',
        'discord_verified',
        'email_verified_at',
        'role',
        'email',
        'password',
        'spigot',
        'spigot_verified',
        'discord_suggestion_notifications',
        'theme',
        'username_changed_at',
        'remember_token'
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

        return Plugin::query()
            ->join('plugin_user', 'plugins.id', '=', 'plugin_user.plugin_id')
            ->whereNested(function ($closure) {
                $closure->where('plugins.author', $this->id)
                    ->orWhere('plugin_user.user_id', $this->id);
            });
    }

    public function isAdmin(): bool {
        return $this->role === "admin";
    }

}
