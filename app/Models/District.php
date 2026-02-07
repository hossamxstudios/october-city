<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'city_id',
        'name',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function sectors(): HasMany
    {
        return $this->hasMany(Sector::class);
    }

    public function getSectorsCountAttribute(): int
    {
        return $this->sectors()->count();
    }

    public function lands(): HasMany
    {
        return $this->hasMany(Land::class);
    }

}
