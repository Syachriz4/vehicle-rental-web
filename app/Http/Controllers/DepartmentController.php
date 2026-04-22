<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Region;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index()
    {
        $departments = Department::with('region')->paginate(10);
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        $regions = Region::all();
        return view('departments.create', compact('regions'));
    }

    /**
     * Store a newly created department in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments|max:50',
            'location' => 'nullable|string|max:255',
            'head_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'region_id' => 'required|exists:regions,id',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
                        ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department
     */
    public function edit(Department $department)
    {
        $regions = Region::all();
        return view('departments.edit', compact('department', 'regions'));
    }

    /**
     * Update the specified department in database
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code,' . $department->id . '|max:50',
            'location' => 'nullable|string|max:255',
            'head_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'region_id' => 'required|exists:regions,id',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
                        ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from database
     */
    public function destroy(Department $department)
    {
        if ($department->users()->exists()) {
            return back()->with('error', 'Cannot delete department with related users.');
        }

        $department->delete();

        return redirect()->route('departments.index')
                        ->with('success', 'Department deleted successfully.');
    }
}
