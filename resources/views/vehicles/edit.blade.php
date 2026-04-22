@extends('layouts.app')

@section('title', 'Edit Vehicle')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Edit Vehicle</h2>
    <p class="text-lg text-on-surface-variant">{{ $vehicle->plate_number }}</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="vehicle_name">
                    Vehicle Name
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('vehicle_name') border-2 border-red-500 @enderror" 
                    id="vehicle_name" 
                    name="vehicle_name" 
                    value="{{ $vehicle->vehicle_name }}"
                    required 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('vehicle_name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Plate Number Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="plate_number">
                    Plate Number
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('plate_number') border-2 border-red-500 @enderror" 
                    id="plate_number" 
                    name="plate_number" 
                    value="{{ $vehicle->plate_number }}"
                    required 
                    type="text"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('plate_number')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vehicle Type -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="vehicle_type">
                    Vehicle Type
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('vehicle_type') border-2 border-red-500 @enderror" 
                        id="vehicle_type" 
                        name="vehicle_type"
                        required>
                        <option value="passenger" {{ $vehicle->vehicle_type === 'passenger' ? 'selected' : '' }}>Passenger</option>
                        <option value="cargo" {{ $vehicle->vehicle_type === 'cargo' ? 'selected' : '' }}>Cargo</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
                @error('vehicle_type')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Year -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="year">
                    Year
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('year') border-2 border-red-500 @enderror" 
                    id="year" 
                    name="year" 
                    value="{{ $vehicle->year }}"
                    min="2000"
                    max="2099"
                    required 
                    type="number"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('year')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Region -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="region_id">
                    Region
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('region_id') border-2 border-red-500 @enderror" 
                        id="region_id" 
                        name="region_id"
                        required>
                        <option value="">Select region</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ $vehicle->region_id == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
                @error('region_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="status">
                    Status
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('status') border-2 border-red-500 @enderror" 
                        id="status" 
                        name="status"
                        required>
                        <option value="available" {{ $vehicle->status === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="in_use" {{ $vehicle->status === 'in_use' ? 'selected' : '' }}>In Use</option>
                        <option value="maintenance" {{ $vehicle->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
                @error('status')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Primary Action -->
            <div class="flex gap-3 justify-end pt-6 border-t border-surface-variant">
                <a href="{{ route('vehicles.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface hover:bg-surface-container rounded-lg transition-colors inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">close</span>
                    Cancel
                </a>
                <button 
                    class="bg-blue-600 text-white py-3 px-8 font-headline font-bold text-sm tracking-wider uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg inline-flex items-center gap-2" 
                    type="submit">
                    <span class="material-symbols-outlined">check</span>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
