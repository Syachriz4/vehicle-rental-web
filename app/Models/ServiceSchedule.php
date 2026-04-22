<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSchedule extends Model
{
    protected $fillable = [
        'vehicle_id',
        'service_type',
        'scheduled_date',
        'completed_date',
        'status',
        'estimated_cost',
        'actual_cost',
        'notes',
        'completion_notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
    ];

    /**
     * Relationship: Service schedule belongs to a vehicle
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Scope: Get pending services
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get completed services
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Get overdue services
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('scheduled_date', '<', now()->toDateString());
    }

    /**
     * Scope: Filter by vehicle
     */
    public function scopeForVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    /**
     * Check if service is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->scheduled_date->isPast();
    }

    /**
     * Check if service is upcoming (within 7 days)
     */
    public function isUpcoming(): bool
    {
        return $this->status === 'pending' 
            && $this->scheduled_date->isBetween(now(), now()->addDays(7));
    }

    /**
     * Mark service as completed
     */
    public function markCompleted($actualCost = null, $completionNotes = null): bool
    {
        $this->update([
            'status' => 'completed',
            'completed_date' => now()->toDateString(),
            'actual_cost' => $actualCost ?? $this->estimated_cost,
            'completion_notes' => $completionNotes,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'module' => 'service_schedules',
            'action' => 'complete',
            'description' => 'Completed service schedule ID ' . $this->id . ' - Type: ' . $this->service_type . ' - Cost: ' . ($actualCost ?? $this->estimated_cost),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return true;
    }
}
