@extends('manager.layouts.app')

@section('title', 'Deregistration Request')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>Deregistration Request
            </h2>
            <p class="text-gray-600 text-sm mt-1">Request to remove your property from the platform</p>
        </div>
        <a href="{{ route('manager.dashboard') }}" class="text-green-600 hover:text-green-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
    </div>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded flex items-center">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded flex items-center">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
</div>
@endif

<!-- Existing Request Status -->
@if($deregistrationRequest)
    @if($deregistrationRequest->status === 'PENDING')
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded mb-6">
        <div class="flex items-start">
            <i class="fas fa-hourglass-half text-yellow-600 text-2xl mr-3 mt-1"></i>
            <div>
                <h3 class="text-lg font-bold text-yellow-800 mb-2">Pending Deregistration Request</h3>
                <p class="text-yellow-700 mb-3">You have submitted a deregistration request on <strong>{{ $deregistrationRequest->created_at->format('F d, Y') }}</strong></p>
                <div class="bg-white rounded p-4 mb-4">
                    <div class="mb-3">
                        <p class="text-sm text-gray-600"><strong>Reason:</strong></p>
                        <p class="text-gray-800">{{ ucwords(str_replace('_', ' ', $deregistrationRequest->reason)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600"><strong>Details:</strong></p>
                        <p class="text-gray-800">{{ $deregistrationRequest->reason_details }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('manager.deregistration.cancel', $deregistrationRequest->id) }}" onsubmit="return confirm('Are you sure you want to cancel this deregistration request?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded font-semibold transition flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>Cancel Request
                    </button>
                </form>
            </div>
        </div>
    </div>

    @elseif($deregistrationRequest->status === 'APPROVED')
    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded mb-6">
        <div class="flex items-start">
            <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
            <div>
                <h3 class="text-lg font-bold text-green-800">Deregistration Request Approved</h3>
                <p class="text-green-700 mt-2">Your deregistration request has been approved by the administrator.</p>
                @if($deregistrationRequest->admin_notes)
                <p class="text-green-700 mt-2"><strong>Notes:</strong> {{ $deregistrationRequest->admin_notes }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif
@endif

<!-- Future Bookings Warning -->
@if($futureBookingsCount > 0)
<div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded mb-6">
    <div class="flex items-start">
        <i class="fas fa-exclamation-triangle text-orange-600 text-2xl mr-3"></i>
        <div>
            <h3 class="text-lg font-bold text-orange-800 mb-2">Cannot Submit Deregistration Request</h3>
            <p class="text-orange-700 mb-3">You have <strong>{{ $futureBookingsCount }}</strong> confirmed booking(s) with future check-in dates.</p>
            <p class="text-orange-700 mb-4">Please complete or cancel all future bookings before submitting a deregistration request.</p>
            <a href="{{ route('manager.reservations.index') }}" class="inline-block bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded font-semibold transition flex items-center w-fit">
                <i class="fas fa-calendar-check mr-2"></i>View Bookings
            </a>
        </div>
    </div>
</div>
@endif

<!-- Information Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- What Happens -->
    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded">
        <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>What Happens
        </h3>
        <ul class="space-y-2 text-green-700 text-sm">
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Your property will be marked as "Pending Closure"</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>No new bookings will be accepted</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Your listing will be hidden from public view</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Admin will review within 3-5 business days</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Upon approval, all data will be archived</span>
            </li>
        </ul>
    </div>

    <!-- Before You Proceed -->
    <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded">
        <h3 class="text-lg font-bold text-orange-800 mb-4 flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>Before You Proceed
        </h3>
        <ul class="space-y-2 text-orange-700 text-sm">
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Complete all confirmed bookings</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Cancel future reservations with proper notice</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Settle outstanding commission payments</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Download copies of important reports</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-chevron-right mr-2 mt-1 flex-shrink-0"></i>
                <span>Export guest and booking data if needed</span>
            </li>
        </ul>
    </div>
</div>

<!-- Important Note -->
<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-8">
    <p class="text-red-800 text-sm flex items-start">
        <i class="fas fa-lightbulb mr-2 mt-0.5 flex-shrink-0"></i>
        <strong>Important:</strong> Deregistration is a permanent action. Once approved, your property will be removed from the platform. If you're experiencing temporary issues, consider pausing bookings instead.
    </p>
</div>

<!-- Deregistration Request Form -->
@if(!$deregistrationRequest || $deregistrationRequest->status === 'REJECTED')
<div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-file-alt mr-2 text-red-600"></i>Submit Deregistration Request
    </h3>

    @if($deregistrationRequest && $deregistrationRequest->status === 'REJECTED')
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
        <h4 class="text-red-800 font-semibold mb-2 flex items-center">
            <i class="fas fa-times-circle mr-2"></i>Previous Request Rejected
        </h4>
        <p class="text-red-700 text-sm mb-2"><strong>Rejection Date:</strong> {{ $deregistrationRequest->reviewed_at->format('F d, Y') }}</p>
        <p class="text-red-700 text-sm"><strong>Admin Notes:</strong> {{ $deregistrationRequest->admin_notes ?? 'No notes provided.' }}</p>
    </div>
    @endif

    @if($futureBookingsCount === 0 && (!$deregistrationRequest || in_array($deregistrationRequest->status, ['REJECTED', 'CANCELLED'])))
    <form method="POST" action="{{ route('manager.deregistration.store') }}" id="deregistrationForm">
        @csrf

        <!-- Reason Selection -->
        <div class="mb-6">
            <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-question-circle mr-1 text-red-600"></i>Reason for Deregistration <span class="text-red-600">*</span>
            </label>
            <select name="reason" id="reason" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('reason') border-red-500 @enderror">
                <option value="">-- Select Reason --</option>
                <option value="BUSINESS_CLOSED" {{ old('reason') === 'BUSINESS_CLOSED' ? 'selected' : '' }}>Business Permanently Closed</option>
                <option value="RENOVATION" {{ old('reason') === 'RENOVATION' ? 'selected' : '' }}>Property Under Major Renovation</option>
                <option value="SEASONAL_CLOSURE" {{ old('reason') === 'SEASONAL_CLOSURE' ? 'selected' : '' }}>Seasonal/Temporary Closure</option>
                <option value="SWITCHING_PLATFORM" {{ old('reason') === 'SWITCHING_PLATFORM' ? 'selected' : '' }}>Switching to Another Platform</option>
                <option value="OTHER" {{ old('reason') === 'OTHER' ? 'selected' : '' }}>Other Reason</option>
            </select>
            @error('reason')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Additional Details -->
        <div class="mb-6">
            <label for="reason_details" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-align-left mr-1 text-red-600"></i>Additional Details <span class="text-red-600">*</span>
            </label>
            <textarea name="reason_details" id="reason_details" rows="5" required maxlength="1000"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('reason_details') border-red-500 @enderror"
                placeholder="Please provide detailed information about your reason for deregistration...">{{ old('reason_details') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
            @error('reason_details')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirmation Checkbox -->
        <div class="mb-6">
            <label class="flex items-start cursor-pointer">
                <input type="checkbox" id="confirmCheckbox" required
                    class="mt-1 h-4 w-4 border border-gray-300 rounded focus:ring-2 focus:ring-green-500">
                <span class="ml-3 text-sm text-gray-700">
                    I understand that this is a <strong>permanent action</strong> and my property will be removed from the platform after approval.
                </span>
            </label>
        </div>

        <!-- Form Actions -->
        <div class="flex gap-3 justify-end">
            <a href="{{ route('manager.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" id="submitBtn" disabled
                class="px-6 py-2 bg-red-600 text-white rounded-lg font-semibold transition hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                <i class="fas fa-paper-plane mr-2"></i>Submit Request
            </button>
        </div>
    </form>

    <script>
        const confirmCheckbox = document.getElementById('confirmCheckbox');
        const submitBtn = document.getElementById('submitBtn');

        confirmCheckbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });
    </script>
    @else
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
        <i class="fas fa-ban text-4xl text-gray-400 mb-3"></i>
        <p class="text-gray-700 font-semibold">Unable to submit deregistration request</p>
        <p class="text-gray-600 text-sm mt-2">Please resolve the pending issues before proceeding.</p>
    </div>
    @endif
</div>
@endif
@endsection
