@extends('layouts.app')

@section('title', 'Departments - Department Management')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Departments</h2>
        <p class="text-lg text-on-surface-variant mt-1">Organizational Departments</p>
    </div>
    <a href="{{ route('departments.create') }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        + Add Department
    </a>
</div>

<!-- Departments Table -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    @if($departments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Name</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Location</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Head</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-4 text-sm font-semibold">{{ $department->name }}</td>
                            <td class="px-8 py-4 text-sm">{{ $department->location }}</td>
                            <td class="px-8 py-4 text-sm">{{ $department->head_name ?? '-' }}</td>
                            <td class="px-8 py-4 text-sm flex gap-3">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('departments.edit', $department->id) }}" class="text-primary hover:opacity-70 font-semibold">Edit</a>
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this department?')">
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
            <p class="text-2xl mb-2">🏢</p>
            <p class="text-lg font-semibold text-primary mb-1">No Departments</p>
            <p class="text-on-surface-variant mb-6">Add a department to organize your structure</p>
            <a href="{{ route('departments.create') }}" class="bg-primary text-on-primary py-2 px-4 text-sm font-semibold hover:opacity-90 transition-all">
                Create Department
            </a>
        </div>
    @endif
</div>
@endsection
