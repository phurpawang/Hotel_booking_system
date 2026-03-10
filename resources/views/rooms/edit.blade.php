@extends('layouts.dashboard-bootstrap')

@section('title', 'Edit Room - ' . $room->room_number)

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2"><i class="bi bi-pencil-square me-2"></i>Edit Room {{ $room->room_number }}</h2>
                <p class="text-muted mb-0">Update room information</p>
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
                <div class="card-header py-3" style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); color: white;">
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

                    <form method="POST" action="{{ route(strtolower(Auth::user()->role) . '.rooms.update', $room->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Room Number & Type -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #ffa751;">
                                    <i class="bi bi-hash me-1"></i>Room Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="room_number" 
                                       class="form-control form-control-lg @error('room_number') is-invalid @enderror" 
                                       placeholder="e.g., 101, 102" 
                                       value="{{ old('room_number', $room->room_number) }}" 
                                       required>
                                @error('room_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Unique identifier for this room</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #ffa751;">
                                    <i class="bi bi-grid-3x3-gap-fill me-1"></i>Room Type <span class="text-danger">*</span>
                                </label>
                                <select name="room_type" 
                                        class="form-select form-select-lg @error('room_type') is-invalid @enderror" 
                                        required>
                                    <option value="">-- Select Room Type --</option>
                                    <option value="Single" {{ old('room_type', $room->room_type) == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Double" {{ old('room_type', $room->room_type) == 'Double' ? 'selected' : '' }}>Double</option>
                                    <option value="Twin" {{ old('room_type', $room->room_type) == 'Twin' ? 'selected' : '' }}>Twin</option>
                                    <option value="Deluxe" {{ old('room_type', $room->room_type) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                    <option value="Suite" {{ old('room_type', $room->room_type) == 'Suite' ? 'selected' : '' }}>Suite</option>
                                    <option value="Family" {{ old('room_type', $room->room_type) == 'Family' ? 'selected' : '' }}>Family</option>
                                    <option value="VIP" {{ old('room_type', $room->room_type) == 'VIP' ? 'selected' : '' }}>VIP</option>
                                </select>
                                @error('room_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Quantity, Capacity & Price -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="color: #ffa751;">
                                    <i class="bi bi-stack me-1"></i>Quantity (Number of Rooms) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       name="quantity" 
                                       class="form-control form-control-lg @error('quantity') is-invalid @enderror" 
                                       placeholder="e.g., 1" 
                                       min="1" 
                                       max="100" 
                                       value="{{ old('quantity', $room->quantity ?? 1) }}" 
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">How many of this room type</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="color: #ffa751;">
                                    <i class="bi bi-people-fill me-1"></i>Capacity (Number of Guests) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       name="capacity" 
                                       class="form-control form-control-lg @error('capacity') is-invalid @enderror" 
                                       placeholder="e.g., 2" 
                                       min="1" 
                                       max="20" 
                                       value="{{ old('capacity', $room->capacity) }}" 
                                       required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum number of people</small>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold" style="color: #ffa751;">
                                    <i class="bi bi-tag-fill me-1"></i>Base Price Per Night (Nu.) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       id="base_price"
                                       name="base_price" 
                                       class="form-control form-control-lg @error('base_price') is-invalid @enderror" 
                                       placeholder="e.g., 2500.00" 
                                       min="0" 
                                       step="0.01" 
                                       value="{{ old('base_price', $room->base_price ?? $room->price_per_night) }}" 
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
                                <div class="alert alert-info border-0" style="background: linear-gradient(135deg, #ffa75115 0%, #ffe25915 100%);">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-calculator me-2"></i>Commission Breakdown</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" style="color: #ffa751;">Commission Rate (%)</label>
                                            <input type="number" 
                                                   id="commission_rate"
                                                   name="commission_rate" 
                                                   class="form-control" 
                                                   value="{{ old('commission_rate', $room->commission_rate ?? 10.00) }}" 
                                                   min="0" 
                                                   max="100" 
                                                   step="0.01"
                                                   readonly>
                                            <small class="text-muted">Platform commission</small>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" style="color: #ffa751;">Commission Amount (Nu.)</label>
                                            <input type="text" 
                                                   id="commission_amount"
                                                   class="form-control" 
                                                   value="{{ number_format($room->commission_amount ?? 0, 2) }}" 
                                                   readonly>
                                            <small class="text-muted">Calculated automatically</small>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-success">Final Price (Nu.)</label>
                                            <input type="text" 
                                                   id="final_price"
                                                   class="form-control fw-bold text-success" 
                                                   value="{{ number_format($room->final_price ?? $room->price_per_night, 2) }}" 
                                                   readonly>
                                            <small class="text-muted">Price shown to guests</small>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="text-center w-100">
                                                <div class="badge bg-success fs-6 py-2 px-3">
                                                    <i class="bi bi-piggy-bank me-2"></i>Your Earning: <span id="your_earning">{{ number_format($room->base_price ?? $room->price_per_night, 2) }}</span> Nu.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #ffa751;">
                                <i class="bi bi-file-text me-1"></i>Description
                            </label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Enter room description, features, view, etc.">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Describe room features, view, special amenities (max 1000 characters)</small>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #ffa751;">
                                <i class="bi bi-list-check me-1"></i>Amenities
                            </label>
                            <input type="text" 
                                   name="amenities" 
                                   class="form-control form-control-lg @error('amenities') is-invalid @enderror" 
                                   placeholder="e.g., WiFi, TV, AC, Mini Bar, Balcony" 
                                   value="{{ old('amenities', is_array($room->amenities) ? implode(', ', $room->amenities) : '') }}">
                            @error('amenities')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Comma-separated list of amenities</small>
                        </div>

                        <!-- Current Status -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #ffa751;">
                                    <i class="bi bi-bookmark-fill me-1"></i>Room Status
                                </label>
                                <select name="status" 
                                        class="form-select form-select-lg @error('status') is-invalid @enderror">
                                    <option value="AVAILABLE" {{ old('status', $room->status) == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                                    <option value="OCCUPIED" {{ old('status', $room->status) == 'OCCUPIED' ? 'selected' : '' }}>Occupied</option>
                                    <option value="MAINTENANCE" {{ old('status', $room->status) == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Current operational status of the room</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: #ffa751;">
                                    <i class="bi bi-unlock-fill me-1"></i>Booking Availability
                                </label>
                                <select name="is_available" 
                                        class="form-select form-select-lg @error('is_available') is-invalid @enderror">
                                    <option value="1" {{ old('is_available', $room->is_available) == 1 ? 'selected' : '' }}>Open for Booking</option>
                                    <option value="0" {{ old('is_available', $room->is_available) == 0 ? 'selected' : '' }}>Closed for Booking</option>
                                </select>
                                @error('is_available')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Allow guests to book this room online</small>
                            </div>
                        </div>

                        <!-- Existing Photos -->
                        @if($room->photos && count($room->photos) > 0)
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #ffa751;">
                                <i class="bi bi-image me-1"></i>Current Photos
                            </label>
                            <div class="row g-3">
                                @foreach($room->photos as $index => $photo)
                                <div class="col-md-3" id="photo-{{ $index }}">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded" alt="Room Photo">
                                        <button type="button" 
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                                                onclick="deletePhoto('{{ $photo }}', {{ $index }})"
                                                title="Delete Photo">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- New Room Photos -->
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #ffa751;">
                                <i class="bi bi-image me-1"></i>Add New Photos
                            </label>
                            <input type="file" 
                                   name="photos[]" 
                                   class="form-control form-control-lg @error('photos.*') is-invalid @enderror" 
                                   accept="image/*" 
                                   multiple>
                            @error('photos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Upload additional room photos (JPG, JPEG, PNG, GIF - max 2MB each)</small>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-gradient-warning btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Update Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Delete Photo Confirmation Modal -->
<div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePhotoModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete this photo? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash me-1"></i>Delete Photo
                </button>
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
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        padding: 2.5rem;
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(255, 167, 81, 0.3);
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
        border-color: #ffa751;
        box-shadow: 0 0 0 0.25rem rgba(255, 167, 81, 0.15);
        transform: translateY(-2px);
    }

    .form-control-lg,
    .form-select-lg {
        padding: 0.875rem 1.25rem;
        font-size: 1.1rem;
    }

    .btn-gradient-warning {
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(255, 167, 81, 0.4);
        transition: all 0.3s ease;
    }

    .btn-gradient-warning:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 167, 81, 0.5);
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
        color: #ffa751;
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

    img.img-fluid {
        max-height: 150px;
        object-fit: cover;
        width: 100%;
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

// Delete photo function
let pendingDeletePhoto = null;
let pendingDeleteIndex = null;

function deletePhoto(photoPath, index) {
    // Store the photo info for later
    pendingDeletePhoto = photoPath;
    pendingDeleteIndex = index;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('deletePhotoModal'));
    modal.show();
}

// Handle actual deletion when user confirms
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!pendingDeletePhoto || pendingDeleteIndex === null) {
            return;
        }
        
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deletePhotoModal'));
        modal.hide();
        
        // Show loading state
        const photoElement = document.getElementById('photo-' + pendingDeleteIndex);
        const originalContent = photoElement.innerHTML;
        photoElement.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Deleting...</span></div></div>';
        
        // Send delete request
        fetch("{{ route(strtolower(Auth::user()->role) . '.rooms.delete-photo', $room->id) }}", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                photo: pendingDeletePhoto
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove photo element with fade effect
                photoElement.style.transition = 'opacity 0.3s ease';
                photoElement.style.opacity = '0';
                setTimeout(() => {
                    photoElement.remove();
                    
                    // Show success message
                    const alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    document.querySelector('.card-body').insertAdjacentHTML('afterbegin', alertHtml);
                    
                    // Auto-dismiss after 3 seconds
                    setTimeout(() => {
                        const alert = document.querySelector('.alert-success');
                        if (alert) {
                            alert.remove();
                        }
                    }, 3000);
                }, 300);
            } else {
                // Restore original content and show error
                photoElement.innerHTML = originalContent;
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>Error: ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                document.querySelector('.card-body').insertAdjacentHTML('afterbegin', alertHtml);
            }
            
            // Reset pending deletion
            pendingDeletePhoto = null;
            pendingDeleteIndex = null;
        })
        .catch(error => {
            // Restore original content and show error
            photoElement.innerHTML = originalContent;
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>Failed to delete photo. Please try again.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.querySelector('.card-body').insertAdjacentHTML('afterbegin', alertHtml);
            console.error('Error:', error);
            
            // Reset pending deletion
            pendingDeletePhoto = null;
            pendingDeleteIndex = null;
        });
    });
});
</script>

@endsection
