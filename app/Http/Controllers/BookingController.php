<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Region;
use App\Models\Approval;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index()
    {
        $bookings = Booking::with('user', 'vehicle', 'approvals')
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create()
    {
        $vehicles = Vehicle::where('status', 'available')->get();
        $drivers = User::where('role', 'user')->get();
        $regions = Region::all();
        $approvers = User::whereIn('role', ['approver', 'admin'])->get();
        return view('bookings.create', compact('vehicles', 'drivers', 'regions', 'approvers'));
    }

    /**
     * Store a newly created booking in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'purpose' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        // Generate booking number
        $bookingNumber = 'BK' . date('YmdHis');

        // Create booking
        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'user_id' => auth()->id(),
            'vehicle_id' => $validated['vehicle_id'],
            'driver_id' => $validated['driver_id'] ?? auth()->id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'purpose' => $validated['purpose'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        // Get approvers for 2-level approval system
        $approvers = User::whereIn('role', ['approver', 'admin'])->get();
        
        if ($approvers->count() >= 2) {
            // Level 1 Approval
            $approver1 = $approvers->first();
            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $approver1->id,
                'level' => 1,
                'status' => 'pending',
            ]);
            
            // Level 2 Approval
            $approver2 = $approvers->skip(1)->first();
            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $approver2->id,
                'level' => 2,
                'status' => 'pending',
            ]);
            
            // Update booking with approver IDs
            $booking->update([
                'approver1_id' => $approver1->id,
                'approver2_id' => $approver2->id,
            ]);
        } elseif ($approvers->count() === 1) {
            // Fallback to single approval if only 1 approver
            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $approvers->first()->id,
                'level' => 1,
                'status' => 'pending',
            ]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'module' => 'booking',
            'description' => 'Created booking ' . $bookingNumber,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('bookings.show', $booking->id)
                        ->with('success', 'Booking created successfully. Awaiting approval.');
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load('user', 'vehicle', 'driver', 'approvals.approver');
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking
     */
    public function edit(Booking $booking)
    {
        // Only allow editing pending bookings (creator or admin)
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be edited.');
        }

        // Creator or admin can edit
        if ($booking->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Only the booking creator or admin can edit.');
        }

        $vehicles = Vehicle::all();
        $drivers = User::all();
        return view('bookings.edit', compact('booking', 'vehicles', 'drivers'));
    }

    /**
     * Update the specified booking in database
     */
    public function update(Request $request, Booking $booking)
    {
        // Only allow updating pending bookings (creator or admin)
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be edited.');
        }

        // Creator or admin can edit
        if ($booking->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Only the booking creator or admin can edit.');
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:users,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'purpose' => 'required|string|max:500',
        ]);

        $booking->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'module' => 'booking',
            'description' => 'Updated booking ' . $booking->booking_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('bookings.show', $booking->id)
                        ->with('success', 'Booking updated successfully.');
    }

    /**
     * Approve a booking (from approver perspective)
     */
    public function approve(Request $request, Booking $booking)
    {
        $approval = Approval::where('booking_id', $booking->id)
                            ->where('approver_id', auth()->id())
                            ->where('status', 'pending')
                            ->firstOrFail();

        $validated = $request->validate([
            'comments' => 'nullable|string|max:500',
        ]);

        // Update approval
        $approval->update([
            'status' => 'approved',
            'comments' => $validated['comments'] ?? null,
            'approved_at' => now(),
        ]);

        // Check if all approvals done
        $pendingApprovals = $booking->approvals()->where('status', 'pending')->count();

        if ($pendingApprovals === 0) {
            $booking->update(['status' => 'approved']);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve',
            'module' => 'booking',
            'description' => 'Approved booking ' . $booking->booking_number . ' (Level ' . $approval->level . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Booking approved successfully.');
    }

    /**
     * Reject a booking
     */
    public function reject(Request $request, Booking $booking)
    {
        $approval = Approval::where('booking_id', $booking->id)
                            ->where('approver_id', auth()->id())
                            ->where('status', 'pending')
                            ->firstOrFail();

        $validated = $request->validate([
            'comments' => 'required|string|max:500',
        ]);

        $approval->update([
            'status' => 'rejected',
            'comments' => $validated['comments'],
            'approved_at' => now(),
        ]);

        $booking->update(['status' => 'rejected']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject',
            'module' => 'booking',
            'description' => 'Rejected booking ' . $booking->booking_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Booking rejected.');
    }

    /**
     * Mark booking as completed
     */
    public function complete(Request $request, Booking $booking)
    {
        // Authorization: hanya creator, admin, atau approver yang bisa complete
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isApprover()) {
            return back()->with('error', 'Unauthorized to complete this booking.');
        }

        if ($booking->status !== 'approved') {
            return back()->with('error', 'Only approved bookings can be completed.');
        }

        $validated = $request->validate([
            'end_km' => [
                'required',
                'integer',
                'min:' . $booking->start_km,
            ],
            'actual_return_date' => [
                'required',
                'date_format:Y-m-d',
            ],
        ], [
            'end_km.min' => 'End KM must be greater than or equal to start KM (' . $booking->start_km . ')',
            'actual_return_date.date_format' => 'Invalid date format',
        ]);

        $booking->update([
            'end_km' => $validated['end_km'],
            'actual_return_date' => $validated['actual_return_date'],
            'status' => 'completed',
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'complete',
            'module' => 'booking',
            'description' => 'Completed booking ' . $booking->booking_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('bookings.show', $booking->id)
                        ->with('success', 'Booking marked as completed.');
    }

    /**
     * Remove the specified booking from database
     */
    public function destroy(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Cannot delete non-pending bookings.');
        }

        $bookingNumber = $booking->booking_number;
        $booking->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'module' => 'booking',
            'description' => 'Deleted booking ' . $bookingNumber,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('bookings.index')
                        ->with('success', 'Booking deleted successfully.');
    }
}
