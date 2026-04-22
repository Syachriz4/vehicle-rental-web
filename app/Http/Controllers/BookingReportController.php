<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;

class BookingReportController extends Controller
{
    /**
     * Display booking report with filters
     */
    public function index(Request $request)
    {
        $query = Booking::with('user', 'vehicle', 'approvals.approver')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vehicle
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by approver
        if ($request->filled('approver_id')) {
            $query->whereHas('approvals', function ($q) use ($request) {
                $q->where('approver_id', $request->approver_id);
            });
        }

        $bookings = $query->paginate(20);

        // Get filter options
        $vehicles = Vehicle::all();
        $approvers = User::where('role', 'approver')->orWhere('role', 'admin')->get();

        // Calculate summary statistics
        $totalBookings = Booking::count();
        $approvedCount = Booking::where('status', 'approved')->count();
        $rejectedCount = Booking::where('status', 'rejected')->count();
        $pendingCount = Booking::where('status', 'pending')->count();
        $completedCount = Booking::where('status', 'completed')->count();

        $approvalPercentage = $totalBookings > 0 ? round(($approvedCount / $totalBookings) * 100, 2) : 0;
        $rejectionPercentage = $totalBookings > 0 ? round(($rejectedCount / $totalBookings) * 100, 2) : 0;

        return view('reports.bookings.index', compact(
            'bookings',
            'vehicles',
            'approvers',
            'totalBookings',
            'approvedCount',
            'rejectedCount',
            'pendingCount',
            'completedCount',
            'approvalPercentage',
            'rejectionPercentage'
        ));
    }

    /**
     * Export booking report to CSV
     */
    public function export(Request $request)
    {
        $query = Booking::with('user', 'vehicle', 'approvals.approver');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('approver_id')) {
            $query->whereHas('approvals', function ($q) use ($request) {
                $q->where('approver_id', $request->approver_id);
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        // Create CSV
        $filename = 'booking_report_' . now()->format('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'Booking #',
            'Requester',
            'Vehicle',
            'Driver',
            'Start Date',
            'End Date',
            'Purpose',
            'Status',
            'Level 1 Approver',
            'Level 1 Status',
            'Level 2 Approver',
            'Level 2 Status',
            'Created Date'
        ];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            
            // Write header
            fputcsv($file, $columns);

            // Write data
            foreach ($bookings as $booking) {
                $approvals = $booking->approvals->sortBy('level');
                $level1 = $approvals->where('level', 1)->first();
                $level2 = $approvals->where('level', 2)->first();

                fputcsv($file, [
                    $booking->booking_number,
                    $booking->user->name,
                    $booking->vehicle->plate_number,
                    $booking->driver?->name ?? '-',
                    $booking->start_date->format('d M Y H:i'),
                    $booking->end_date->format('d M Y H:i'),
                    $booking->purpose,
                    ucfirst($booking->status),
                    $level1?->approver->name ?? '-',
                    $level1 ? ucfirst($level1->status) : '-',
                    $level2?->approver->name ?? '-',
                    $level2 ? ucfirst($level2->status) : '-',
                    $booking->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
