<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\FuelConsumption;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $isApprover = $user->role === 'approver';

        if ($isAdmin || $isApprover) {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard()
    {
        // Summary stats
        $totalVehicles = Vehicle::count();
        $vehiclesInUse = Booking::where('status', '!=', 'rejected')
            ->where('status', '!=', 'completed')
            ->distinct('vehicle_id')
            ->count('vehicle_id');
        $vehiclesAvailable = $totalVehicles - $vehiclesInUse;
        
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        // Average fuel consumption
        $averageFuelConsumption = FuelConsumption::avg('amount') ?? 0;
        $averageFuelConsumption = number_format($averageFuelConsumption, 1);

        // Weekly vehicle usage data (last 7 days)
        $weeklyUsage = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Booking::whereDate('start_date', $date)->count();
            $weeklyUsage[] = $count;
        }

        // Booking status data
        $bookingStatusData = [
            Booking::where('status', 'pending')->count(),
            Booking::where('status', 'approved')->count(),
            Booking::where('status', 'completed')->count(),
            Booking::where('status', 'rejected')->count(),
        ];

        // Monthly fuel consumption data (last 4 weeks)
        $monthlyFuelData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startDate = now()->subWeeks($i)->startOfWeek();
            $endDate = now()->subWeeks($i)->endOfWeek();
            $consumption = FuelConsumption::whereBetween('fuel_date', [$startDate, $endDate])->sum('amount');
            $monthlyFuelData[] = round($consumption, 2);
        }

        // Recent bookings
        $recentBookings = Booking::with(['vehicle', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', [
            'isUserDashboard' => false,
            'totalVehicles' => $totalVehicles,
            'vehiclesInUse' => $vehiclesInUse,
            'vehiclesAvailable' => $vehiclesAvailable,
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'completedBookings' => $completedBookings,
            'averageFuelConsumption' => $averageFuelConsumption,
            'weeklyUsage' => $weeklyUsage,
            'bookingStatusData' => $bookingStatusData,
            'monthlyFuelData' => $monthlyFuelData,
            'recentBookings' => $recentBookings,
        ]);
    }

    private function userDashboard()
    {
        $userId = auth()->id();

        // User's booking stats
        $myTotalBookings = Booking::where('user_id', $userId)->count();
        $myPendingBookings = Booking::where('user_id', $userId)->where('status', 'pending')->count();
        $myApprovedBookings = Booking::where('user_id', $userId)->where('status', 'approved')->count();
        $myCompletedBookings = Booking::where('user_id', $userId)->where('status', 'completed')->count();
        $myRejectedBookings = Booking::where('user_id', $userId)->where('status', 'rejected')->count();

        // My vehicles (unique vehicles I've booked)
        $myVehicles = Vehicle::whereIn('id', 
            Booking::where('user_id', $userId)->pluck('vehicle_id')
        )->count();

        // Weekly booking trend (my bookings, last 7 days)
        $weeklyBookings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Booking::where('user_id', $userId)->whereDate('start_date', $date)->count();
            $weeklyBookings[] = $count;
        }

        // My booking status breakdown
        $myBookingStatusData = [
            Booking::where('user_id', $userId)->where('status', 'pending')->count(),
            Booking::where('user_id', $userId)->where('status', 'approved')->count(),
            Booking::where('user_id', $userId)->where('status', 'completed')->count(),
            Booking::where('user_id', $userId)->where('status', 'rejected')->count(),
        ];

        // My recent bookings
        $myRecentBookings = Booking::with(['vehicle', 'user'])
            ->where('user_id', $userId)
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', [
            'isUserDashboard' => true,
            'myTotalBookings' => $myTotalBookings,
            'myPendingBookings' => $myPendingBookings,
            'myApprovedBookings' => $myApprovedBookings,
            'myCompletedBookings' => $myCompletedBookings,
            'myRejectedBookings' => $myRejectedBookings,
            'myVehicles' => $myVehicles,
            'weeklyBookings' => $weeklyBookings,
            'myBookingStatusData' => $myBookingStatusData,
            'myRecentBookings' => $myRecentBookings,
        ]);
    }
}
