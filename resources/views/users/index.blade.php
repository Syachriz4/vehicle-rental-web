@extends('layouts.app')

@section('title', 'Users - User Management')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary font-headline">Users</h2>
        <p class="text-lg text-on-surface-variant mt-1">System User Management</p>
    </div>
    <a href="{{ route('users.create') }}" class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all">
        + Add User
    </a>
</div>

<!-- Users Table -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_20px_50px_rgba(26,28,28,0.06)] overflow-hidden">
    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container">
                    <tr class="border-b border-surface-variant">
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Name</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Email</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Role</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Department</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Status</th>
                        <th class="px-8 py-4 text-left text-sm font-semibold text-on-surface">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-b border-surface-variant hover:bg-surface-container-low transition-colors">
                            <td class="px-8 py-4 text-sm font-semibold">{{ $user->name }}</td>
                            <td class="px-8 py-4 text-sm">{{ $user->email }}</td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="px-8 py-4 text-sm">{{ $user->department->name ?? '-' }}</td>
                            <td class="px-8 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-sm flex gap-3">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-primary hover:opacity-70 font-semibold">Edit</a>
                                    @if($user->id !== auth()->user()->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-error hover:opacity-70 font-semibold bg-none border-none cursor-pointer">Delete</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-8 py-16 text-center">
            <p class="text-2xl mb-2">👥</p>
            <p class="text-lg font-semibold text-primary mb-1">No Users Yet</p>
            <p class="text-on-surface-variant mb-6">Create your first user to get started</p>
            <a href="{{ route('users.create') }}" class="bg-primary text-on-primary py-2 px-4 text-sm font-semibold hover:opacity-90 transition-all">
                Create User
            </a>
        </div>
    @endif
</div>
@endsection
