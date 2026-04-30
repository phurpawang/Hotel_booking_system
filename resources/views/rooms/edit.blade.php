@extends(
    strtoupper(Auth::user()->role) === 'MANAGER' ? 'manager.layouts.app' :
    (in_array(strtoupper(Auth::user()->role), ['RECEPTIONIST', 'RECEPTION']) ? 'reception.layouts.app' : 'layouts.owner-bootstrap')
)

@section('title', 'Edit Room')

@section('header')
    <h2 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Edit Room</h2>
    <p class="text-gray-600 text-sm mt-1">Room {{ $room->room_number }}</p>
@endsection

@section('content')
<div class="container-fluid py-5">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-6">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
            <div style="flex: 1;">
                <h2 class="mb-2" style="margin-top: 0;"><i class="fas fa-door-open me-3"></i>Edit Room {{ $room->room_number }}</h2>
                <p class="text-white text-opacity-90 mb-0">Update room information and settings</p>
            </div>
            <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" class="btn btn-light btn-lg" style="white-space: nowrap; padding: 10px 25px; margin-top: 5px; flex-shrink: 0;">
                <i class="bi bi-arrow-left me-2"></i>Back to Rooms
            </a>
        </div>
    </div>

    <!-- Room Form Main Card -->
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow-lg" style="border: none; border-radius: 20px; overflow: hidden;">
                <div class="card-header py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                    <h5 class="mb-0"><i class="fas fa-home me-2"></i>Room Information</h5>
                </div>
                <div class="card-body p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #fee 0%, #fdd 100%);">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Please fix the following errors:</h6>
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
                        
                        <!-- Room Identifiers Section -->
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-left: 4px solid #667eea; padding: 20px; border-radius: 15px;">
                                <h6 style="color: #667eea; font-weight: 700; margin-bottom: 20px;"><i class="fas fa-info-circle me-2"></i>Room Identifiers</h6>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" style="color: #667eea;">
                                            <i class="fas fa-door-open me-2"></i>Room Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="room_number" 
                                               class="form-control form-control-lg" 
                                               style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px; transition: all 0.3s ease;"
                                               placeholder="e.g., 101, 102" 
                                               value="{{ old('room_number', $room->room_number) }}" 
                                               required
                                               onchange="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'">
                                        @error('room_number')
                                            <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Unique identifier for this room</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" style="color: #3b82f6;">
                                            <i class="fas fa-layer-group me-2"></i>Room Type <span class="text-danger">*</span>
                                        </label>
                                        <select name="room_type" 
                                                class="form-select form-select-lg"
                                                style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px; transition: all 0.3s ease;"
                                                required
                                                onchange="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'">
                                            <option value="">-- Select Room Type --</option>
                                            <option value="Single" {{ old('room_type', $room->room_type) == 'Single' ? 'selected' : '' }}>🛏️ Single</option>
                                            <option value="Double" {{ old('room_type', $room->room_type) == 'Double' ? 'selected' : '' }}>🛏️ Double</option>
                                            <option value="Twin" {{ old('room_type', $room->room_type) == 'Twin' ? 'selected' : '' }}>🛏️🛏️ Twin</option>
                                            <option value="Deluxe" {{ old('room_type', $room->room_type) == 'Deluxe' ? 'selected' : '' }}>✨ Deluxe</option>
                                            <option value="Suite" {{ old('room_type', $room->room_type) == 'Suite' ? 'selected' : '' }}>👑 Suite</option>
                                            <option value="Family" {{ old('room_type', $room->room_type) == 'Family' ? 'selected' : '' }}>👨‍👩‍👧‍👦 Family</option>
                                            <option value="VIP" {{ old('room_type', $room->room_type) == 'VIP' ? 'selected' : '' }}>🌟 VIP</option>
                                        </select>
                                        @error('room_type')
                                            <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Room Capacity & Quantity Section -->
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #10b98115 0%, #059e0b15 100%); border-left: 4px solid #10b981; padding: 20px; border-radius: 15px;">
                                <h6 style="color: #10b981; font-weight: 700; margin-bottom: 20px;"><i class="fas fa-users me-2"></i>Capacity & Quantity</h6>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold" style="color: #10b981;">
                                            <i class="fas fa-objects-column me-2"></i>Quantity <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               name="quantity" 
                                               class="form-control form-control-lg" 
                                               style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px;"
                                               placeholder="e.g., 1" 
                                               min="1" 
                                               max="100" 
                                               value="{{ old('quantity', $room->quantity ?? 1) }}" 
                                               required>
                                        @error('quantity')
                                            <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Number of this room type</small>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold" style="color: #10b981;">
                                            <i class="fas fa-person me-2"></i>Capacity <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               name="capacity" 
                                               class="form-control form-control-lg" 
                                               style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px;"
                                               placeholder="e.g., 2" 
                                               min="1" 
                                               max="20" 
                                               value="{{ old('capacity', $room->capacity) }}" 
                                               required>
                                        @error('capacity')
                                            <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Max guests</small>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold" style="color: #059e0b;">
                                            <i class="fas fa-money-bill me-2"></i>Base Price (Nu.) <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               id="base_price"
                                               name="base_price" 
                                               class="form-control form-control-lg" 
                                               style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px;"
                                               placeholder="e.g., 2500.00" 
                                               min="0" 
                                               step="0.01" 
                                               value="{{ old('base_price', $room->base_price ?? $room->price_per_night) }}" 
                                               required>
                                        @error('base_price')
                                            <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Your earning</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Commission Breakdown Section -->
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #f59e0b15 0%, #f97316 15 100%); border-left: 4px solid #f59e0b; padding: 20px; border-radius: 15px;">
                                <h6 style="color: #f59e0b; font-weight: 700; margin-bottom: 20px;"><i class="fas fa-calculator me-2"></i>Commission Breakdown</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold" style="color: #f59e0b;">Commission Rate (%)</label>
                                        <input type="number" 
                                               id="commission_rate"
                                               name="commission_rate" 
                                               class="form-control"
                                               style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px; background-color: #f9f9f9;"
                                               value="{{ old('commission_rate', $room->commission_rate ?? 10.00) }}" 
                                               min="0" 
                                               max="100" 
                                               step="0.01"
                                               readonly>
                                        <small class="text-muted d-block mt-2">Platform fee</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold" style="color: #f59e0b;">Commission (Nu.)</label>
                                        <input type="text" 
                                               id="commission_amount"
                                               class="form-control"
                                               style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px; background-color: #f9f9f9;"
                                               value="{{ number_format($room->commission_amount ?? 0, 2) }}" 
                                               readonly>
                                        <small class="text-muted d-block mt-2">Auto-calculated</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold text-success">Final Price (Nu.)</label>
                                        <input type="text" 
                                               id="final_price"
                                               class="form-control fw-bold"
                                               style="border: 2px solid #10b981; border-radius: 12px; padding: 12px 15px; background-color: #f0fdf415;"
                                               value="{{ number_format($room->final_price ?? $room->price_per_night, 2) }}" 
                                               readonly>
                                        <small class="text-muted d-block mt-2">Guest price</small>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <div class="w-100">
                                            <div style="background: linear-gradient(135deg, #059e0b 0%, #10b981 100%); color: white; padding: 15px; border-radius: 12px; text-align: center; font-weight: 700;">
                                                💰 <span id="your_earning">{{ number_format($room->base_price ?? $room->price_per_night, 2) }}</span> Nu.
                                            </div>
                                            <small class="text-muted d-block mt-2" style="text-align: center;">Your earning</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #8b5cf615 0%, #6366f115 100%); border-left: 4px solid #8b5cf6; padding: 20px; border-radius: 15px;">
                                <label class="form-label fw-bold" style="color: #8b5cf6; font-size: 1.1rem;">
                                    <i class="fas fa-file-alt me-2"></i>Description
                                </label>
                                <textarea name="description" 
                                          class="form-control" 
                                          style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 15px; font-size: 1rem; resize: vertical; min-height: 120px;"
                                          rows="4" 
                                          placeholder="Enter room description, features, view, amenities...">{{ old('description', $room->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Max 1000 characters</small>
                            </div>
                        </div>

                        <!-- Amenities Section -->
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #06b6d415 0%, #0891b215 100%); border-left: 4px solid #06b6d4; padding: 20px; border-radius: 15px;">
                                <label class="form-label fw-bold" style="color: #06b6d4; font-size: 1.1rem;">
                                    <i class="fas fa-list-check me-2"></i>Amenities
                                </label>
                                <input type="text" 
                                       name="amenities" 
                                       class="form-control form-control-lg"
                                       style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px;"
                                       placeholder="e.g., WiFi, TV, AC, Mini Bar, Balcony, Bathtub" 
                                       value="{{ old('amenities', is_array($room->amenities) ? implode(', ', $room->amenities) : '') }}">
                                @error('amenities')
                                    <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Comma-separated list</small>
                            </div>
                        </div>

                        <!-- Room Status Section -->
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #ec4899 15 0%, #f43f5e15 100%); border-left: 4px solid #ec4899; padding: 20px; border-radius: 15px;">
                                <h6 style="color: #ec4899; font-weight: 700; margin-bottom: 20px;"><i class="fas fa-cog me-2"></i>Status & Availability</h6>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" style="color: #ec4899;">
                                            <i class="fas fa-traffic-light me-2"></i>Room Status
                                        </label>
                                        <select name="status" 
                                                class="form-select form-select-lg"
                                                style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px;">
                                            <option value="AVAILABLE" {{ old('status', $room->status) == 'AVAILABLE' ? 'selected' : '' }}>✅ Available</option>
                                            <option value="OCCUPIED" {{ old('status', $room->status) == 'OCCUPIED' ? 'selected' : '' }}>🚫 Occupied</option>
                                            <option value="MAINTENANCE" {{ old('status', $room->status) == 'MAINTENANCE' ? 'selected' : '' }}>⚙️ Maintenance</option>
                                        </select>
                                        @error('status')
                                            <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Current operational status</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" style="color: #ec4899;">
                                            <i class="fas fa-unlock-alt me-2"></i>Booking Availability
                                        </label>
                                        <select name="is_available" 
                                                class="form-select form-select-lg"
                                                style="border: 2px solid #e0e6ed; border-radius: 12px; padding: 12px 15px;">
                                            <option value="1" {{ old('is_available', $room->is_available) == 1 ? 'selected' : '' }}>🔓 Open for Booking</option>
                                            <option value="0" {{ old('is_available', $room->is_available) == 0 ? 'selected' : '' }}>🔒 Closed for Booking</option>
                                        </select>
                                        @error('is_available')
                                            <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted d-block mt-2">Enable guest bookings</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Photos Section -->
                        @if($room->photos && count($room->photos) > 0)
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #a78bfa15 0%, #c4b5fd15 100%); border-left: 4px solid #a78bfa; padding: 20px; border-radius: 15px;">
                                <h6 style="color: #a78bfa; font-weight: 700; margin-bottom: 20px;"><i class="fas fa-images me-2"></i>Current Photos</h6>
                                <div class="row g-3">
                                    @foreach($room->photos as $index => $photo)
                                    <div class="col-md-4" id="photo-{{ $index }}">
                                        <div class="position-relative" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(167, 139, 250, 0.2);">
                                            <img src="{{ asset('storage/' . $photo) }}" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover; display: block;">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- New Photos Upload Section -->
                        <div class="mb-5">
                            <div style="background: linear-gradient(135deg, #fbbf2415 0%, #fcd34d15 100%); border-left: 4px solid #fbbf24; padding: 20px; border-radius: 15px;">
                                <label class="form-label fw-bold" style="color: #f59e0b; font-size: 1.1rem;">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>Add Room Photos
                                </label>
                                <div class="input-group input-group-lg" style="border: 2px dashed #f59e0b; border-radius: 12px; padding: 20px; background-color: #fffbeb;">
                                    <input type="file" 
                                           name="photos[]" 
                                           class="form-control" 
                                           style="border: none; padding: 0;"
                                           accept="image/jpeg,image/png,image/gif" 
                                           multiple>
                                </div>
                                @error('photos.*')
                                    <div class="text-danger small mt-2"><i class="fas fa-times-circle me-1"></i>{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>JPG, PNG or GIF - Max 2MB each - Multiple files allowed</small>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4" style="border-top: 2px dashed #e5e7eb;">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; font-weight: 700; padding: 12px 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;" 
                                    onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.5)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)';">
                                <i class="fas fa-check-circle me-2"></i>Update Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Delete Photo Confirmation Modal -->
<!-- Removed -->

<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 2.5rem;
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.35);
        animation: slideInDown 0.5s ease-out;
    }

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

    .dashboard-header h2 {
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        font-size: 2rem;
    }

    .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        animation: slideInUp 0.5s ease-out;
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

    .card-header {
        border: none;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .card-body {
        padding: 3rem 2.5rem;
    }

    .form-label {
        font-size: 1rem;
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
    }

    .form-control,
    .form-select {
        border: 2px solid #e0e6ed;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: white;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
        background-color: white;
    }

    .form-control-lg,
    .form-select-lg {
        padding: 0.875rem 1.25rem;
        font-size: 1.05rem;
        height: auto;
        min-height: 50px;
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        font-weight: 700;
        padding: 10px 30px;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    .btn-light {
        background: white;
        border: 2px solid white;
        color: #667eea;
        font-weight: 700;
        transition: all 0.3s ease;
        padding: 10px 20px;
        border-radius: 12px;
    }

    .btn-light:hover {
        background: transparent;
        border-color: white;
        color: white;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        border: none;
        color: white;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        color: white;
    }

    .alert {
        border-radius: 15px;
        border: none;
        animation: slideInLeft 0.3s ease-out;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        font-weight: 600;
        display: block;
        color: #dc3545;
    }

    small.text-muted {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

    hr {
        border-top: 2px dashed #e9ecef;
        opacity: 1;
        margin: 2rem 0;
    }

    img.img-fluid {
        max-height: 200px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.3s ease;
    }

    img.img-fluid:hover {
        transform: scale(1.05);
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
