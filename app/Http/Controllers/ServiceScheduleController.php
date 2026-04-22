<?php

namespace App\Http\Controllers;

use App\Models\ServiceSchedule;
use App\Models\Vehicle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    /**
     * Display a listing of service schedules
     */
    public function index()
    {
        $schedules = ServiceSchedule::with('vehicle')
            ->orderBy('scheduled_date', 'desc')
            ->paginate(15);

        $pendingCount = ServiceSchedule::pending()->count();
        $overdueCount = ServiceSchedule::overdue()->count();
        $completedCount = ServiceSchedule::completed()->count();
        $totalCount = ServiceSchedule::count();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'module' => 'service_schedules',
            'action' => 'view_list',
            'description' => 'Viewed service schedules list',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return view('service-schedules.index', compact('schedules', 'pendingCount', 'overdueCount', 'completedCount', 'totalCount'));
    }

    /**
     * Show the form for creating a new service schedule
     */
    public function create()
    {
        $vehicles = Vehicle::orderBy('vehicle_name')->get();

        return view('service-schedules.create', compact('vehicles'));
    }

    /**
     * Store a newly created service schedule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'required|in:maintenance,inspection,oil_change,tire_replacement,filter_replacement,coolant_replacement,other',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'estimated_cost' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $schedule = ServiceSchedule::create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'module' => 'service_schedules',
            'action' => 'create',
            'description' => 'Created service schedule for vehicle ID ' . $schedule->vehicle_id . ' - Type: ' . $schedule->service_type,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('service-schedules.show', $schedule)
            ->with('success', 'Service schedule created successfully');
    }

    /**
     * Display a specific service schedule
     */
    public function show(ServiceSchedule $serviceSchedule)
    {
        $serviceSchedule->load('vehicle');

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'module' => 'service_schedules',
            'action' => 'view',
            'description' => 'Viewed service schedule details - Service ID: ' . $serviceSchedule->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return view('service-schedules.show', compact('serviceSchedule'));
    }

    /**
     * Show the form for editing a service schedule
     */
    public function edit(ServiceSchedule $serviceSchedule)
    {
        $vehicles = Vehicle::orderBy('vehicle_name')->get();
        $schedule = $serviceSchedule;

        return view('service-schedules.edit', compact('schedule', 'vehicles'));
    }

    /**
     * Update a service schedule
     */
    public function update(Request $request, ServiceSchedule $serviceSchedule)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'required|in:maintenance,inspection,oil_change,tire_replacement,filter_replacement,coolant_replacement,other',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'notes' => 'nullable|string',
        ]);

        $serviceSchedule->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'module' => 'service_schedules',
            'action' => 'update',
            'description' => 'Updated service schedule ID ' . $serviceSchedule->id . ' - Status: ' . $serviceSchedule->status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('service-schedules.show', $serviceSchedule)
            ->with('success', 'Service schedule updated successfully');
    }

    /**
     * Mark service as completed
     */
    public function markCompleted(Request $request, ServiceSchedule $serviceSchedule)
    {
        $validated = $request->validate([
            'actual_cost' => 'nullable|integer|min:0',
            'completion_notes' => 'nullable|string',
        ]);

        $serviceSchedule->markCompleted(
            $validated['actual_cost'] ?? null,
            $validated['completion_notes'] ?? null
        );

        return redirect()->route('service-schedules.show', $serviceSchedule)
            ->with('success', 'Service marked as completed');
    }

    /**
     * Delete a service schedule
     */
    public function destroy(ServiceSchedule $serviceSchedule)
    {
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'module' => 'service_schedules',
            'action' => 'delete',
            'description' => 'Deleted service schedule ID ' . $serviceSchedule->id . ' for vehicle ID ' . $serviceSchedule->vehicle_id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $serviceSchedule->delete();

        return redirect()->route('service-schedules.index')
            ->with('success', 'Service schedule deleted successfully');
    }

    /**
     * Get service schedule stats (API endpoint)
     */
    public function stats()
    {
        return response()->json([
            'pending' => ServiceSchedule::pending()->count(),
            'overdue' => ServiceSchedule::overdue()->count(),
            'completed' => ServiceSchedule::completed()->count(),
            'upcoming' => ServiceSchedule::where('status', 'pending')
                ->whereBetween('scheduled_date', [now(), now()->addDays(7)])
                ->count(),
        ]);
    }
}
