@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Activity Logs</h2>
        <p class="text-lg text-on-surface-variant mt-1">System Activity & User Actions</p>
    </div>
    <form action="{{ route('activity-logs.clear') }}" method="POST" class="inline" onsubmit="return confirm('Clear all logs? This cannot be undone!')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-error text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
            Clear All Logs
        </button>
    </form>
</div>

<!-- Activity Logs Table -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Timestamp</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">User</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Module</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Action</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-4 text-sm">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-8 py-4 text-sm font-semibold">{{ $log->user->name ?? 'System' }}</td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ $log->module }}</span>
                            </td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">{{ $log->action }}</span>
                            </td>
                            <td class="px-8 py-4 text-sm">{{ $log->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-8 py-16 text-center">
            <p class="text-2xl mb-2">📋</p>
            <p class="text-lg font-semibold text-primary mb-1">No Activity Logs</p>
            <p class="text-on-surface-variant">Activity logs will appear here</p>
        </div>
    @endif
</div>
@endsection
