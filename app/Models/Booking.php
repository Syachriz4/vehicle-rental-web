<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'vehicle_id',
        'driver_id',
        'approver1_id',
        'approver2_id',
        'start_date',
        'end_date',
        'actual_return_date',
        'purpose',
        'status',
        'fuel_used',
        'start_km',
        'end_km',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'actual_return_date' => 'datetime',
    ];

    /**
     * Get the user who created this booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle for this booking
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the assigned driver for this booking
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the level 1 approver
     */
    public function approver1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver1_id');
    }

    /**
     * Get the level 2 approver
     */
    public function approver2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver2_id');
    }

    /**
     * Get the approvals for this booking
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Get the fuel consumption records for this booking
     */
    public function fuelConsumptions(): HasMany
    {
        return $this->hasMany(FuelConsumption::class);
    }
}
