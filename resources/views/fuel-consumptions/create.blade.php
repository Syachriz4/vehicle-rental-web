@extends('layouts.app')

@section('title', 'Create Fuel Record')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Create Fuel Record</h2>
    <p class="text-lg text-on-surface-variant">Record fuel consumption</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('fuel-consumptions.store') }}" class="space-y-8">
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

            <!-- Date Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="fuel_date">
                    Date
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body focus:ring-0 transition-all @error('fuel_date') border-2 border-red-500 @enderror" 
                    id="fuel_date" 
                    name="fuel_date" 
                    value="{{ old('fuel_date') }}"
                    required 
                    type="date"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('fuel_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="amount">
                    Amount (Liter)
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('amount') border-2 border-red-500 @enderror" 
                    id="amount" 
                    name="amount" 
                    value="{{ old('amount') }}"
                    placeholder="0.0" 
                    step="0.1"
                    required 
                    type="number"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('amount')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="price">
                    Price (Rp)
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('price') border-2 border-red-500 @enderror" 
                    id="price" 
                    name="price" 
                    value="{{ old('price') }}"
                    placeholder="0" 
                    required 
                    type="number"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('price')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Odometer Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="km_at_fuel">
                    Odometer (KM)
                </label>
                <input 
                    class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('km_at_fuel') border-2 border-red-500 @enderror" 
                    id="km_at_fuel" 
                    name="km_at_fuel" 
                    value="{{ old('km_at_fuel') }}"
                    placeholder="0" 
                    required 
                    type="number"/>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('km_at_fuel')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Primary Action -->
            <div class="flex gap-3 justify-end pt-4">
                <a href="{{ route('fuel-consumptions.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors border border-outline rounded-lg">
                    Cancel
                </a>
                <button 
                    class="bg-primary text-on-primary py-3 px-6 font-headline font-bold text-sm tracking-[0.2em] uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg" 
                    type="submit">
                    Create Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
