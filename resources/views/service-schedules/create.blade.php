@extends('layouts.app')

@section('title', 'Create Service Schedule')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Create Service Schedule</h2>
    <p class="text-lg text-on-surface-variant">Schedule vehicle maintenance</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('service-schedules.store') }}" class="space-y-8">
            @csrf

            <!-- Vehicle Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="vehicle_id">
                    Vehicle
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('vehicle_id') border-2 border-red-500 @enderror" 
                        id="vehicle_id" 
                        name="vehicle_id"
                        required>
                        <option value="">Select a vehicle</option>
                        @foreach(\App\Models\Vehicle::all() as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>{{ $vehicle->vehicle_name }} ({{ $vehicle->plate_number }})</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
                @error('vehicle_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Service Type Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="service_type">
                    Service Type
                </label>
                <div class="relative group">
                    <select 
                        class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('service_type') border-2 border-red-500 @enderror" 
                        id="service_type" 
                        name="service_type"
                        required>
                        <option value="">Select a service type</option>
                        <option value="maintenance" {{ old('service_type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="inspection" {{ old('service_type') == 'inspection' ? 'selected' : '' }}>Inspection</option>
                        <option value="oil_change" {{ old('service_type') == 'oil_change' ? 'selected' : '' }}>Oil Change</option>
                        <option value="tire_replacement" {{ old('service_type') == 'tire_replacement' ? 'selected' : '' }}>Tire Replacement</option>
                        <option value="filter_replacement" {{ old('service_type') == 'filter_replacement' ? 'selected' : '' }}>Filter Replacement</option>
                        <option value="coolant_replacement" {{ old('service_type') == 'coolant_replacement' ? 'selected' : '' }}>Coolant Replacement</option>
                        <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
                @error('service_type')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Scheduled Date Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="scheduled_date">
                    Scheduled Date
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body focus:ring-0 transition-all @error('scheduled_date') border-2 border-red-500 @enderror" 
                    id="scheduled_date" 
                    name="scheduled_date" 
                    value="{{ old('scheduled_date') }}"
                    required 
                    type="date"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('scheduled_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="notes">
                    Notes
                </label>
                <textarea 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all" 
                    id="notes" 
                    name="notes" 
                    placeholder="Additional notes"
                    rows="4"></textarea>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
            </div>

            <!-- Primary Action -->
            <div class="flex gap-3 justify-end pt-4">
                <a href="{{ route('service-schedules.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors border border-outline rounded-lg">
                    Cancel
                </a>
                <button 
                    class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg" 
                    type="submit">
                    Create Schedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
