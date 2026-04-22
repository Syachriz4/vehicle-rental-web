<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of regions
     */
    public function index()
    {
        $regions = Region::withCount('departments', 'vehicles')->paginate(10);
        return view('regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new region
     */
    public function create()
    {
        return view('regions.create');
    }

    /**
     * Store a newly created region in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:regions|max:50',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'type' => 'required|in:kantor_pusat,kantor_cabang,tambang',
        ]);

        Region::create($validated);

        return redirect()->route('regions.index')
                        ->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified region
     */
    public function show(Region $region)
    {
        return view('regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified region
     */
    public function edit(Region $region)
    {
        return view('regions.edit', compact('region'));
    }

    /**
     * Update the specified region in database
     */
    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:regions,code,' . $region->id . '|max:50',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'type' => 'required|in:kantor_pusat,kantor_cabang,tambang',
        ]);

        $region->update($validated);

        return redirect()->route('regions.index')
                        ->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified region from database
     */
    public function destroy(Region $region)
    {
        if ($region->departments()->exists() || $region->vehicles()->exists()) {
            return back()->with('error', 'Cannot delete region with related data.');
        }

        $region->delete();

        return redirect()->route('regions.index')
                        ->with('success', 'Region deleted successfully.');
    }
}
