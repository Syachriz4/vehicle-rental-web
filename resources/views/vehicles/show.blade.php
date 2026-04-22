@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Vehicle Details</h2>
        <p class="text-lg text-on-surface-variant">{{ $vehicle->plate_number }}</p>
    </div>
    <a href="{{ route('vehicles.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Back
    </a>
</div>

<div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg space-y-8">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Vehicle Name</p>
            <p class="text-lg font-bold text-on-surface">{{ $vehicle->vehicle_name }}</p>
        </div>
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Plate Number</p>
            <p class="text-lg font-bold text-on-surface">{{ $vehicle->plate_number }}</p>
        </div>
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Vehicle Type</p>
            <p class="text-base font-semibold text-on-surface">{{ $vehicle->vehicle_type }}</p>
        </div>
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Year</p>
            <p class="text-base font-semibold text-on-surface">{{ $vehicle->year }}</p>
        </div>
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Status</p>
            <div class="inline-flex">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/20 text-primary">
                    {{ ucfirst($vehicle->status) }}
                </span>
            </div>
        </div>
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Total Bookings</p>
            <p class="text-base font-semibold text-on-surface">{{ $vehicle->bookings()->count() }}</p>
        </div>
    </div>

    <div class="flex gap-3 justify-end pt-6 border-t border-surface-variant">
        <a href="{{ route('vehicles.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface hover:bg-surface-container rounded-lg transition-colors inline-flex items-center gap-2">
            <span class="material-symbols-outlined">close</span>
            Back
        </a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="bg-blue-600 text-white py-3 px-8 font-headline font-bold text-sm tracking-wider uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg inline-flex items-center gap-2">
                <span class="material-symbols-outlined">edit</span>
                Edit
            </a>
        @endif
    </div>
</div>
@endsection
