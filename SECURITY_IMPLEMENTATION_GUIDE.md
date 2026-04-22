# Security Audit - Quick Implementation Guide
## Vehicle Booking System - Remediation Checklist

---

## 🔴 CRITICAL FIXES (Complete Week 1)

### 1. Implement Authorization Policies (Priority: CRITICAL)

#### Step 1: Create Vehicle Policy
**File**: `app/Policies/VehiclePolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'admin';
    }

    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'admin';
    }

    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->role === 'admin';
    }
}
```

#### Step 2: Create Booking Policy
**File**: `app/Policies/BookingPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Booking $booking): bool
    {
        // Users can view their own bookings, approvers and admins can view all
        return $user->id === $booking->user_id || 
               in_array($user->role, ['approver', 'admin']);
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can create
    }

    public function update(User $user, Booking $booking): bool
    {
        // Only the creator can update pending bookings
        return $user->id === $booking->user_id && $booking->status === 'pending';
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->role === 'admin';
    }
}
```

#### Step 3: Create Approval Policy
**File**: `app/Policies/ApprovalPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Approval;

class ApprovalPolicy
{
    public function view(User $user, Approval $approval): bool
    {
        // Only the assigned approver can view
        return $user->id === $approval->approver_id;
    }

    public function approve(User $user, Approval $approval): bool
    {
        return $user->id === $approval->approver_id && 
               $approval->status === 'pending';
    }

    public function reject(User $user, Approval $approval): bool
    {
        return $user->id === $approval->approver_id && 
               $approval->status === 'pending';
    }
}
```

#### Step 4: Register Policies
**File**: `app/Providers/AuthServiceProvider.php`

```php
<?php

namespace App\Providers;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Approval;
use App\Models\User;
use App\Models\Department;
use App\Models\Region;
use App\Policies\VehiclePolicy;
use App\Policies\BookingPolicy;
use App\Policies\ApprovalPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Vehicle::class => VehiclePolicy::class,
        Booking::class => BookingPolicy::class,
        Approval::class => ApprovalPolicy::class,
    ];

    public function boot(): void
    {
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('approver', function (User $user) {
            return in_array($user->role, ['approver', 'admin']);
        });
    }
}
```

### 2. Update Controllers with Authorization

#### VehicleController.php
```php
public function update(Request $request, Vehicle $vehicle)
{
    // ADD THIS LINE
    $this->authorize('update', $vehicle);
    
    $validated = $request->validate([
        'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id . '|max:20',
        'vehicle_name' => 'required|string|max:255',
        'vehicle_type' => 'required|in:passenger,cargo',
        'region_id' => 'required|exists:regions,id',
        'status' => 'required|in:available,in_use,maintenance',
    ]);

    $vehicle->update($validated);

    return redirect()->route('vehicles.show', $vehicle)
                    ->with('success', 'Vehicle updated successfully.');
}

public function destroy(Vehicle $vehicle)
{
    // ADD THIS LINE
    $this->authorize('delete', $vehicle);
    
    $vehicle->delete();

    return redirect()->route('vehicles.index')
                    ->with('success', 'Vehicle deleted successfully.');
}
```

#### BookingController.php
```php
public function update(Request $request, Booking $booking)
{
    // ADD THIS LINE
    $this->authorize('update', $booking);
    
    $validated = $request->validate([
        'vehicle_id' => 'required|exists:vehicles,id',
        'driver_id' => 'nullable|exists:users,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'purpose' => 'required|string|max:500',
    ]);

    $booking->update($validated);

    return redirect()->route('bookings.show', $booking)
                    ->with('success', 'Booking updated successfully.');
}
```

#### ApprovalController.php
```php
public function approve(Request $request, Approval $approval)
{
    // ADD THIS LINE
    $this->authorize('approve', $approval);
    
    $approval->update([
        'status' => 'approved',
        'comments' => $request->input('comments'),
        'approved_at' => now(),
    ]);

    // Handle business logic...
}

public function reject(Request $request, Approval $approval)
{
    // ADD THIS LINE
    $this->authorize('reject', $approval);
    
    $approval->update([
        'status' => 'rejected',
        'comments' => $request->input('comments'),
        'rejected_at' => now(),
    ]);

    // Handle business logic...
}
```

