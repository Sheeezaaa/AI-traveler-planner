<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'day_plans',
        'estimated_transport',
        'estimated_food',
        'estimated_activities',
        'estimated_hotels',
        'total_estimated'
    ];

    protected $casts = [
        'day_plans' => 'array',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
