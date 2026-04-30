@extends('manager.layouts.app')

@section('title', 'Property Settings')

@section('header')
<h1 class="text-2xl font-bold text-gray-800">Property Settings</h1>
<p class="text-gray-600 mt-1">Manage your hotel property information</p>
@endsection

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem 2.5rem; border-radius: 20px; margin-bottom: 2.5rem; box-shadow: 0 10px 40px rgba(102, 126, 234, 0.35); animation: slideInDown 0.5s ease-out;">
        <h2 style="margin: 0 0 0.5rem 0; font-weight: 700; font-size: 2.2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-cog me-3"></i>Property Settings</h2>
        <p style="margin: 0; opacity: 0.95; font-size: 1.05rem;">Manage your hotel property information</p>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    @if($errors->any())
    <div style="background: linear-gradient(135deg, #dc262615 0%, #b91c1c15 100%); border-left: 5px solid #dc2626; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #991b1b; font-weight: 700; margin-bottom: 0.75rem;"><i class="fas fa-exclamation-circle me-2"></i>Please correct the following errors:</div>
        <ul style="margin: 0; padding-left: 1.5rem; color: #991b1b;">
            @foreach($errors->all() as $error)
            <li style="margin-bottom: 0.5rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Property Settings Form -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.6s ease-out;">
        <form method="POST" action="{{ route('manager.property.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="padding: 2.5rem;">
                <!-- Hotel Name -->
                <div style="margin-bottom: 2rem;">
                    <label for="name" style="display: block; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-hotel me-2"></i>Hotel Name
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $hotel->name) }}" required 
                        class="form-input @error('name') form-input-error @enderror">
                    @error('name')
                    <small style="color: #dc2626; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Address -->
                <div style="margin-bottom: 2rem;">
                    <label for="address" style="display: block; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-map-marker-alt me-2"></i>Address
                    </label>
                    <textarea name="address" id="address" rows="3" required 
                        class="form-input @error('address') form-input-error @enderror">{{ old('address', $hotel->address) }}</textarea>
                    @error('address')
                    <small style="color: #dc2626; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Dzongkhag -->
                <div style="margin-bottom: 2rem;">
                    <label for="dzongkhag_id" style="display: block; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-flag me-2"></i>Dzongkhag
                    </label>
                    <select name="dzongkhag_id" id="dzongkhag_id" required 
                        class="form-input @error('dzongkhag_id') form-input-error @enderror">
                        <option value="">Select Dzongkhag</option>
                        @foreach($dzongkhags as $dzongkhag)
                        <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag_id', $hotel->dzongkhag_id) == $dzongkhag->id ? 'selected' : '' }}>
                            {{ $dzongkhag->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('dzongkhag_id')
                    <small style="color: #dc2626; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Map Location -->
                <div style="margin-bottom: 2rem;">
                    <label for="pin_location" style="display: block; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-map-pin me-2"></i>Map Location (Google Maps URL or Coordinates)
                    </label>
                    <input type="text" name="pin_location" id="pin_location" value="{{ old('pin_location', $hotel->pin_location) }}" 
                        class="form-input @error('pin_location') form-input-error @enderror"
                        placeholder="e.g., Google Maps URL or 27.3081,89.6007">
                    <small style="color: #999; display: block; margin-top: 0.5rem;">Paste Google Maps URL or enter coordinates as latitude,longitude</small>
                    @error('pin_location')
                    <small style="color: #dc2626; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Description -->
                <div style="margin-bottom: 2rem;">
                    <label for="description" style="display: block; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-align-left me-2"></i>Description
                    </label>
                    <textarea name="description" id="description" rows="5" 
                        class="form-input @error('description') form-input-error @enderror">{{ old('description', $hotel->description) }}</textarea>
                    <small style="color: #999; display: block; margin-top: 0.5rem;">Describe your property, amenities, and services</small>
                    @error('description')
                    <small style="color: #dc2626; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Current Property Image -->
                @if($hotel->property_image)
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-image me-2"></i>Current Property Image
                    </label>
                    <div style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 1.5rem; background: #f9f9f9;">
                        <img src="{{ asset('storage/' . $hotel->property_image) }}" alt="Property Image"
                            style="max-width: 100%; max-height: 300px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    </div>
                </div>
                @endif

                <!-- Upload New Property Image -->
                <div style="margin-bottom: 2rem;">
                    <label for="property_image" style="display: block; color: #667eea; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.75rem;">
                        <i class="fas fa-upload me-2"></i>{{ $hotel->property_image ? 'Update Property Image' : 'Upload Property Image' }}
                    </label>
                    <input type="file" name="property_image" id="property_image" accept="image/jpeg,image/jpg,image/png" 
                        class="form-input @error('property_image') form-input-error @enderror">
                    <small style="color: #999; display: block; margin-top: 0.5rem;">Accepted formats: JPG, JPEG, PNG (Max size: 2MB)</small>
                    @error('property_image')
                    <small style="color: #dc2626; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div style="display: flex; justify-content: flex-end; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <button type="submit" 
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 2rem; border-radius: 10px; border: none; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.3)'">
                        <i class="fas fa-save"></i>Update Property Settings
                    </button>
                </div>
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

    /* Form input styles */
    .form-input {
        width: 100%;
        background-color: #ffffff;
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-family: inherit;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-input-error {
        border-color: #dc2626 !important;
    }

    .form-input-error:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }
</style>
@endsection
