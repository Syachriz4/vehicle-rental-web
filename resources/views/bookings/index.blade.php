@extends('layouts.app')

@section('title', 'Bookings')
@section('page-title', 'Booking Management')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Bookings</h2>
        <p class="text-lg text-on-surface-variant mt-1">Vehicle Reservation System</p>
    </div>
    <a href="{{ route('bookings.create') }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        + New Booking
    </a>
</div>

<!-- Bookings Table -->
@if($bookings->count() > 0)
    <div class="bg-surface-container-lowest rounded-2xl shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden border border-surface-bright">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container sticky top-0">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Booking #</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Vehicle</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Driver</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Period</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-on-surface tracking-widest uppercase">Status</th>
                        <th class="px-8 py-5 text-center text-xs font-bold text-on-surface tracking-widest uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low/50 transition-all duration-200 group">
                            <!-- Booking Number -->
                            <td class="px-8 py-5 text-sm font-semibold text-primary">
                                <span class="bg-primary/10 px-3 py-1.5 rounded-lg font-mono text-xs">{{ $booking->booking_number }}</span>
                            </td>
                            
                            <!-- Vehicle -->
                            <td class="px-8 py-5 text-sm text-on-surface font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg text-primary">directions_car</span>
                                    {{ $booking->vehicle->vehicle_name ?? 'N/A' }}
                                </div>
                            </td>
                            
                            <!-- Driver/User -->
                            <td class="px-8 py-5 text-sm text-on-surface">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center text-xs font-bold text-primary">
                                        {{ strtoupper(substr($booking->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    {{ $booking->user->name ?? 'N/A' }}
                                </div>
                            </td>
                            
                            <!-- Period -->
                            <td class="px-8 py-5 text-sm text-on-surface-variant">
                                <div class="flex flex-col">
                                    <span class="text-xs">{{ $booking->start_date->format('d M Y') }}</span>
                                    <span class="text-xs text-on-surface-variant">→ {{ $booking->end_date->format('d M Y') }}</span>
                                </div>
                            </td>
                            
                            <!-- Status Badge -->
                            <td class="px-8 py-5 text-sm">
                                @if($booking->status === 'pending')
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">schedule</span>
                                        Pending
                                    </span>
                                @elseif($booking->status === 'approved')
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        Approved
                                    </span>
                                @elseif($booking->status === 'completed')
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">done_all</span>
                                        Completed
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 flex items-center gap-1.5 w-fit">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-8 py-5 text-sm">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="inline-flex items-center gap-1.5 text-primary hover:bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                        <span class="text-xs font-semibold">View</span>
                                    </a>
                                    
                                    @if($booking->status === 'pending' && (auth()->id() === $booking->user_id || auth()->user()->role === 'admin'))
                                        <a href="{{ route('bookings.edit', $booking->id) }}" class="inline-flex items-center gap-1.5 text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                            <span class="text-xs font-semibold">Edit</span>
                                        </a>
                                        
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking?')">
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
                <span class="material-symbols-outlined text-6xl text-primary">bookmark_outline</span>
            </div>
            
            <!-- Empty State Title -->
            <h3 class="text-2xl font-headline font-bold text-on-surface mb-2 text-center">No Bookings Yet</h3>
            
            <!-- Empty State Description -->
            <p class="text-center text-on-surface-variant mb-8 text-sm leading-relaxed">
                You haven't created any vehicle bookings yet. Start by creating your first booking to manage vehicle reservations.
            </p>
            
            <!-- CTA Button -->
            <a href="{{ route('bookings.create') }}" class="bg-primary text-on-primary py-3 px-8 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-full inline-flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined">add</span>
                Create First Booking
            </a>
            
            <!-- Secondary Link -->
            <a href="{{ route('vehicles.index') }}" class="text-primary hover:underline text-sm font-semibold">
                View available vehicles →
            </a>
        </div>
    </div>
@endif
@endsection
