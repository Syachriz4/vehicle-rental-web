@extends('layouts.app')

@section('title', 'Review Approval')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-4xl font-extrabold tracking-tight text-primary mb-2 font-headline">Review Approval</h2>
        <p class="text-lg text-on-surface-variant">{{ $approval->booking->booking_number }} - Level {{ $approval->level }}</p>
    </div>
    <a href="{{ route('approvals.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-on-surface-variant hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Back
    </a>
</div>

<!-- Booking Details -->
<div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg mb-8">
    <h3 class="text-xl font-bold text-on-surface mb-6">Booking Details</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Booking Number</p>
            <p class="text-lg font-bold text-on-surface">{{ $approval->booking->booking_number }}</p>
        </div>
        
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Vehicle</p>
            <p class="text-lg font-bold text-on-surface">{{ $approval->booking->vehicle->vehicle_name ?? 'N/A' }}</p>
            <p class="text-sm text-on-surface-variant">{{ $approval->booking->vehicle->plate_number ?? 'N/A' }}</p>
        </div>
        
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Requestor</p>
            <p class="text-lg font-bold text-on-surface">{{ $approval->booking->user->name ?? 'N/A' }}</p>
        </div>
        
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Status</p>
            <div class="inline-flex">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider @if($approval->booking->status === 'pending') bg-yellow-50 text-yellow-700 @elseif($approval->booking->status === 'approved') bg-green-50 text-green-700 @else bg-red-50 text-red-700 @endif">
                    {{ ucfirst($approval->booking->status) }}
                </span>
            </div>
        </div>
        
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Start Date</p>
            <p class="text-base font-semibold text-on-surface">{{ $approval->booking->start_date->format('d M Y H:i') }}</p>
        </div>
        
        <div class="space-y-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">End Date</p>
            <p class="text-base font-semibold text-on-surface">{{ $approval->booking->end_date->format('d M Y H:i') }}</p>
        </div>
        
        <div class="space-y-2 md:col-span-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Purpose</p>
            <p class="text-base font-semibold text-on-surface">{{ $approval->booking->purpose }}</p>
        </div>
        
        @if($approval->booking->notes)
        <div class="space-y-2 md:col-span-2">
            <p class="text-[10px] uppercase tracking-[0.15em] font-semibold text-outline">Notes</p>
            <p class="text-base text-on-surface">{{ $approval->booking->notes }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Approval Decision -->
@if($approval->status === 'pending')
    <div class="bg-surface-container-lowest p-8 md:p-12 shadow-[0px_20px_50px_rgba(26,28,28,0.06)] rounded-lg">
        <h3 class="text-xl font-bold text-on-surface mb-6">Your Decision</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Approve Form -->
            <form action="{{ route('approvals.approve', $approval->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="approve_comments" class="block text-sm font-semibold text-on-surface mb-2">Comments (Optional)</label>
                        <textarea 
                            id="approve_comments"
                            name="comments" 
                            rows="4"
                            class="w-full bg-[#F0F0F0] border-none py-3 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all rounded-lg"
                            placeholder="Add any comments about this approval...">
                        </textarea>
                    </div>
                    <button 
                        type="submit" 
                        class="w-full bg-green-600 text-white py-3 px-6 font-headline font-bold text-sm tracking-wider uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg inline-flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        Approve
                    </button>
                </div>
            </form>
            
            <!-- Reject Form -->
            <form action="{{ route('approvals.reject', $approval->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="reject_comments" class="block text-sm font-semibold text-on-surface mb-2">Rejection Reason <span class="text-red-600">*</span></label>
                        <textarea 
                            id="reject_comments"
                            name="comments" 
                            rows="4"
                            required
                            class="w-full bg-[#F0F0F0] border-none py-3 px-4 text-sm font-body placeholder:text-outline-variant focus:ring-0 transition-all rounded-lg @error('comments') border-2 border-red-500 @enderror"
                            placeholder="Explain why you're rejecting this booking...">
                        </textarea>
                        @error('comments')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button 
                        type="submit" 
                        class="w-full bg-red-600 text-white py-3 px-6 font-headline font-bold text-sm tracking-wider uppercase hover:opacity-90 active:scale-[0.98] transition-all rounded-lg inline-flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">cancel</span>
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <!-- Already Processed -->
    <div class="bg-surface-container-lowest border-l-4 @if($approval->status === 'approved') border-green-600 @else border-red-600 @endif p-8 rounded-lg">
        <div class="flex items-start gap-4">
            <span class="material-symbols-outlined text-3xl @if($approval->status === 'approved') text-green-600 @else text-red-600 @endif">
                @if($approval->status === 'approved') check_circle @else cancel @endif
            </span>
            <div>
                <h3 class="text-lg font-bold text-on-surface mb-2">
                    {{ ucfirst($approval->status) === 'Approved' ? 'This booking has been approved' : 'This booking has been rejected' }}
                </h3>
                @if($approval->comments)
                    <p class="text-on-surface-variant"><strong>{{ ucfirst($approval->status) === 'Approved' ? 'Comments' : 'Reason' }}:</strong></p>
                    <p class="text-on-surface mt-1">{{ $approval->comments }}</p>
                @endif
                @if($approval->approved_at)
                    <p class="text-sm text-on-surface-variant mt-3">{{ $approval->approved_at->format('d M Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('approvals.index') }}" class="px-6 py-3 text-sm font-semibold text-on-surface hover:bg-surface-container rounded-lg transition-colors inline-flex items-center gap-2">
            <span class="material-symbols-outlined">close</span>
            Back
        </a>
    </div>
@endif
@endsection
