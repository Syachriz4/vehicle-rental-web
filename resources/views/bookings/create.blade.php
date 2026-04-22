@extends('layouts.app')

@section('title', 'New Booking')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Create Booking</h2>
    <p class="text-lg text-on-surface-variant">Create a new vehicle booking</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('bookings.store') }}" class="space-y-8">
            @csrf

            <!-- Vehicle Selection -->
            <div class="space-y-3">
                <label class="block text-xs uppercase tracking-widest font-bold text-on-surface">Vehicle *</label>
                <select 
                    class="w-full bg-surface border border-surface-variant rounded-lg py-3 px-4 text-sm font-body appearance-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all cursor-pointer @error('vehicle_id') border-2 border-red-500 @enderror" 
                    id="vehicle_id" 
                    name="vehicle_id"
                    required>
                    <option value="">🚗 Select a vehicle...</option>
                    @foreach(\App\Models\Vehicle::all() as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->vehicle_name }} • {{ $vehicle->plate_number }} ({{ $vehicle->vehicle_type }})
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Driver Selection -->
            <div class="space-y-3">
                <label class="block text-xs uppercase tracking-widest font-bold text-on-surface">Driver *</label>
                <select 
                    class="w-full bg-surface border border-surface-variant rounded-lg py-3 px-4 text-sm font-body appearance-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all cursor-pointer @error('driver_id') border-2 border-red-500 @enderror" 
                    id="driver_id" 
                    name="driver_id"
                    required>
                    <option value="">👤 Select a driver...</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ old('driver_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('driver_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <label class="block text-xs uppercase tracking-widest font-bold text-on-surface">Start Date *</label>
                    <input 
                        class="w-full bg-surface border border-surface-variant rounded-lg py-3 px-4 text-sm font-body placeholder:text-on-surface-variant focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('start_date') border-2 border-red-500 @enderror" 
                        id="start_date" 
                        name="start_date" 
                        value="{{ old('start_date') }}"
                        required 
                        type="datetime-local"/>
                    @error('start_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-xs uppercase tracking-widest font-bold text-on-surface">End Date *</label>
                    <input 
                        class="w-full bg-surface border border-surface-variant rounded-lg py-3 px-4 text-sm font-body placeholder:text-on-surface-variant focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('end_date') border-2 border-red-500 @enderror" 
                        id="end_date" 
                        name="end_date" 
                        value="{{ old('end_date') }}"
                        required 
                        type="datetime-local"/>
                    @error('end_date')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Purpose -->
            <div class="space-y-3">
                <label class="block text-xs uppercase tracking-widest font-bold text-on-surface">Purpose *</label>
                <textarea 
                    class="w-full bg-surface border border-surface-variant rounded-lg py-3 px-4 text-sm font-body placeholder:text-on-surface-variant focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('purpose') border-2 border-red-500 @enderror" 
                    id="purpose" 
                    name="purpose" 
                    placeholder="Describe the booking purpose"
                    rows="4"
                    required></textarea>
                @error('purpose')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="space-y-3">
                <label class="block text-xs uppercase tracking-widest font-bold text-on-surface">Notes (Optional)</label>
                <textarea 
                    class="w-full bg-surface border border-surface-variant rounded-lg py-3 px-4 text-sm font-body placeholder:text-on-surface-variant focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                    id="notes" 
                    name="notes" 
                    placeholder="Additional notes..."
                    rows="4"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 justify-end pt-6 border-t border-surface-variant">
                <a href="{{ route('bookings.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface hover:bg-surface-container rounded-lg transition-colors">
                    Cancel
                </a>
                <button 
                    class="bg-primary text-on-primary py-3 px-8 font-headline font-bold text-sm tracking-wider uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg inline-flex items-center gap-2" 
                    type="submit">
                    <span class="material-symbols-outlined">check</span>
                    Create Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
