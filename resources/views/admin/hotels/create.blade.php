@extends('admin.layout')

@section('title', 'Add New Hotel')

@section('content')
<div class="dashboard-header">
    <h1>Add New Hotel</h1>
    <a href="{{ route('admin.hotels.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Hotels
    </a>
</div>

<div class="dashboard-card">
    <div class="card-body">
        <form action="{{ route('admin.hotels.store') }}" method="POST" class="admin-form">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label for="owner_id">Hotel Owner <span class="required">*</span></label>
                    <select name="owner_id" id="owner_id" class="form-control" required>
                        <option value="">Select Owner</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('owner_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('owner_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Hotel Name <span class="required">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="property_type">Property Type <span class="required">*</span></label>
                    <select name="property_type" id="property_type" class="form-control" required>
                        <option value="">Select Type</option>
                        <option value="HOTEL" {{ old('property_type') == 'HOTEL' ? 'selected' : '' }}>Hotel</option>
                        <option value="GUEST_HOUSE" {{ old('property_type') == 'GUEST_HOUSE' ? 'selected' : '' }}>Guest House</option>
                        <option value="RESORT" {{ old('property_type') == 'RESORT' ? 'selected' : '' }}>Resort</option>
                        <option value="HOMESTAY" {{ old('property_type') == 'HOMESTAY' ? 'selected' : '' }}>Homestay</option>
                    </select>
                    @error('property_type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="dzongkhag_id">Dzongkhag <span class="required">*</span></label>
                    <select name="dzongkhag_id" id="dzongkhag_id" class="form-control" required>
                        <option value="">Select Dzongkhag</option>
                        @foreach($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag_id') == $dzongkhag->id ? 'selected' : '' }}>
                                {{ $dzongkhag->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('dzongkhag_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone <span class="required">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="star_rating">Star Rating</label>
                    <select name="star_rating" id="star_rating" class="form-control">
                        <option value="">Select Rating</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('star_rating') == $i ? 'selected' : '' }}>{{ $i }} Star</option>
                        @endfor
                    </select>
                    @error('star_rating')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="APPROVED" {{ old('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                        <option value="REJECTED" {{ old('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="address">Address <span class="required">*</span></label>
                <textarea name="address" id="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                @error('address')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Create Hotel
                </button>
                <a href="{{ route('admin.hotels.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.admin-form {
    max-width: 900px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #003580;
}

textarea.form-control {
    resize: vertical;
}

.error-message {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: block;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-secondary {
    padding: 10px 20px;
    background: #6c757d;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush
@endsection
