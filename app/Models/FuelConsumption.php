<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelConsumption extends Model
{
    protected $table = 'fuel_consumption';
    
    protected $fillable = [
        'vehicle_id',
        'booking_id',
        'amount',
        'price',
        'fuel_date',
        'km_at_fuel',
        'notes',
    ];

    protected $casts = [
        'fuel_date' => 'date',
    ];

    /**
     * Get the vehicle this fuel consumption belongs to
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the booking this fuel consumption belongs to
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
