<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Display approvals pending for current user
     */
    public function index()
    {
        $pendingApprovals = Approval::where('approver_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->with('booking.user.department', 'booking.vehicle', 'approver')
                                    ->orderBy('created_at', 'asc')
                                    ->paginate(10);

        $approvedApprovals = Approval::where('approver_id', auth()->id())
                                     ->where('status', '!=', 'pending')
                                     ->with('booking.user.department', 'booking.vehicle')
                                     ->orderBy('approved_at', 'desc')
                                     ->paginate(10);

        // Statistics
        $totalPending = Approval::where('approver_id', auth()->id())
                                ->where('status', 'pending')
                                ->count();

        $myApprovals = Approval::where('approver_id', auth()->id())
                               ->where('status', 'pending')
                               ->count();

        $approvedThisWeek = Approval::where('approver_id', auth()->id())
                                    ->where('status', 'approved')
                                    ->whereBetween('approved_at', [now()->startOfWeek(), now()->endOfWeek()])
                                    ->count();

        return view('approvals.index', compact('pendingApprovals', 'approvedApprovals', 'totalPending', 'myApprovals', 'approvedThisWeek'));
    }

    /**
     * Show approval details
     */
    public function show(Approval $approval)
    {
        // Only approver or admin can view
        if (auth()->id() !== $approval->approver_id && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $approval->load('booking.user', 'booking.vehicle', 'booking.driver', 'approver');
        return view('approvals.show', compact('approval'));
    }

    /**
     * Approve booking
     */
    public function approve(Request $request, Approval $approval)
    {
        if (auth()->id() !== $approval->approver_id) {
            return back()->with('error', 'Unauthorized.');
        }

        if ($approval->status !== 'pending') {
            return back()->with('error', 'This approval has already been processed.');
        }

        $validated = $request->validate([
            'comments' => 'nullable|string|max:500',
        ]);

        $booking = $approval->booking;

        $approval->update([
            'status' => 'approved',
            'comments' => $validated['comments'] ?? null,
            'approved_at' => now(),
        ]);

        // Check if all approvals are done
        $pendingApprovals = $booking->approvals()->where('status', 'pending')->count();

        if ($pendingApprovals === 0) {
            $booking->update(['status' => 'approved']);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approve',
            'module' => 'approval',
            'description' => 'Approved booking ' . $booking->booking_number . ' (Level ' . $approval->level . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('approvals.index')
                        ->with('success', 'Booking approved successfully.');
    }

    /**
     * Reject booking
     */
    public function reject(Request $request, Approval $approval)
    {
        if (auth()->id() !== $approval->approver_id) {
            return back()->with('error', 'Unauthorized.');
        }

        if ($approval->status !== 'pending') {
            return back()->with('error', 'This approval has already been processed.');
        }

        $validated = $request->validate([
            'comments' => 'required|string|max:500',
        ]);

        $booking = $approval->booking;

        $approval->update([
            'status' => 'rejected',
            'comments' => $validated['comments'],
            'approved_at' => now(),
        ]);

        // Reject booking
        $booking->update(['status' => 'rejected']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reject',
            'module' => 'approval',
            'description' => 'Rejected booking ' . $booking->booking_number . ' (Level ' . $approval->level . ')',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('approvals.index')
                        ->with('success', 'Booking rejected.');
    }
}
