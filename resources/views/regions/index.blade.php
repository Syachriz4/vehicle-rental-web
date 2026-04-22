@extends('layouts.app')

@section('title', 'Regions - Regional Management')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Regions</h2>
        <p class="text-lg text-on-surface-variant mt-1">Geographic Regions</p>
    </div>
    <a href="{{ route('regions.create') }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        + Add Region
    </a>
</div>

<!-- Regions Table -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    @if($regions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Name</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Code</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Location</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regions as $region)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-4 text-sm font-semibold">{{ $region->name }}</td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">{{ $region->code }}</span>
                            </td>
                            <td class="px-8 py-4 text-sm">{{ $region->location }}</td>
                            <td class="px-8 py-4 text-sm flex gap-3">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('regions.edit', $region->id) }}" class="text-primary hover:opacity-70 font-semibold">Edit</a>
                                    <form action="{{ route('regions.destroy', $region->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this region?')">
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
    <div class="table-container">
        <div class="empty-state">
            <div class="empty-state-icon">🗺️</div>
            <div class="empty-state-title">No Regions Found</div>
            <div class="empty-state-text">Add a region to organize your operations</div>
            <a href="{{ route('regions.create') }}" class="btn btn-primary">Create Region</a>
        </div>
    </div>
@endif
@endsection
