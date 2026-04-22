@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', $isUserDashboard ? 'My Bookings' : 'Fleet Overview')

@section('content')

@if($isUserDashboard)
    <!-- USER DASHBOARD -->
    <!-- Summary Grid for User -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-px bg-surface-container-high border border-surface-container-high mb-12">
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">event_note</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Total Bookings</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $myTotalBookings }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">pending_actions</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Pending</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $myPendingBookings }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">thumb_up</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Approved</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $myApprovedBookings }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">task_alt</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Completed</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $myCompletedBookings }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">directions_car</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">My Vehicles</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $myVehicles }}</p>
            </div>
        </div>
    </section>

    <!-- Charts Section for User -->
    <section class="grid grid-cols-12 gap-8 mb-12">
        <!-- My Weekly Bookings Chart -->
        <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest p-8 border border-surface-container-high rounded-lg">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h3 class="text-xs uppercase tracking-[0.2em] font-bold text-on-surface-variant mb-1">My Bookings</h3>
                    <p class="font-headline text-2xl font-bold">Weekly Activity</p>
                </div>
            </div>
            <canvas id="usageChart" height="80"></canvas>
        </div>

        <!-- My Booking Status Pie Chart -->
        <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest p-8 border border-surface-container-high rounded-lg flex flex-col">
            <h3 class="text-xs uppercase tracking-[0.2em] font-bold text-on-surface-variant mb-1">Status Overview</h3>
            <p class="font-headline text-2xl font-bold mb-8">My Bookings</p>
            <div class="flex-1 flex flex-col justify-center items-center">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </section>

@else
    <!-- ADMIN/APPROVER DASHBOARD -->
    <!-- Summary Grid -->
    <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-px bg-surface-container-high border border-surface-container-high mb-12">
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">directions_car</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Total Vehicles</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $totalVehicles }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">ev_station</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Vehicles In Use</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $vehiclesInUse }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">check_circle</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Available</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $vehiclesAvailable }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">event_note</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Total Bookings</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $totalBookings }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">pending_actions</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Pending</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $pendingBookings }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-8 flex flex-col items-start gap-4">
            <span class="material-symbols-outlined text-on-surface-variant">task_alt</span>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Completed</p>
                <p class="text-3xl font-headline font-extrabold text-primary">{{ $completedBookings }}</p>
            </div>
        </div>
    </section>

    <!-- Charts Section -->
    <section class="grid grid-cols-12 gap-8 mb-12">
        <!-- Vehicle Usage Chart -->
        <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest p-8 border border-surface-container-high rounded-lg">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h3 class="text-xs uppercase tracking-[0.2em] font-bold text-on-surface-variant mb-1">Vehicle Usage</h3>
                    <p class="font-headline text-2xl font-bold">Weekly Performance</p>
                </div>
            </div>
            <canvas id="usageChart" height="80"></canvas>
        </div>

        <!-- Booking Status Pie Chart -->
        <div class="col-span-12 lg:col-span-4 bg-surface-container-lowest p-8 border border-surface-container-high rounded-lg flex flex-col">
            <h3 class="text-xs uppercase tracking-[0.2em] font-bold text-on-surface-variant mb-1">Status Allocation</h3>
            <p class="font-headline text-2xl font-bold mb-8">Booking Mix</p>
            <div class="flex-1 flex flex-col justify-center items-center">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>

        <!-- Fuel Consumption Line Chart -->
        <div class="col-span-12 bg-surface-container-lowest p-8 border border-surface-container-high rounded-lg">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h3 class="text-xs uppercase tracking-[0.2em] font-bold text-on-surface-variant mb-1">Fuel & Energy</h3>
                    <p class="font-headline text-2xl font-bold">Monthly Consumption</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-headline font-black">{{ $averageFuelConsumption }}<span class="text-sm font-normal text-on-surface-variant ml-1">L/month</span></p>
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase">Fleet Average</p>
                </div>
            </div>
            <canvas id="fuelChart" height="80"></canvas>
        </div>
    </section>

