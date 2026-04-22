<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'location',
        'head_name',
        'description',
        'region_id',
    ];

    /**
     * Get the region this department belongs to
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the users in this department
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
