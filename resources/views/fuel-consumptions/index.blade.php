@extends('layouts.app')

@section('title', 'Fuel Consumption - Fuel Tracking')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Fuel Consumption</h2>
        <p class="text-lg text-on-surface-variant mt-1">Fuel Usage Tracking</p>
    </div>
    <a href="{{ route('fuel-consumptions.create') }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        + Record Fuel
    </a>
</div>

<!-- Fuel Consumptions Table -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    @if($consumptions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Vehicle</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Date</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Amount (Liter)</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Price (Rp)</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Odometer (KM)</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consumptions as $consumption)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-4 text-sm font-semibold">{{ $consumption->vehicle->vehicle_name ?? 'N/A' }}</td>
                            <td class="px-8 py-4 text-sm">{{ $consumption->fuel_date->format('d M Y') }}</td>
                            <td class="px-8 py-4 text-sm">{{ number_format($consumption->amount, 2) }} L</td>
                            <td class="px-8 py-4 text-sm">Rp {{ number_format($consumption->price, 0, ',', '.') }}</td>
                            <td class="px-8 py-4 text-sm">{{ number_format($consumption->km_at_fuel, 0, ',', '.') }} KM</td>
                            <td class="px-8 py-4 text-sm flex gap-3">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('fuel-consumptions.edit', $consumption->id) }}" class="text-primary hover:opacity-70 font-semibold">Edit</a>
                                    <form action="{{ route('fuel-consumptions.destroy', $consumption->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error hover:opacity-70 font-semibold bg-none border-none cursor-pointer">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-8 py-16 text-center">
            <p class="text-2xl mb-2">⛽</p>
            <p class="text-lg font-semibold text-primary mb-1">No Fuel Records</p>
            <p class="text-on-surface-variant mb-6">Start tracking fuel consumption</p>
            <a href="{{ route('fuel-consumptions.create') }}" class="bg-primary text-on-primary py-2 px-4 text-sm font-semibold hover:opacity-90 transition-all">
                Record Fuel
            </a>
        </div>
    @endif
</div>
@endsection
