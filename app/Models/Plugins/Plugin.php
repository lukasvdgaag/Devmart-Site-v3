<?php

namespace App\Models\Plugins;

use App\Models\PluginUser;
use App\Models\User;
use App\Utils\WebUtils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Plugin extends Model
{

    protected $attributes = [
        'description' => '',
        'title' => '',
        'features' => '',
        'donation_url' => 'https://www.gcnt.net/donate',
        'price' => 0,
        'logo_url' => 'https://www.gcnt.net/inc/img/default-plugin-image.png',
        'banner_url' => 'https://www.gcnt.net/inc/img/default-banner-image.png'
    ];

    protected $fillable = [
        'description',
        'title',
        'features',
        'custom',
        'dependencies',
        'categories',
        'spigot_link',
        'github_link',
        'donation_url',
        'minecraft_versions',
        'last_updated',
        'price',
        'logo_url',
        'banner_url',
    ];

    protected $casts = [
        'custom' => 'boolean',
        'spigot_link' => 'integer',
        'last_updated' => 'datetime',
        'author' => 'integer',
        'price' => 'float',
    ];

    public function premium(): bool
    {
        return ($this->price ?? 0) > 0;
    }

    public function getDownloads(): int
    {
        return $this->belongsTo(PluginUpdate::class, 'id', 'plugin')->sum('downloads');
    }

    public function getUpdates(): HasMany {
        return $this->hasMany(PluginUpdate::class, 'plugin', 'id')->orderByDesc('created_at');
    }

    public function getAuthor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function hasAccess($user): bool
    {
        if ($this->price == 0 && !$this->custom) return true;

        if ($user instanceof User) {
            return $this->hasModifyAccess($user) || $user->getPlugins()->where('plugins.id', '=', $this->id)->count() !== 0;
        }
        return false;
    }

    public function hasModifyAccess($user): bool
    {
        if ($user != null) {
            return $user->role === "admin" || $this->author === $user->id;
        }
        return false;
    }

    public function getSales(): BelongsTo
    {
        return $this->belongsTo(PluginSale::class, 'id', 'plugin')
            ->whereRaw('ISNULL(end_date) OR end_date >= CURRENT_TIMESTAMP()')
            ->orderBy('end_date', 'desc');
    }

    public function getTransactions(): HasMany
    {
        return $this->hasMany(PluginUser::class)
            ->select('plugin_user.*')
            ->addSelect([
                DB::raw('payments.id AS payment_id'),
                DB::raw('payments.payment_amount - payments.payment_fee AS payment_amount'),
                'payments.email',
                'platform',
                'payment_status',
                'payments.created_at AS payment_date',
                'users.username'
            ])
            ->leftJoin('payments', 'payments.id', '=', 'plugin_user.payment_id')
            ->leftJoin('users', 'users.id', '=', 'plugin_user.user_id')
            ->orderByDesc('plugin_user.date');
    }

    public function getSale(): PluginSale|null
    {
        return $this->getSales()->whereRaw('CURRENT_TIMESTAMP() BETWEEN start_date AND COALESCE(end_date, (CURRENT_TIMESTAMP() + INTERVAL 1 SECOND))')->first();
    }

    public function getSalePart(): float {
        $sale = $this->getSale();
        if ($sale != null) {
            return round((($this->price / 100) * $sale->percentage), 2);
        }
        return 0;
    }

    public function getPrice()
    {
        return $this->price - $this->getSalePart();
    }

    public function getInfoArray(): array
    {
        $formattedDate = WebUtils::formatDate($this->last_updated);

        $return = [
            'plugin' => $this,
            'pluginId' => $this->id,
            'pluginName' => $this->name,
            'title' => $this->title,
            'rawPrice' => $this->price,
            'price' => $this->getPrice(),
            'custom' => $this->custom,
            'downloads' => $this->getDownloads(),
            'author' => $this->getAuthor->getModel()->username,
            'description' => $this->description,
            'features' => $this->features,
            'bannerImage' => $this->banner_url ?? 'https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048',
            'logoUrl' => $this->logo_url ?? asset("img/logo.png"),
            'supportedVersions' => explode(',', $this->minecraft_versions ?? ''),
            'dependencies' => $this->dependencies,
            'highlights' => [],
            'categories' => $this->categories === '' ? [] : explode(',', $this->categories ?? ''),
            'links' => [],
            'downloadInfo' => 'No File Found',
            'updateTime' => $this->last_updated,
        ];

        $latestUpdate = $this->getUpdates()->first();
        if ($latestUpdate != null) {
            $return['downloadInfo'] = $latestUpdate->getFileDetails();
        }

        $return['highlights'][] = [
            'title' => 'Supported Versions',
            'description' => strlen($this->minecraft_versions) === 0 ? "Not specified." : $this->minecraft_versions,
            'image' => asset('img/getbukkit.png')
        ];

        $return['highlights'][] = [
            'title' => 'Last Updated',
            'description' => $this->last_updated,
            'image' => asset('img/calendar.svg')
        ];

        $depends = trim($this->dependencies ?? '');
        if (strlen($depends) !== 0) {
            $return['highlights'][] = [
                'title' => '(Soft) Dependencies',
                'description' => $depends,
                'image' => asset('img/download.svg')
            ];
        }

        if ($this->spigot_link != null && $this->spigot_link !== "") {
            $return['links']['spigot'] = $this->spigot_link;
        }
        if ($this->github_link != null && $this->github_link !== "") {
            $return['links']['github'] = $this->github_link;
        }
        $return['links']['donate'] = $this->donation_url;

        $return['pluginData'] = $return;

        return $return;
    }

    use HasFactory;
}
