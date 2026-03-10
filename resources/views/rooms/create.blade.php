@extends('layouts.dashboard-bootstrap')

@section('title', 'Add New Room - ' . $hotel->name)

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2"><i class="bi bi-plus-circle-fill me-2"></i>Add New Room</h2>
                <p class="text-muted mb-0">{{ $hotel->name }}</p>
            </div>
            <div>
                <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Back to Rooms
                </a>
            </div>
        </div>
    </div>

    <!-- Room Form -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0"><i class="bi bi-door-closed-fill me-2"></i>Room Information</h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.rooms.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Room Number & Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #667eea;">
                                    <i class="bi bi-hash me-1"></i>Room Number 
                                    <span class="badge bg-info text-white ms-2">Auto-generated</span>
                                </label>
                                <input type="text" 
                                       name="room_number" 
                                       class="form-control form-control-lg @error('room_number') is-invalid @enderror" 
                                       placeholder="Leave empty for auto-generation" 
                                       value="{{ old('room_number') }}">
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    Leave empty to auto-generate next available number for the room type
                                </small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #667eea;">
                                    <i class="bi bi-grid-3x3-gap-fill me-1"></i>Room Type <span class="text-danger">*</span>
                                </label>
                                <select name="room_type" 
                                        class="form-select form-select-lg @error('room_type') is-invalid @enderror" 
                                        required>
                                    <option value="">-- Select Room Type --</option>
                                    <option value="Single" {{ old('room_type') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Double" {{ old('room_type') == 'Double' ? 'selected' : '' }}>Double</option>
                                    <option value="Twin" {{ old('room_type') == 'Twin' ? 'selected' : '' }}>Twin</option>
                                    <option value="Deluxe" {{ old('room_type') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                    <option value="Suite" {{ old('room_type') == 'Suite' ? 'selected' : '' }}>Suite</option>
                                    <option value="Family" {{ old('room_type') == 'Family' ? 'selected' : '' }}>Family</option>
                                    <option value="VIP" {{ old('room_type') == 'VIP' ? 'selected' : '' }}>VIP</option>
                                </select>
                                @error('room_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Quantity, Capacity & Price -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="color: #667eea;">
                                    <i class="bi bi-stack me-1"></i>Quantity (Number of Rooms) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       class="form-control form-control-lg @error('quantity') is-invalid @enderror" 
                                       placeholder="e.g., 1" 
                                       min="1" 
                                       max="100" 
                                       value="{{ old('quantity', 1) }}" 
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">How many of this room type</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="color: #667eea;">
                                    <i class="bi bi-people-fill me-1"></i>Capacity (Number of Guests) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       name="capacity" 
                                       class="form-control form-control-lg @error('capacity') is-invalid @enderror" 
                                       placeholder="e.g., 2" 
                                       min="1" 
                                       max="20" 
                                       value="{{ old('capacity', 2) }}" 
                                       required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum number of people</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="color: #667eea;">
                                    <i class="bi bi-tag-fill me-1"></i>Base Price Per Night (Nu.) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       id="base_price"
                                       name="base_price" 
                                       class="form-control form-control-lg @error('base_price') is-invalid @enderror" 
                                       placeholder="e.g., 2500.00" 
                                       min="0" 
                                       step="0.01" 
                                       value="{{ old('base_price') }}" 
                                       required>
                                @error('base_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Your earning per night</small>
                            </div>
                        </div>

                        <!-- Commission Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info border-0" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-calculator me-2"></i>Commission Breakdown</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" style="color: #667eea;">Commission Rate (%)</label>
                                            <input type="number" 
                                                   id="commission_rate"
                                                   name="commission_rate" 
                                                   class="form-control" 
                                                   value="10.00" 
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   readonly>
                                            <small class="text-muted">Platform commission</small>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" style="color: #667eea;">Commission Amount (Nu.)</label>
                                            <input type="text" 
                                                   id="commission_amount"
                                                   class="form-control" 
                                                   value="0.00" 
                                                   readonly>
                                            <small class="text-muted">Calculated automatically</small>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-success">Final Price (Nu.)</label>
                                            <input type="text" 
                                                   id="final_price"
                                                   class="form-control fw-bold text-success" 
                                                   value="0.00" 
                                                   readonly>
                                            <small class="text-muted">Price shown to guests</small>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="text-center w-100">
                                                <div class="badge bg-success fs-6 py-2 px-3">
                                                    <i class="bi bi-piggy-bank me-2"></i>Your Earning: <span id="your_earning">0.00</span> Nu.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #667eea;">
                                <i class="bi bi-file-text me-1"></i>Description
                            </label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Enter room description, features, view, etc.">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Describe room features, view, special amenities (max 1000 characters)</small>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #667eea;">
                                <i class="bi bi-list-check me-1"></i>Amenities
                            </label>
                            <input type="text" 
                                   name="amenities" 
                                   class="form-control form-control-lg @error('amenities') is-invalid @enderror" 
                                   placeholder="e.g., WiFi, TV, AC, Mini Bar, Balcony" 
                                   value="{{ old('amenities') }}">
                            @error('amenities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Comma-separated list of amenities</small>
                        </div>

                        <!-- Room Photos -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #667eea;">
                                <i class="bi bi-image me-1"></i>Room Photos
                            </label>
                            <input type="file" 
                                   name="photos[]" 
                                   class="form-control form-control-lg @error('photos.*') is-invalid @enderror" 
                                   accept="image/*" 
                                   multiple>
                            @error('photos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Upload multiple room photos (JPG, JPEG, PNG, GIF - max 2MB each)</small>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-gradient-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Create Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2.5rem;
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .dashboard-header h2 {
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .card-header {
        border: none;
        font-weight: 600;
    }

    .form-label {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border: 2px solid #e0e6ed;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .form-control-lg,
    .form-select-lg {
        padding: 0.875rem 1.25rem;
        font-size: 1.1rem;
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        font-weight: 600;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }

    .btn-light {
        background: white;
        border: 2px solid white;
        color: #667eea;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background: transparent;
        border-color: white;
        color: white;
    }

    .alert {
        border-radius: 15px;
        border: none;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        font-weight: 500;
    }

    small.text-muted {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
    }

    hr {
        border-top: 2px solid #e9ecef;
        opacity: 1;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const basePriceInput = document.getElementById('base_price');
    const commissionRateInput = document.getElementById('commission_rate');
    const commissionAmountInput = document.getElementById('commission_amount');
    const finalPriceInput = document.getElementById('final_price');
    const yourEarningSpan = document.getElementById('your_earning');

    function calculateCommission() {
        const basePrice = parseFloat(basePriceInput.value) || 0;
        const commissionRate = parseFloat(commissionRateInput.value) || 10.00;
        
        const commissionAmount = Math.round((basePrice * commissionRate / 100) * 100) / 100;
        const finalPrice = Math.round((basePrice + commissionAmount) * 100) / 100;
        
        commissionAmountInput.value = commissionAmount.toFixed(2);
        finalPriceInput.value = finalPrice.toFixed(2);
        yourEarningSpan.textContent = basePrice.toFixed(2);
    }

    basePriceInput.addEventListener('input', calculateCommission);
    commissionRateInput.addEventListener('input', calculateCommission);
    
    // Calculate on page load
    calculateCommission();
});
</script>

@endsection
