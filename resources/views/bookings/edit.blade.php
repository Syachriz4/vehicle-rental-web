@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Edit Booking</h2>
    <p class="text-lg text-on-surface-variant">{{ $booking->booking_number }}</p>
</div>

<div class="w-full max-w-2xl">
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Vehicle Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="vehicle_id">
                    🚗 Vehicle
                </label>
                <div class="relative group">
                    <select class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('vehicle_id') border-2 border-red-500 @enderror" id="vehicle_id" name="vehicle_id" required>
                        <option value="">Select a vehicle</option>
                        @if($vehicles->count())
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" @if($vehicle->id == $booking->vehicle_id) selected @endif>{{ $vehicle->vehicle_name }} ({{ $vehicle->plate_number }})</option>
                            @endforeach
                        @endif
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

            <!-- Driver Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="driver_id">
                    👤 Driver
                </label>
                <div class="relative group">
                    <select class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body appearance-none focus:ring-0 transition-all pr-10 @error('driver_id') border-2 border-red-500 @enderror" id="driver_id" name="driver_id" required>
                        <option value="">Select a driver</option>
                        @if($drivers->count())
                            @foreach($drivers as $user)
                                <option value="{{ $user->id }}" @if($user->id == $booking->driver_id) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline-variant group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">expand_more</span>
                    </div>
                    <div class="h-[1px] bg-primary transition-all duration-300"></div>
                </div>
                @error('driver_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Date Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="start_date">
                    📅 Start Date
                </label>
                <input class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body focus:ring-0 transition-all @error('start_date') border-2 border-red-500 @enderror" id="start_date" name="start_date" value="{{ $booking->start_date->format('Y-m-d\TH:i') }}" required type="datetime-local" />
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('start_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Date Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="end_date">
                    🏁 End Date
                </label>
                <input class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body focus:ring-0 transition-all @error('end_date') border-2 border-red-500 @enderror" id="end_date" name="end_date" value="{{ $booking->end_date->format('Y-m-d\TH:i') }}" required type="datetime-local" />
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('end_date')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Purpose Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="purpose">
                    📝 Purpose
                </label>
                <textarea class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all @error('purpose') border-2 border-red-500 @enderror" id="purpose" name="purpose" placeholder="Trip purpose" required rows="3">{{ $booking->purpose }}</textarea>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
                @error('purpose')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes Field -->
            <div class="space-y-2">
                <label class="block text-[10px] uppercase tracking-[0.15em] font-semibold text-outline" for="notes">
                    📌 Notes
                </label>
                <textarea class="w-full bg-[#F0F0F0] border-none py-4 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all" id="notes" name="notes" placeholder="Additional notes" rows="3">{{ $booking->notes }}</textarea>
                <div class="h-[1px] bg-primary transition-all duration-300"></div>
            </div>

            <!-- Primary Action -->
            <div class="flex gap-3 justify-end pt-6 border-t border-surface-variant">
                <a href="{{ route('bookings.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface hover:bg-surface-container rounded-lg transition-colors inline-flex items-center gap-2">
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
