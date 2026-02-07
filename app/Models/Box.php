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
    ];

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