### 3. Update Blade Templates with @can

#### vehicles/show.blade.php
```blade
<!-- BEFORE (UNSAFE) -->
@if(auth()->user()->role === 'admin' || auth()->user()->can('update', $vehicle))
    <a href="{{ route('vehicles.edit', $vehicle->id) }}">Edit</a>
@endif

<!-- AFTER (SAFE) -->
@can('update', $vehicle)
    <a href="{{ route('vehicles.edit', $vehicle->id) }}">Edit</a>
@endcan

<!-- DELETE BUTTON -->
@can('delete', $vehicle)
    <form method="POST" action="{{ route('vehicles.destroy', $vehicle->id) }}" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Delete this vehicle?')">Delete</button>
    </form>
@endcan
```

#### bookings/show.blade.php
```blade
<!-- BEFORE (UNSAFE) -->
@if($booking->status === 'pending' && $booking->user_id === auth()->id())
    <a href="{{ route('bookings.edit', $booking->id) }}">Edit</a>
@endif

<!-- AFTER (SAFE) -->
@can('update', $booking)
    <a href="{{ route('bookings.edit', $booking->id) }}">Edit</a>
@endcan
```

#### approvals/show.blade.php
```blade
<!-- BEFORE (UNSAFE) -->
<form method="POST" action="{{ route('approvals.approve', $approval->id) }}">
    @csrf
    <button type="submit">Approve</button>
</form>

<!-- AFTER (SAFE) -->
@can('approve', $approval)
    <form method="POST" action="{{ route('approvals.approve', $approval->id) }}">
        @csrf
        <button type="submit">Approve</button>
    </form>
@endcan
```

---

## 🟠 HIGH PRIORITY (Week 2)

### 4. Move Database Queries to Controllers

#### Problem
Templates contain queries that should be in controllers:
```blade
<!-- BAD: In vehicles/edit.blade.php -->
@foreach(\App\Models\Region::all() as $region)
```

#### Solution

**Step 1: Update Controller**
```php
// app/Http/Controllers/VehicleController.php

public function create()
{
    // Query moved here
    $regions = Region::all();
    return view('vehicles.create', compact('regions'));
}

public function edit(Vehicle $vehicle)
{
    // Query moved here
    $regions = Region::all();
    return view('vehicles.edit', compact('vehicle', 'regions'));
}
```

**Step 2: Update Template**
```blade
<!-- vehicles/edit.blade.php -->
<select name="region_id" class="form-select @error('region_id') is-invalid @enderror">
    <option value="">-- Select Region --</option>
    <!-- Using $regions passed from controller -->
    @foreach($regions as $region)
        <option value="{{ $region->id }}" @selected(old('region_id', $vehicle->region_id) == $region->id)>
            {{ $region->name }}
        </option>
    @endforeach
</select>
```

#### Other Templates to Fix
| Template | Query to Move | New Parameter |
|----------|---|---|
| `users/create.blade.php` | `Department::all()` | `$departments` |
| `users/create.blade.php` | `User::where('role', 'approver'...` | `$approvers` |
| `bookings/create.blade.php` | All queries | Pass in controller |
| `regions/create.blade.php` | None needed | ✅ Already good |
| `departments/index.blade.php` | `Department::all()` | `$departments` |

### 5. Add Rate Limiting to Sensitive Routes

**File**: `routes/web.php`

```php
Route::middleware('auth')->group(function () {
    // Standard routes
    Route::resource('vehicles', VehicleController::class);
    
    // Rate-limited routes (max 10 requests per minute)
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('vehicles', [VehicleController::class, 'store']);
        Route::put('vehicles/{vehicle}', [VehicleController::class, 'update']);
        Route::delete('vehicles/{vehicle}', [VehicleController::class, 'destroy']);
        
        Route::post('bookings', [BookingController::class, 'store']);
        Route::put('bookings/{booking}', [BookingController::class, 'update']);
        Route::delete('bookings/{booking}', [BookingController::class, 'destroy']);
    });
});
```

---

## 🟡 MEDIUM PRIORITY (Week 3)

### 6. Add Optimistic Locking for Concurrent Updates

