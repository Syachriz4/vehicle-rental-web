@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Booking Details</h2>
        <p class="text-lg text-on-surface-variant">{{ $booking->booking_number }}</p>
    </div>
</div>

<div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg space-y-12">
    
    <!-- Basic Info Section -->
    <div>
        <h3 class="text-lg font-bold text-on-surface-variant mb-6 pb-3 border-b-2 border-outline-variant">Booking Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Booking Number</p>
                <p class="text-base font-semibold text-on-surface">{{ $booking->booking_number }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Status</p>
                <div class="inline-flex">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/20 text-primary">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Vehicle</p>
                <p class="text-base font-semibold text-on-surface">{{ $booking->vehicle->vehicle_name ?? 'N/A' }}</p>
                <p class="text-sm text-on-surface-variant">{{ $booking->vehicle->plate_number ?? 'N/A' }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Booker</p>
                <p class="text-base font-semibold text-on-surface">{{ $booking->user->name ?? 'N/A' }}</p>
                <p class="text-sm text-on-surface-variant">{{ $booking->user->email ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Dates & Purpose Section -->
    <div>
        <h3 class="text-lg font-bold text-on-surface-variant mb-6 pb-3 border-b-2 border-outline-variant">Reservation Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Start Date</p>
                <p class="text-base font-semibold text-on-surface">{{ $booking->start_date->format('d M Y H:i') }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">End Date</p>
                <p class="text-base font-semibold text-on-surface">{{ $booking->end_date->format('d M Y H:i') }}</p>
            </div>
            <div class="md:col-span-2 space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Purpose</p>
                <p class="text-base text-on-surface">{{ $booking->purpose }}</p>
            </div>
        </div>
    </div>

    <!-- Driver Info Section -->
    <div>
        <h3 class="text-lg font-bold text-on-surface-variant mb-6 pb-3 border-b-2 border-outline-variant">Driver Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Driver Name</p>
                <p class="text-base font-semibold text-on-surface">{{ $booking->driver->name ?? 'N/A' }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Start KM</p>
                <p class="text-base font-semibold text-on-surface">{{ number_format($booking->start_km ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Approvals Status Section -->
    <div>
        <h3 class="text-lg font-bold text-on-surface-variant mb-6 pb-3 border-b-2 border-outline-variant">Approval Status</h3>
        @php
            $approval1 = $booking->approvals()->where('level', 1)->first();
            $approval2 = $booking->approvals()->where('level', 2)->first();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Level 1 -->
            <div class="border border-outline-variant rounded-lg p-6 bg-surface-container">
                <p class="font-bold text-on-surface mb-4">Level 1 - Supervisor</p>
                @if($approval1)
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-outline uppercase tracking-wider">Approver</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $approval1->approver->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/20 text-primary">
                                {{ ucfirst($approval1->status) }}
                            </span>
                        </div>
                        @if($approval1->comments)
                            <div>
                                <p class="text-xs text-outline uppercase tracking-wider">Comments</p>
                                <p class="text-sm text-on-surface-variant">{{ $approval1->comments }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-outline-variant">No approval created</p>
                @endif
            </div>

            <!-- Level 2 -->
            <div class="border border-outline-variant rounded-lg p-6 bg-surface-container">
                <p class="font-bold text-on-surface mb-4">Level 2 - Manager</p>
                @if($approval2)
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-outline uppercase tracking-wider">Approver</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $approval2->approver->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/20 text-primary">
                                {{ ucfirst($approval2->status) }}
                            </span>
                        </div>
                        @if($approval2->comments)
                            <div>
                                <p class="text-xs text-outline uppercase tracking-wider">Comments</p>
                                <p class="text-sm text-on-surface-variant">{{ $approval2->comments }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-outline-variant">No approval created</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-3 justify-end pt-6 border-t border-surface-variant">
        <a href="{{ route('bookings.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface hover:bg-surface-container rounded-lg transition-colors inline-flex items-center gap-2">
            <span class="material-symbols-outlined">arrow_back</span>
            Back
        </a>
        @if($booking->status === 'pending')
            <a href="{{ route('bookings.edit', $booking->id) }}" class="bg-blue-600 text-white py-3 px-8 font-headline font-bold text-sm tracking-wider uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg inline-flex items-center gap-2">
                <span class="material-symbols-outlined">edit</span>
                Edit Booking
            </a>
        @endif
    </div>
@endsection
