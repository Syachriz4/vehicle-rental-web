@extends('layouts.app')

@section('title', 'Vehicles')
@section('page-title', 'Fleet Inventory')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Vehicles</h2>
        <p class="text-lg text-on-surface-variant mt-1">Fleet Management</p>
    </div>
    <a href="{{ route('vehicles.create') }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        + Add Vehicle
    </a>
</div>

<!-- Vehicles Table -->
@if($vehicles->count() > 0)
    <div class="bg-surface-container-lowest rounded-2xl shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden border border-surface-bright">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container sticky top-0">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Vehicle</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Plate</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Type</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Year</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Status</th>
                        <th class="px-8 py-5 text-center text-xs font-bold text-on-surface tracking-widest uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $vehicle)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low/50 transition-all duration-200 group">
                            <!-- Vehicle Name -->
                            <td class="px-8 py-5 text-sm font-semibold text-on-surface">
                                <div class="flex items-center gap-3">
                                    <div class="bg-primary/10 rounded-lg p-2.5">
                                        <span class="material-symbols-outlined text-lg text-primary">directions_car</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-on-surface">{{ $vehicle->vehicle_name }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Plate Number -->
                            <td class="px-8 py-5 text-sm">
                                <span class="bg-surface-container px-3 py-1.5 rounded-lg font-mono text-xs font-bold text-primary">{{ $vehicle->plate_number }}</span>
                            </td>
                            
                            <!-- Vehicle Type -->
                            <td class="px-8 py-5 text-sm text-on-surface">
                                <span class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold capitalize">{{ $vehicle->vehicle_type }}</span>
                            </td>
                            
                            <!-- Year -->
                            <td class="px-8 py-5 text-sm text-on-surface-variant font-medium">
                                {{ $vehicle->year }}
                            </td>
                            
                            <!-- Status Badge -->
                            <td class="px-8 py-5 text-sm">
                                @if($vehicle->status === 'available')
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        Available
                                    </span>
                                @elseif($vehicle->status === 'in_use')
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        In Use
                                    </span>
                                @elseif($vehicle->status === 'maintenance')
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">build</span>
                                        Maintenance
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                        {{ ucfirst(str_replace('_', ' ', $vehicle->status)) }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-8 py-5 text-sm">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('vehicles.show', $vehicle->id) }}" class="inline-flex items-center gap-1.5 text-primary hover:bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                        <span class="text-xs font-semibold">View</span>
                                    </a>
                                    
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="inline-flex items-center gap-1.5 text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                            <span class="text-xs font-semibold">Edit</span>
                                        </a>
                                        
                                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 text-error hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors font-semibold">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                                <span class="text-xs">Delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-24 px-8">
        <div class="bg-surface-container rounded-3xl p-12 flex flex-col items-center justify-center w-full max-w-md">
            <!-- Empty State Icon -->
            <div class="bg-primary/10 rounded-full p-6 mb-6">
                <span class="material-symbols-outlined text-6xl text-primary">directions_car</span>
            </div>
            
            <!-- Empty State Title -->
            <h3 class="text-2xl font-headline font-bold text-on-surface mb-2 text-center">No Vehicles Yet</h3>
            
            <!-- Empty State Description -->
            <p class="text-center text-on-surface-variant mb-8 text-sm leading-relaxed">
                Start building your fleet by adding your first vehicle. Manage all vehicles in one place.
            </p>
            
            <!-- CTA Button -->
            <a href="{{ route('vehicles.create') }}" class="bg-primary text-on-primary py-3 px-8 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-full inline-flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined">add</span>
                Add First Vehicle
            </a>
            
            <!-- Secondary Link -->
            <a href="{{ route('bookings.index') }}" class="text-primary hover:underline text-sm font-semibold">
                View bookings →
            </a>
        </div>
    </div>
@endif
@endsection
