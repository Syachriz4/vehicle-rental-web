@extends('layouts.app')

@section('title', 'Approvals - Booking Approval')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Approvals</h2>
        <p class="text-lg text-on-surface-variant mt-1">Booking Approval Workflow</p>
    </div>
</div>

<!-- Approvals Table -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    @if($pendingApprovals->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Booking #</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Vehicle</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Level</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Status</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Approver</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Comments</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApprovals as $approval)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-4 text-sm font-semibold">{{ $approval->booking->booking_number }}</td>
                            <td class="px-8 py-4 text-sm">{{ $approval->booking->vehicle->vehicle_name ?? 'N/A' }}</td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">Level {{ $approval->level }}</span>
                            </td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $approval->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : ($approval->status === 'approved' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700') }}">
                                    {{ ucfirst($approval->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-sm">{{ $approval->approver->name ?? 'N/A' }}</td>
                            <td class="px-8 py-4 text-sm">{{ $approval->comments ? substr($approval->comments, 0, 50) . (strlen($approval->comments) > 50 ? '...' : '') : '-' }}</td>
                            <td class="px-8 py-4 text-sm">
                                @if($approval->status === 'pending')
                                    <a href="{{ route('approvals.show', $approval->id) }}" class="text-primary hover:opacity-70 font-semibold">Review</a>
                                @else
                                    <span class="text-on-surface-variant text-xs">Done</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-8 py-16 text-center">
            <p class="text-2xl mb-2">✅</p>
            <p class="text-lg font-semibold text-primary mb-1">No Pending Approvals</p>
            <p class="text-on-surface-variant mb-6">All bookings are up to date</p>
        </div>
    @endif
</div>
@endsection
