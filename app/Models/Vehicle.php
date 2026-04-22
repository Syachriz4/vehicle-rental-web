<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'plate_number',
        'vehicle_name',
        'vehicle_type',
        'region_id',
        'brand',
        'model',
        'year',
        'purchase_date',
        'current_km',
        'last_service_date',
        'status',
        'is_rental',
        'rental_company_name',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'last_service_date' => 'date',
        'is_rental' => 'boolean',
    ];

    /**
     * Get the region this vehicle belongs to
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the bookings for this vehicle
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the fuel consumption records for this vehicle
     */
    public function fuelConsumptions(): HasMany
    {
        return $this->hasMany(FuelConsumption::class);
    }

    /**
     * Get the service schedules for this vehicle
     */
    public function serviceSchedules(): HasMany
    {
        return $this->hasMany(ServiceSchedule::class);
    }
}
