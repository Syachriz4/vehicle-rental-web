@extends('layouts.app')

@section('title', 'Booking Reports')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid #e2e2e2;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #666;
        font-weight: 500;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1c1c;
    }

    .stat-value small {
        font-size: 0.75rem;
        color: #999;
        font-weight: 500;
    }

    .card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e2e2;
    }

    .card-title {
        margin: 0;
        color: #1a1c1c;
        font-weight: 600;
    }

    .card-body {
        padding: 1.5rem;
    }

    .form-control-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }

    .btn {
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        cursor: pointer;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-primary {
        background-color: #0066cc;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0052a3;
    }

    .btn-secondary {
        background-color: #999;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #777;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
        padding: 0.625rem 1.25rem;
        font-size: 1rem;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 0.75rem;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -0.5rem;
        margin-left: -0.5rem;
    }

    .g-3 > * {
        padding-right: 0.5rem;
        padding-left: 0.5rem;
    }

    .col-md-2 {
        flex: 0 0 16.66666667%;
        max-width: 16.66666667%;
    }

    .d-flex {
        display: flex;
    }

    .align-items-end {
        align-items: flex-end;
    }

    .w-100 {
        width: 100%;
    }

    .table-container {
        background: white;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background-color: #f3f3f3;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #1a1c1c;
        border-bottom: 1px solid #e2e2e2;
        font-size: 0.875rem;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.875rem;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    .badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-pending {
        background-color: #fff3e0;
        color: #ff9800;
    }

    .badge-approved {
        background-color: #e8f5e9;
        color: #28a745;
    }

    .badge-rejected {
        background-color: #ffebee;
        color: #dc3545;
    }

    .badge-completed {
        background-color: #f3e5f5;
        color: #9c27b0;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Booking Reports
        <small>Booking Analytics & Statistics</small>
    </h1>
</div>

<!-- Statistics Summary -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #e7f3ff;">
            <i class="fas fa-list" style="color: #0066cc;"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value">{{ $totalBookings }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: #e8f5e9;">
            <i class="fas fa-check-circle" style="color: #28a745;"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Approved</div>
            <div class="stat-value">{{ $approvedCount }} <small>({{ $approvalPercentage }}%)</small></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: #fff3e0;">
            <i class="fas fa-clock" style="color: #ff9800;"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $pendingCount }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: #ffebee;">
            <i class="fas fa-times-circle" style="color: #dc3545;"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Rejected</div>
            <div class="stat-value">{{ $rejectedCount }} <small>({{ $rejectionPercentage }}%)</small></div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: #f3e5f5;">
            <i class="fas fa-flag-checkered" style="color: #9c27b0;"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Completed</div>
            <div class="stat-value">{{ $completedCount }}</div>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Filter & Export</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-control form-control-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Vehicle</label>
                <select name="vehicle_id" class="form-control form-control-sm">
                    <option value="">All Vehicles</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->plate_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">Approver</label>
                <select name="approver_id" class="form-control form-control-sm">
                    <option value="">All Approvers</option>
                    @foreach($approvers as $approver)
                        <option value="{{ $approver->id }}" {{ request('approver_id') == $approver->id ? 'selected' : '' }}>
                            {{ $approver->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('booking-reports.index') }}" class="btn btn-secondary btn-sm w-100">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Export Button -->
<div class="mb-3 d-flex gap-2">
    <form method="GET" action="{{ route('booking-reports.export') }}" style="display: inline;">
        @foreach(request()->query() as $key => $value)
            @if($value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        <button type="submit" class="btn btn-success">
            <i class="fas fa-download"></i> Export to CSV
        </button>
    </form>
</div>

@if($bookings->count() > 0)
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking #</th>
                    <th>Vehicle</th>
                    <th>User</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Level 1</th>
                    <th>Level 2</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->booking_number }}</strong></td>
                        <td>{{ $booking->vehicle->name ?? 'N/A' }}</td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->start_date->format('d M') }} - {{ $booking->end_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $approval1 = $booking->approvals()->where('level', 1)->first();
                            @endphp
                            @if($approval1)
                                <span class="badge badge-{{ $approval1->status }}">{{ ucfirst($approval1->status) }}</span>
                            @else
                                <span style="color:#999;">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $approval2 = $booking->approvals()->where('level', 2)->first();
                            @endphp
                            @if($approval2)
                                <span class="badge badge-{{ $approval2->status }}">{{ ucfirst($approval2->status) }}</span>
                            @else
                                <span style="color:#999;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="table-container">
        <div class="empty-state">
            <div class="empty-state-icon">📊</div>
            <div class="empty-state-title">No Booking Reports</div>
            <div class="empty-state-text">No booking data available yet</div>
        </div>
    </div>
@endif
@endsection
