@extends('layouts.app')

@section('title', 'Create Department')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Create Department</h2>
    <p class="text-lg text-on-surface-variant">Add a new department</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('departments.store') }}" class="space-y-8">
            @csrf

            <!-- Name Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="name">
                    Department Name
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('name') border-2 border-red-500 @enderror" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    placeholder="e.g., Operations" 
                    required 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="location">
                    Location
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('location') border-2 border-red-500 @enderror" 
                    id="location" 
                    name="location" 
                    value="{{ old('location') }}"
                    placeholder="Office location" 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('location')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Head Name Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="head_name">
                    Department Head Name
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all" 
                    id="head_name" 
                    name="head_name" 
                    value="{{ old('head_name') }}"
                    placeholder="Head name" 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
            </div>

            <!-- Primary Action -->
            <div class="flex gap-3 justify-end pt-4">
                <a href="{{ route('departments.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors border border-outline rounded-lg">
                    Cancel
                </a>
                <button 
                    class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg" 
                    type="submit">
                    Create Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
