<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paste extends Model
{

    protected $attributes = [
        'creator' => 0,
        'title' => 'Unknown Paste',
        'visibility' => 'PUBLIC',
        'lifetime' => '7d'
    ];

    protected $fillable = [
        'name',
        'creator',
        'visibility',
        'style',
        'lifetime',
        'title',
        'expire_at',
        'content'
    ];

    protected $casts = [
        'id' => 'string',
        'creator' => 'int',
    ];

    public function getCreator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator');
    }

    public function hasAccess($user): bool
    {
        if ($this->visibility === "PUBLIC" || $this->visibility === "HIDDEN") return true;

        if ($user instanceof User) {
            return $user->id === $this->creator || $user->role === "admin";
        }
        return false;
    }

    public function hasModifyAccess($user): bool
    {
        if ($user instanceof User) {
            return $user->role === "admin" || $this->creator === $user->id;
        }
        return false;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($paste) {
            $id = $paste->id;
            $location = "/home/pastes/$id.txt.gz";

            if (file_exists($location)) {
                unlink($location);
            }
        });
    }

    use HasFactory;
}
