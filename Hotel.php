<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'price_per_night',
        'rating',
        'tier',
        'contact_info',
        'image_path'
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }
}