@endif

<!-- Recent Activity Table -->
<section class="bg-surface-container-lowest border border-surface-container-high rounded-lg overflow-hidden">
    <div class="p-8 border-b border-surface-container-high flex justify-between items-center">
        <div>
            <h3 class="text-xs uppercase tracking-[0.2em] font-bold text-on-surface-variant mb-1">{{ $isUserDashboard ? 'My Bookings' : 'Live Logs' }}</h3>
            <p class="font-headline text-2xl font-bold">{{ $isUserDashboard ? 'Recent Activity' : 'Recent Activity' }}</p>
        </div>
        <a href="{{ route($isUserDashboard ? 'bookings.index' : 'activity-logs.index') }}" class="px-6 py-2 bg-primary text-on-primary text-xs font-bold uppercase tracking-widest rounded-lg hover:opacity-90">
            View All
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="px-8 py-4 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Booking #</th>
                    <th class="px-8 py-4 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Vehicle</th>
                    @if(!$isUserDashboard)
                    <th class="px-8 py-4 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">User</th>
                    @endif
                    <th class="px-8 py-4 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Status</th>
                    <th class="px-8 py-4 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Date</th>
                    <th class="px-8 py-4 text-[10px] uppercase tracking-widest font-bold text-on-surface-variant">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-high">
                @forelse($isUserDashboard ? $myRecentBookings : $recentBookings as $booking)
                <tr class="hover:bg-surface-container-low/50 transition-colors">
                    <td class="px-8 py-5 font-headline font-bold text-sm">{{ $booking->booking_number }}</td>
                    <td class="px-8 py-5 text-sm font-medium">{{ $booking->vehicle->vehicle_name ?? 'N/A' }}</td>
                    @if(!$isUserDashboard)
                    <td class="px-8 py-5 text-sm">{{ $booking->user->name ?? 'N/A' }}</td>
                    @endif
                    <td class="px-8 py-5">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary text-on-primary text-[9px] font-black uppercase tracking-tighter rounded">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-sm text-on-surface-variant font-mono">{{ $booking->start_date->format('d M Y H:i') }}</td>
                    <td class="px-8 py-5 text-right">
                        <a href="{{ route('bookings.show', $booking->id) }}" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">
                            arrow_outward
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $isUserDashboard ? 5 : 6 }}" class="px-8 py-12 text-center text-on-surface-variant">No recent activity</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<!-- Chart.js Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vehicle Usage Chart
    const usageCtx = document.getElementById('usageChart').getContext('2d');
    @if($isUserDashboard)
        const usageData = {{ json_encode($weeklyBookings) }};
    @else
        const usageData = {{ json_encode($weeklyUsage) }};
    @endif
    
    new Chart(usageCtx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: @if($isUserDashboard) 'My Bookings' @else 'Vehicles in Use' @endif,
                data: usageData,
                backgroundColor: '#000000',
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...usageData) * 1.3
                }
            }
        }
    });

    // Booking Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    @if($isUserDashboard)
        const statusData = {{ json_encode($myBookingStatusData) }};
    @else
        const statusData = {{ json_encode($bookingStatusData) }};
    @endif
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Completed', 'Rejected'],
            datasets: [{
                data: statusData,
                backgroundColor: ['#000000', '#e2e2e2', '#c6c6c6', '#ba1a1a'],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 16, font: { size: 12 } }
                }
            }
        }
    });

    @if(!$isUserDashboard)
    // Fuel Consumption Chart (Admin only)
    const fuelCtx = document.getElementById('fuelChart').getContext('2d');
    new Chart(fuelCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Fuel Consumption (Liters)',
                data: {{ json_encode($monthlyFuelData) }},
                borderColor: '#000000',
                backgroundColor: 'rgba(0, 0, 0, 0.05)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#000000',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    @endif
});
</script>
@endsection
