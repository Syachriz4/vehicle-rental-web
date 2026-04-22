@extends('layouts.app')

@section('title', 'Service Schedules - Maintenance')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Service Schedules</h2>
        <p class="text-lg text-on-surface-variant mt-1">Vehicle Maintenance Planning</p>
    </div>
    <a href="{{ route('service-schedules.create') }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        + Add Schedule
    </a>
</div>

<!-- Service Schedules Table -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    @if($schedules->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Vehicle</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Service Type</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Schedule Date</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Status</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Notes</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-4 text-sm font-semibold">{{ $schedule->vehicle->vehicle_name ?? 'N/A' }}</td>
                            <td class="px-8 py-4 text-sm">{{ $schedule->service_type }}</td>
                            <td class="px-8 py-4 text-sm">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $schedule->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : ($schedule->status === 'completed' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-sm">{{ $schedule->notes ? substr($schedule->notes, 0, 50) . (strlen($schedule->notes) > 50 ? '...' : '') : '-' }}</td>
                            <td class="px-8 py-4 text-sm flex gap-3">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('service-schedules.edit', $schedule->id) }}" class="text-primary hover:opacity-70 font-semibold">Edit</a>
                                    <form action="{{ route('service-schedules.destroy', $schedule->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this schedule?')">
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
            <p class="text-2xl mb-2">🔧</p>
            <p class="text-lg font-semibold text-primary mb-1">No Service Schedules</p>
            <p class="text-on-surface-variant mb-6">Start by adding a maintenance schedule</p>
            <a href="{{ route('service-schedules.create') }}" class="bg-primary text-on-primary py-2 px-4 text-sm font-semibold hover:opacity-90 transition-all">
                Create Schedule
            </a>
        </div>
    @endif
</div>
@endsection
