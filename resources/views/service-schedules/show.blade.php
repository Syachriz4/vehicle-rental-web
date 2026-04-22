@extends('layouts.app')

@section('title', 'Service Schedule Details')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Service Schedule Details</h2>
        <p class="text-lg text-on-surface-variant mt-1">{{ $serviceSchedule->service_type }}</p>
    </div>
    <a href="{{ route('service-schedules.edit', $serviceSchedule->id) }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        ✎ Edit
    </a>
</div>

<!-- Details Card -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    <div class="p-8 md:p-12">
        <!-- Vehicle Section -->
        <div class="mb-8 pb-8 border-b border-surface-variant">
            <p class="text-xs uppercase tracking-[0.15em] font-semibold text-outline mb-2">Vehicle</p>
            <p class="text-lg font-bold text-on-surface">{{ $serviceSchedule->vehicle->vehicle_name ?? 'N/A' }}</p>
            <p class="text-sm text-on-surface-variant">{{ $serviceSchedule->vehicle->plate_number ?? 'N/A' }}</p>
        </div>

        <!-- Service Type Section -->
        <div class="mb-8 pb-8 border-b border-surface-variant">
            <p class="text-xs uppercase tracking-[0.15em] font-semibold text-outline mb-2">Service Type</p>
            <p class="text-base font-semibold text-on-surface">{{ ucfirst($serviceSchedule->service_type) }}</p>
        </div>

        <!-- Scheduled Date Section -->
        <div class="mb-8 pb-8 border-b border-surface-variant">
            <p class="text-xs uppercase tracking-[0.15em] font-semibold text-outline mb-2">Scheduled Date</p>
            <p class="text-base font-semibold text-on-surface">{{ $serviceSchedule->scheduled_date->format('d F Y') }}</p>
        </div>

        <!-- Status Section -->
        <div class="mb-8 pb-8 border-b border-surface-variant">
            <p class="text-xs uppercase tracking-[0.15em] font-semibold text-outline mb-2">Status</p>
            <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ $serviceSchedule->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : ($serviceSchedule->status === 'completed' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                {{ ucfirst($serviceSchedule->status) }}
            </span>
        </div>

        <!-- Notes Section -->
        @if($serviceSchedule->notes)
            <div class="mb-8">
                <p class="text-xs uppercase tracking-[0.15em] font-semibold text-outline mb-2">Notes</p>
                <p class="text-base text-on-surface whitespace-pre-wrap">{{ $serviceSchedule->notes }}</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3 justify-end pt-8 border-t border-surface-variant">
            <a href="{{ route('service-schedules.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors border border-outline rounded-lg">
                Back
            </a>
            <form action="{{ route('service-schedules.destroy', $serviceSchedule->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this schedule?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-3 text-sm font-semibold text-error hover:text-red-700 transition-colors border border-error rounded-lg bg-none">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
