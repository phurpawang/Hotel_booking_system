@extends('owner.layouts.app')

@section('title', 'Edit Promotion')

@section('header')
    <div class="flex items-center mb-2">
        <a href="{{ route('owner.promotions.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Edit Promotion</h2>
    </div>
    <p class="text-gray-600 text-sm">Update promotional offer</p>
@endsection

@section('content')
@if($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
    <p class="font-bold mb-2">Please fix the following errors:</p>
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
    <form action="{{ route('owner.promotions.update', $promotion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Promotion Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $promotion->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $promotion->description) }}</textarea>
            </div>

            <div>
                <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Discount Type <span class="text-red-500">*</span>
                </label>
                <select name="discount_type" id="discount_type" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select --</option>
                    <option value="percentage" {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (Nu.)</option>
                </select>
            </div>

            <div>
                <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">
                    Discount Value <span class="text-red-500">*</span>
                </label>
                <input type="number" name="discount_value" id="discount_value" min="0" step="0.01" value="{{ old('discount_value', $promotion->discount_value) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="room_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Apply to Room Type
                </label>
                <select name="room_type" id="room_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- All Room Types (Hotel-wide) --</option>
                    @foreach($roomsGroupedByType as $roomType => $rooms)
                    <option value="{{ $roomType }}" {{ old('room_type', $promotion->room_type) == $roomType ? 'selected' : '' }}>
                        {{ $roomType }} ({{ count($rooms) }} room{{ count($rooms) > 1 ? 's' : '' }})
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Leave empty to apply to all room types in your hotel</p>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Start Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    End Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('owner.promotions.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i>
                Update Promotion
            </button>
        </div>
    </form>
</div>

<script>
    // ========== FORM SUBMISSION SAFEGUARDS ==========
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (!form) return;

        let formSubmitted = false;  // Flag to track if form is being submitted
        
        // Add invisible tracking input to detect if form was submitted
        const trackingInput = document.createElement('input');
        trackingInput.type = 'hidden';
        trackingInput.name = 'submit_token';
        trackingInput.value = Date.now() + Math.random();
        form.appendChild(trackingInput);

        // Main form submission handler
        form.addEventListener('submit', function(e) {
            // Debug: Log submission attempt
            console.log('Form submission triggered at:', new Date().toISOString());

            // If already submitted, prevent duplicate
            if (formSubmitted) {
                console.warn('Form submission prevented - already submitted');
                e.preventDefault();
                return false;
            }

            // Mark as submitted
            formSubmitted = true;
            console.log('Form marked as submitted');

            // Disable all form inputs and buttons to prevent user interaction
            const submitButton = form.querySelector('button[type="submit"]');
            const inputs = form.querySelectorAll('input, textarea, select, button');

            inputs.forEach(input => {
                if (input !== submitButton) {
                    input.disabled = true;
                }
            });

            // Update submit button text and disable it
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.style.opacity = '0.6';
                submitButton.style.cursor = 'not-allowed';
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
                console.log('Submit button disabled');
            }

            // Add safeguard: if form hasn't submitted after 30 seconds, re-enable
            const reenableTimeout = setTimeout(() => {
                console.warn('Form submission timeout - re-enabling form');
                formSubmitted = false;
                inputs.forEach(input => {
                    input.disabled = false;
                });
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.style.opacity = '1';
                    submitButton.style.cursor = 'pointer';
                    submitButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update Promotion';
                }
            }, 30000);

            // Attach timeout ID to form for potential cleanup
            form.submitting_timeout = reenableTimeout;

            // Allow form to submit normally
            console.log('Form submission allowed to proceed');
            return true;
        });

        // Additional safeguard: Prevent form submission via Enter key in inputs
        const inputs = form.querySelectorAll('input[type="text"], input[type="email"], textarea');
        inputs.forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.ctrlKey) {
                    e.preventDefault();
                    console.log('Enter key in input prevented');
                }
            });
        });

        console.log('Form submission safeguards initialized');
    });
</script>

@endsection