**Step 1: Create Migration**
```php
// Create migration file
Schema::table('bookings', function (Blueprint $table) {
    $table->timestamp('version')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
});
```

**Step 2: Update Model**
```php
// app/Models/Booking.php

class Booking extends Model
{
    // Add to casts
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'version' => 'datetime', // Add this
    ];

    // Override update method for optimistic locking
    public function update(array $attributes = [], array $options = [])
    {
        // Store the original version
        $originalVersion = $this->version;
        
        // Update the version timestamp
        $attributes['version'] = now();
        
        // Add WHERE clause for version check
        return parent::update($attributes, $options);
    }
}
```

**Step 3: Handle Version Conflicts in Controller**
```php
public function update(Request $request, Booking $booking)
{
    $this->authorize('update', $booking);
    
    $validated = $request->validate([...]);

    try {
        // Attempt update with version check
        $updated = DB::table('bookings')
            ->where('id', $booking->id)
            ->where('version', $booking->version)
            ->update(array_merge($validated, ['version' => now()]));

        if ($updated === 0) {
            return back()->with('error', 'Booking was modified by another user. Please refresh and try again.');
        }

        return redirect()->route('bookings.show', $booking)
                        ->with('success', 'Booking updated successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
```

### 7. Add Custom Validation Rules

**File**: `app/Rules/ValidBookingDate.php` (CREATE NEW)

```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBookingDate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if date is not more than 30 days in advance
        if (now()->addDays(30)->lessThan($value)) {
            $fail('The booking date cannot be more than 30 days in advance.');
        }
    }
}
```

**Usage in Controller**:
```php
$validated = $request->validate([
    'start_date' => ['required', 'date', new ValidBookingDate()],
]);
```

---

## Verification Checklist

### After Implementing Critical Fixes:

- [ ] All policies created and registered in AuthServiceProvider
- [ ] All controllers have `$this->authorize()` calls
- [ ] All templates use `@can()` instead of role checks
- [ ] Test that unauthorized users cannot access/modify resources
- [ ] Test that admins can still perform all operations
- [ ] CSRF tokens still present on all forms

### After Implementing High Priority Fixes:

- [ ] All database queries moved from templates to controllers
- [ ] No `@foreach(\App\Models\...::all()` in templates
- [ ] Rate limiting implemented on POST/PUT/DELETE routes
- [ ] Test rate limiting with multiple rapid requests
- [ ] Monitor for N+1 query issues in production

### After Implementing Medium Priority Fixes:

- [ ] Optimistic locking implemented for Booking model
- [ ] Version conflict errors handled gracefully
- [ ] Custom validation rules working correctly
- [ ] Performance tested with concurrent updates

---

## Testing Commands

```bash
# Test authorization policies
php artisan tinker
> auth()->loginUsingId(1); // Login as user 1
> auth()->user()->can('update', \App\Models\Vehicle::first());

# Test rate limiting
ab -n 20 -c 2 http://localhost:8000/bookings

# Test concurrent updates
php artisan migrate:refresh --seed
# Then run concurrent booking updates

# Check for N+1 queries
php artisan debugbar:publish # If using debugbar
# OR use Laravel Debugbar to inspect queries
```

---

## Deployment Instructions

### Step 1: Pre-Deployment
```bash
# Run tests
php artisan test

# Check policies
php artisan policy:list

# Verify no errors
php artisan tinker
```

### Step 2: Backup
```bash
# Backup database
mysqldump -u root vehicle_booking > backup_$(date +%s).sql
```

### Step 3: Deploy
```bash
# Pull latest code
git pull origin main

# Run migrations (if any schema changes)
php artisan migrate

# Cache config
php artisan config:cache

# Restart queue workers if using jobs
php artisan queue:restart
```

### Step 4: Post-Deployment Verification
```bash
# Test key operations
curl -X GET http://production-url/api/vehicles
curl -X POST http://production-url/bookings (unauthorized)
curl -X POST http://production-url/bookings (authorized)

# Monitor logs for errors
tail -f storage/logs/laravel.log
```

---

**Last Updated**: April 16, 2026  
**Status**: Ready for Implementation  
**Estimated Time**: 3 weeks
