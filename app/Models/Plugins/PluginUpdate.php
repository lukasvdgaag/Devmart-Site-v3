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
        'plugin' => 'int',
        'beta_number' => 'int',
        'date' => 'datetime',
        'downloads' => 'int'
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
        return PluginUpdate::getVersionDisplayName($this->version, $this->beta_number);
    }

    public function getFileName(): string
    {
        if ($this->beta_number > 0) {
            return 'SNAPSHOT-' . $this->version . '_' . $this->beta_number . "." . $this->file_extension;
        }
        return $this->version . "." . $this->file_extension;
    }

    public function getFilePath(): string {
        return env('DIR_UPLOADS') . $this->plugin . '/' . $this->getFileName();
    }

    public function getFileDetails($withFileExtension = true): string {
        $path = $this->getFilePath();
        if (file_exists($path)) {
            $size = round(filesize($path) / 1024);
            $res = $size . " KB";

            if ($size >= 1024) {
                $res = round($size/1024,2) . " MB";
            }

            if ($withFileExtension) $res .= " (." . $this->file_extension . ")";
            return $res;
        }
        return "No File Found";
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
        $arr['file_name'] = $this->getFileName();

        return $arr;
    }

}
