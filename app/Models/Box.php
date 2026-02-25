<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rack_id',
        'name',
        'barcode',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($box) {
            if (empty($box->barcode)) {
                $box->barcode = self::generateBarcode();
            }
        });
    }

    public static function generateBarcode(): string
    {
        do {
            $barcode = 'BX' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }

    public function getFullPathAttribute(): string
    {
        $rack = $this->rack;
        $stand = $rack?->stand;
        $lane = $stand?->lane;
        $room = $lane?->room;

        return implode(' → ', array_filter([
            $room?->name ? 'غرفة ' . $room->name : null,
            $lane?->name ? 'ممر ' . $lane->name : null,
            $stand?->name ? 'ستاند ' . $stand->name : null,
            $rack?->name ? 'رف ' . $rack->name : null,
        ]));
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function lands(): HasMany
    {
        return $this->hasMany(Land::class);
    }

    public function getFilesCountAttribute(): int
    {
        return $this->files()->count();
    }
}
