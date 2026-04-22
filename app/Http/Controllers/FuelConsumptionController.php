<?php

namespace App\Http\Controllers;

use App\Models\FuelConsumption;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class FuelConsumptionController extends Controller
{
    /**
     * Display a listing of fuel consumptions
     */
    public function index()
    {
        $consumptions = FuelConsumption::with('vehicle', 'booking.user')
                                       ->orderBy('fuel_date', 'desc')
                                       ->paginate(10);

        // Calculate statistics
        $totalConsumed = FuelConsumption::sum('amount') ?? 0;
        $totalCost = FuelConsumption::sum('price') ?? 0;
        
        // Average consumption per 100km
        $avgConsumption = $totalConsumed > 0 ? ($totalConsumed / (FuelConsumption::count() ?: 1)) * 10 : 0;
        
        // Highest consumption vehicle
        $highestVehicle = FuelConsumption::selectRaw('vehicle_id, SUM(amount) as total_amount')
                                         ->groupBy('vehicle_id')
                                         ->orderByDesc('total_amount')
                                         ->with('vehicle')
                                         ->first();

        return view('fuel-consumptions.index', compact('consumptions', 'totalConsumed', 'totalCost', 'avgConsumption', 'highestVehicle'));
    }

    /**
     * Show the form for creating a new fuel consumption record
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        $bookings = Booking::where('status', 'completed')->get();
        return view('fuel-consumptions.create', compact('vehicles', 'bookings'));
    }

    /**
     * Store a newly created fuel consumption record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'fuel_date' => 'required|date',
            'km_at_fuel' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        FuelConsumption::create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'module' => 'fuel_consumption',
            'description' => 'Added fuel consumption record',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('fuel-consumptions.index')
                        ->with('success', 'Fuel consumption record created successfully.');
    }

    /**
     * Display the specified fuel consumption record
     */
    public function show(FuelConsumption $fuelConsumption)
    {
        return view('fuel-consumptions.show', compact('fuelConsumption'));
    }

    /**
     * Show the form for editing the specified fuel consumption record
     */
    public function edit(FuelConsumption $fuelConsumption)
    {
        $vehicles = Vehicle::all();
        $bookings = Booking::where('status', 'completed')->get();
        $consumption = $fuelConsumption;
        return view('fuel-consumptions.edit', compact('consumption', 'vehicles', 'bookings'));
    }

    /**
     * Update the specified fuel consumption record
     */
    public function update(Request $request, FuelConsumption $fuelConsumption)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0',
            'fuel_date' => 'required|date',
            'km_at_fuel' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $fuelConsumption->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'module' => 'fuel_consumption',
            'description' => 'Updated fuel consumption record',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('fuel-consumptions.index')
                        ->with('success', 'Fuel consumption record updated successfully.');
    }

    /**
     * Remove the specified fuel consumption record
     */
    public function destroy(FuelConsumption $fuelConsumption)
    {
        $fuelConsumption->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'module' => 'fuel_consumption',
            'description' => 'Deleted fuel consumption record',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('fuel-consumptions.index')
                        ->with('success', 'Fuel consumption record deleted successfully.');
    }

    /**
     * Get fuel consumption statistics for dashboard
     */
    public function statistics()
    {
        $totalFuel = FuelConsumption::sum('amount');
        $totalCost = FuelConsumption::sum('price');
        $averagePrice = FuelConsumption::avg('price');
        $vehicleStats = FuelConsumption::with('vehicle')
                                       ->selectRaw('vehicle_id, SUM(amount) as total_fuel, COUNT(*) as records')
                                       ->groupBy('vehicle_id')
                                       ->get();

        return view('fuel-consumptions.statistics', compact(
            'totalFuel',
            'totalCost',
            'averagePrice',
            'vehicleStats'
        ));
    }
}
