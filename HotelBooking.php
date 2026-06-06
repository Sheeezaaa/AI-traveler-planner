<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'email',
        'phone',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
