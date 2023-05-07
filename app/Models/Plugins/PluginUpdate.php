<?php

namespace App\Models\Plugins;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use function PHPUnit\Framework\fileExists;

class PluginUpdate extends Model
{

    protected $table = 'plugin_updates';
    public $timestamps = false;

    protected $attributes = [
        'file_extension' => 'jar'
    ];

    protected $casts = [
        'plugin' => 'integer',
        'beta_number' => 'integer',
        'date' => 'datetime',
        'downloads' => 'integer'
    ];

    protected $fillable = [
        'plugin',
        'title',
        'version',
        'beta_number',
        'changelog',
        'downloads',
        'file_extension'
    ];

    public static function getVersionDisplayName($version, $betaNumber): string {
        if ($betaNumber > 0) {
            return $version . ' Beta ' . $betaNumber;
        }
        return $version ?? 'Unknown';
    }

    public function getDisplayName(): string
    {
        return self::getVersionDisplayName($this->version, $this->beta_number);
    }

    public static function getVersionInfoFromString($string): array {
        $version = $string;
        $betaNumber = 0;
        if (str_starts_with($string, 'SNAPSHOT-')) {
            $string = substr($string, 9);
            if (str_contains($string, '_')) {
                $parts = explode('_', $string);
                $version = $parts[0];
                $betaNumber = $parts[1];
            }
        }

        return [
            'version' => $version,
            'beta_number' => $betaNumber
        ];
    }

    public function getFileName($withExtension = true): string
    {
        if ($this->beta_number > 0) {
            return 'SNAPSHOT-' . $this->version . '_' . $this->beta_number . ($withExtension ? "." . $this->file_extension : '');
        }
        return $this->version . ($withExtension ? "." . $this->file_extension : '');
    }

    public function getFilePath(): string {
        return \config('filesystems.links.uploads') . $this->plugin . '/' . $this->getFileName();
    }

    public function getFileDetails($withFileExtension = true): string|null {
        $path = $this->getFilePath();
        if (file_exists($path)) {
            return filesize($path);
        }
        return null;
    }

    public function getPlugin(): BelongsTo
    {
        return $this->belongsTo(Plugin::class, 'plugin');
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr['effective_version'] = $this->getDisplayName();
        $arr['file_size'] = $this->getFileDetails(false);
        $arr['file_name'] = $this->getFileName(false);

        return $arr;
    }

}
