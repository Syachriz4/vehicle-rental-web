@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Edit User</h2>
    <p class="text-lg text-on-surface-variant">{{ $user->email }}</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="name">
                    Name
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('name') border-2 border-red-500 @enderror" 
                    id="name" 
                    name="name" 
                    value="{{ $user->name }}"
                    required 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="email">
                    Email
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('email') border-2 border-red-500 @enderror" 
                    id="email" 
                    name="email" 
                    value="{{ $user->email }}"
                    required 
                    type="email"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="role">
                    Role
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10" 
                        id="role" 
                        name="role"
                        required>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="approver" {{ $user->role === 'approver' ? 'selected' : '' }}>Approver</option>
                        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
            </div>

            <!-- Department Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="department_id">
                    Department
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10" 
                        id="department_id" 
                        name="department_id">
                        <option value="">Select department</option>
                        @foreach(\App\Models\Department::all() as $dept)
                            <option value="{{ $dept->id }}" {{ $dept->id === $user->department_id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
            </div>

            <!-- Supervisor Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="supervisor_id">
                    Supervisor
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10" 
                        id="supervisor_id" 
                        name="supervisor_id">
                        <option value="">Select supervisor</option>
                        @foreach(\App\Models\User::where('id', '!=', $user->id)->get() as $u)
                            <option value="{{ $u->id }}" {{ $u->id === $user->supervisor_id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
            </div>

            <!-- Status Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="is_active">
                    Status
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10" 
                        id="is_active" 
                        name="is_active">
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
            </div>

            <!-- Primary Action -->
            <div class="flex gap-3 justify-end pt-4">
                <a href="{{ route('users.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors border border-outline rounded-lg">
                    Cancel
                </a>
                <button 
                    class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg" 
                    type="submit">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
