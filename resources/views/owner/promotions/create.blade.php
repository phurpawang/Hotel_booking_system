@extends('owner.layouts.app')

@section('title', 'Create Promotion')

@section('content')
<!-- Create Promotion Container -->
<div style="max-width: 900px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Back Button -->
    <a href="{{ route('owner.promotions.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; transition: all 0.3s ease;" onmouseover="this.style.color='#764ba2'" onmouseout="this.style.color='#667eea'">
        <i class="fas fa-arrow-left"></i>Back to Promotions
    </a>

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-tags" style="font-size: 2.5rem; opacity: 0.95;"></i>
            <div>
                <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Create New Promotion</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Set up a new promotional offer</p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div style="background: linear-gradient(135deg, #ef444415 0%, #dc262615 100%); border-left: 5px solid #ef4444; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #991b1b; font-weight: 700; margin-bottom: 0.75rem;"><i class="fas fa-exclamation-circle mr-2"></i>Please fix the following errors:</div>
        <ul style="margin: 0; padding-left: 1.5rem; color: #7f1d1d;">
            @foreach($errors->all() as $error)
            <li style="margin-bottom: 0.5rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Card -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; padding: 2.5rem; animation: slideInUp 0.5s ease-out;">
        
        <form action="{{ route('owner.promotions.store') }}" method="POST">
            @csrf

            <!-- Promotion Title Field -->
            <div style="margin-bottom: 1.5rem;">
                <label for="title" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                    Promotion Title 
                    <span style="color: #ef4444; font-weight: 700;">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                    placeholder="e.g., Summer Special, Weekend Getaway">
                @error('title')
                    <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <!-- Description Field -->
            <div style="margin-bottom: 1.5rem;">
                <label for="description" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                    Description 
                    <span style="color: #ef4444; font-weight: 700;">*</span>
                </label>
                <textarea name="description" id="description" rows="4" required
                    style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit; resize: vertical;" 
                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                    placeholder="Describe the promotion details and benefits...">{{ old('description') }}</textarea>
                @error('description')
                    <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                
                <!-- Discount Type Dropdown -->
                <div style="display: flex; flex-direction: column;">
                    <label for="discount_type" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        Discount Type 
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <select name="discount_type" id="discount_type" required
                        style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        <option value="">-- Select Discount Type --</option>
                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (Nu.)</option>
                    </select>
                    @error('discount_type')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Discount Value Field -->
                <div style="display: flex; flex-direction: column;">
                    <label for="discount_value" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        Discount Value 
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="number" name="discount_value" id="discount_value" min="0" max="999999" step="0.01" value="{{ old('discount_value') }}" required
                            style="flex: 1; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                            placeholder="15"
                            oninput="updateDiscountPreview()">
                        <span style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 700; min-width: 50px; text-align: center;" id="discountPreview">0</span>
                    </div>
                    @error('discount_value')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Room Type Dropdown -->
                <div style="display: flex; flex-direction: column;">
                    <label for="room_type" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        Apply to Room Type
                    </label>
                    <select name="room_type" id="room_type"
                        style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        <option value="">-- All Room Types (Hotel-wide) --</option>
                        @foreach($roomsGroupedByType as $roomType => $rooms)
                        <option value="{{ $roomType }}" {{ old('room_type') == $roomType ? 'selected' : '' }}>
                            {{ $roomType }} ({{ count($rooms) }} room{{ count($rooms) > 1 ? 's' : '' }})
                        </option>
                        @endforeach
                    </select>
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">Leave empty to apply to all room types in your hotel</p>
                    @error('room_type')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Date Fields Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                
                <!-- Start Date Field -->
                <div style="display: flex; flex-direction: column;">
                    <label for="start_date" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        Start Date 
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                        style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    @error('start_date')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- End Date Field -->
                <div style="display: flex; flex-direction: column;">
                    <label for="end_date" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        End Date 
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                        style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    @error('end_date')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Active Checkbox -->
            <div style="background: #f9fafb; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem; border: 2px solid #f0f0f0; transition: all 0.3s ease;">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        style="width: 20px; height: 20px; cursor: pointer; accent-color: #667eea;">
                    <div>
                        <p style="margin: 0; font-weight: 700; color: #333;">Active</p>
                        <p style="margin: 0.25rem 0 0 0; color: #666; font-size: 0.85rem;">This promotion will be visible and active</p>
                    </div>
                </label>
            </div>

            <!-- Divider -->
            <div style="height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb, transparent); margin: 2rem 0;"></div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('owner.promotions.index') }}" style="background: #f3f4f6; color: #374151; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='#f3f4f6'">
                    <i class="fas fa-times"></i>Cancel
                </a>
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.3)'">
                    <i class="fas fa-plus"></i>Create Promotion
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // ========== DISCOUNT PREVIEW FUNCTIONALITY ==========
    function updateDiscountPreview() {
        const discountType = document.getElementById('discount_type').value;
        const discountValue = document.getElementById('discount_value').value;
        const discountPreview = document.getElementById('discountPreview');
        
        if (!discountType || !discountValue) {
            discountPreview.textContent = '0';
            return;
        }
        
        if (discountType === 'percentage') {
            discountPreview.textContent = (discountValue || 0) + '%';
        } else if (discountType === 'fixed') {
            discountPreview.textContent = 'Nu.' + (discountValue || 0);
        } else {
            discountPreview.textContent = discountValue || 0;
        }
    }

    // ========== FORM SUBMISSION SAFEGUARDS ==========
    document.addEventListener('DOMContentLoaded', function() {
        // Setup discount preview listeners
        updateDiscountPreview();
        const discountTypeSelect = document.getElementById('discount_type');
        const discountValueInput = document.getElementById('discount_value');
        
        if (discountTypeSelect) {
            discountTypeSelect.addEventListener('change', updateDiscountPreview);
        }
        if (discountValueInput) {
            discountValueInput.addEventListener('input', updateDiscountPreview);
        }

        // ========== PREVENT FORM DOUBLE-SUBMISSION ==========
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
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
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
                    submitButton.innerHTML = '<i class="fas fa-plus"></i>Create Promotion';
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
