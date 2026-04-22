<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles
     */
    public function index()
    {
        $vehicles = Vehicle::with('region')->paginate(10);
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle
     */
    public function create()
    {
        $regions = Region::all();
        return view('vehicles.create', compact('regions'));
    }

    /**
     * Store a newly created vehicle in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles|max:20',
            'vehicle_name' => 'required|string|max:255',
            'vehicle_type' => 'required|in:passenger,cargo',
            'region_id' => 'required|exists:regions,id',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'purchase_date' => 'nullable|date',
            'current_km' => 'nullable|integer|min:0',
            'last_service_date' => 'nullable|date',
            'status' => 'required|in:available,in_use,maintenance',
            'notes' => 'nullable|string',
        ]);

        // Set defaults for optional fields
        $validated['current_km'] = $validated['current_km'] ?? 0;
        $validated['is_rental'] = false;

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
                        ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle
     */
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle
     */
    public function edit(Vehicle $vehicle)
    {
        $regions = Region::all();
        return view('vehicles.edit', compact('vehicle', 'regions'));
    }

    /**
     * Update the specified vehicle in database
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id . '|max:20',
            'vehicle_name' => 'required|string|max:255',
            'vehicle_type' => 'required|in:passenger,cargo',
            'region_id' => 'required|exists:regions,id',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'purchase_date' => 'nullable|date',
            'current_km' => 'nullable|integer|min:0',
            'last_service_date' => 'nullable|date',
            'status' => 'required|in:available,in_use,maintenance',
            'notes' => 'nullable|string',
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
                        ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from database
     */
    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->bookings()->exists()) {
            return back()->with('error', 'Cannot delete vehicle with active bookings.');
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
                        ->with('success', 'Vehicle deleted successfully.');
    }
}
