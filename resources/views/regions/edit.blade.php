@extends('layouts.app')

@section('title', 'Edit Region')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Edit Region</h2>
    <p class="text-lg text-on-surface-variant">{{ $region->code }}</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('regions.update', $region->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="name">
                    Region Name
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all" 
                    id="name" 
                    name="name" 
                    value="{{ $region->name }}"
                    required 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
            </div>

            <!-- Code Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="code">
                    Region Code
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all" 
                    id="code" 
                    name="code" 
                    value="{{ $region->code }}"
                    required 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
            </div>

            <!-- Location Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="location">
                    Location
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all" 
                    id="location" 
                    name="location" 
                    value="{{ $region->location }}"
                    required 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
            </div>

            <!-- Primary Action -->
            <div class="flex gap-3 justify-end pt-4">
                <a href="{{ route('regions.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors border border-outline rounded-lg">
                    Cancel
                </a>
                <button 
                    class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg" 
                    type="submit">
                    Update Region
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
