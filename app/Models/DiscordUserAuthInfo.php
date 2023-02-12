<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordUserAuthInfo extends Model
{
    use HasFactory;

    protected $table = 'users_discord_auth';

    protected $fillable = [
        'token',
        'discord_id',
        'discord_username',
        'discord_discriminator',
        'discord_email',
        'discord_verified'
    ];

}
