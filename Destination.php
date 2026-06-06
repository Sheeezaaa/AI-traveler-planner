<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'best_season',
        'estimated_cost',
        'coordinates',
        'image_path'
    ];

    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
