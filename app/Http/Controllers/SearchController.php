<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Approval;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function global(Request $request)
    {
        $query = $request->input('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([
                'bookings' => [],
                'vehicles' => [],
                'users' => [],
            ]);
        }

        // Search Bookings
        $bookings = Booking::with('user', 'vehicle')
            ->where('booking_number', 'like', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->booking_number . ' - ' . ($booking->user->name ?? 'N/A'),
                    'type' => 'booking',
                    'url' => route('bookings.show', $booking->id),
                    'status' => $booking->status,
                ];
            });

        // Search Vehicles
        $vehicles = Vehicle::where('vehicle_name', 'like', "%{$query}%")
            ->orWhere('license_plate', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'title' => $vehicle->vehicle_name . ' (' . $vehicle->license_plate . ')',
                    'type' => 'vehicle',
                    'url' => route('vehicles.show', $vehicle->id),
                ];
            });

        // Search Users (admin only)
        $users = auth()->user()->role === 'admin' 
            ? User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(5)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'title' => $user->name . ' (' . $user->email . ')',
                        'type' => 'user',
                        'url' => route('users.show', $user->id),
                    ];
                })
            : [];

        return response()->json([
            'bookings' => $bookings,
            'vehicles' => $vehicles,
            'users' => $users,
        ]);
    }

    public function notifications()
    {
        $user = auth()->user();
        $notifications = [];

        // For approvers - pending approvals
        if ($user->role === 'admin' || $user->role === 'approver') {
            $pendingApprovals = Approval::where('approver_id', $user->id)
                ->where('status', 'pending')
                ->with('booking.user', 'booking.vehicle')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($pendingApprovals as $approval) {
                $notifications[] = [
                    'message' => 'Approval needed for ' . $approval->booking->booking_number,
                    'url' => route('approvals.show', $approval->id),
                    'time' => $approval->created_at->diffForHumans(),
                ];
            }
        }

        // For all users - their pending bookings
        $pendingBookings = Booking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($pendingBookings as $booking) {
            $notifications[] = [
                'message' => 'Booking ' . $booking->booking_number . ' awaiting approval',
                'url' => route('bookings.show', $booking->id),
                'time' => $booking->created_at->diffForHumans(),
            ];
        }

        return response()->json([
            'count' => count($notifications),
            'notifications' => $notifications,
        ]);
    }
}

