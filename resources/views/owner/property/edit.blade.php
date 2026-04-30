@extends('owner.layouts.app')

@section('title', 'Property Settings')

@section('content')
<!-- Property Settings Container -->
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-cog" style="font-size: 2.5rem; opacity: 0.95;"></i>
            <div>
                <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Property Settings</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage your hotel information and settings</p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
    </div>
    @endif

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

    <!-- Settings Form -->
    <form action="{{ route('owner.property.update') }}" method="POST" enctype="multipart/form-data" style="animation: slideInUp 0.5s ease-out;">
        @csrf

        <!-- Hotel Information Section -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 2rem; border: 1px solid #f0f0f0;">
            <!-- Section Header -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-building mr-2"></i>Hotel Information</h3>
            </div>

            <!-- Section Content -->
            <div style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Hotel Name -->
                    <div>
                        <label for="hotel_name" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                            Hotel Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="hotel_name" id="hotel_name" value="{{ old('hotel_name', $hotel->hotel_name) }}" required
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('hotel_name')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                            Contact Person <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $hotel->contact_person) }}" required
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('contact_person')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Email -->
                    <div>
                        <label for="contact_email" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                            Contact Email <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $hotel->contact_email) }}" required
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('contact_email')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="contact_phone" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                            Contact Phone <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $hotel->contact_phone) }}" required
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('contact_phone')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div style="margin-top: 1.5rem;">
                    <label for="address" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                        Address <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea name="address" id="address" rows="3" required
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit; resize: vertical;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">{{ old('address', $hotel->address) }}</textarea>
                    @error('address')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Map Location -->
                <div style="margin-top: 1.5rem;">
                    <label for="pin_location" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                        Map Location (Google Maps URL or Coordinates)
                    </label>
                    <input type="text" name="pin_location" id="pin_location" value="{{ old('pin_location', $hotel->pin_location) }}"
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                        placeholder="e.g., Google Maps URL or 27.3081,89.6007">
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;"><i class="fas fa-info-circle mr-1" style="color: #667eea;"></i>You can paste Google Maps URL or enter coordinates as latitude,longitude</p>
                    @error('pin_location')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hotel Description -->
                <div style="margin-top: 1.5rem;">
                    <label for="description" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                        Hotel Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit; resize: vertical;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">{{ old('description', $hotel->description) }}</textarea>
                    @error('description')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Check-in/Check-out Section -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 2rem; border: 1px solid #f0f0f0;">
            <!-- Section Header -->
            <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-clock mr-2"></i>Check-in & Check-out</h3>
            </div>

            <!-- Section Content -->
            <div style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <!-- Check-in Time -->
                    <div>
                        <label for="check_in_time" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                            Check-in Time <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="time" name="check_in_time" id="check_in_time" value="{{ old('check_in_time', $hotel->check_in_time) }}" required
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#11998e'; this.style.boxShadow='0 0 0 3px rgba(17, 153, 142, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('check_in_time')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Check-out Time -->
                    <div>
                        <label for="check_out_time" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                            Check-out Time <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="time" name="check_out_time" id="check_out_time" value="{{ old('check_out_time', $hotel->check_out_time) }}" required
                            style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#11998e'; this.style.boxShadow='0 0 0 3px rgba(17, 153, 142, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('check_out_time')
                            <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Policies Section -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 2rem; border: 1px solid #f0f0f0;">
            <!-- Section Header -->
            <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-file-alt mr-2"></i>Policies</h3>
            </div>

            <!-- Section Content -->
            <div style="padding: 2rem;">
                <label for="cancellation_policy" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                    Cancellation Policy
                </label>
                <textarea name="cancellation_policy" id="cancellation_policy" rows="4"
                    style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit; resize: vertical;" 
                    onfocus="this.style.borderColor='#f97316'; this.style.boxShadow='0 0 0 3px rgba(249, 115, 22, 0.1)'"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">{{ old('cancellation_policy', $hotel->cancellation_policy) }}</textarea>
                @error('cancellation_policy')
                    <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Property Image Section -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 2rem; border: 1px solid #f0f0f0;">
            <!-- Section Header -->
            <div style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); color: white; padding: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-image mr-2"></i>Property Image</h3>
            </div>

            <!-- Section Content -->
            <div style="padding: 2rem;">
                @if($hotel->image_path)
                <div style="margin-bottom: 2rem;">
                    <p style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;"><i class="fas fa-check-circle mr-1" style="color: #10b981;"></i>Current Image:</p>
                    <img src="{{ asset('storage/' . $hotel->image_path) }}" alt="Property" 
                        style="width: 100%; max-width: 300px; height: auto; object-fit: cover; border-radius: 12px; border: 2px solid #e5e7eb; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                </div>
                @endif

                <label for="property_image" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem; display: block;">
                    Upload New Image
                </label>
                <div style="position: relative; border: 2px dashed #e5e7eb; border-radius: 8px; padding: 2rem; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.borderColor='#667eea'; this.style.backgroundColor='#f9f9ff'" onmouseout="this.style.borderColor='#e5e7eb'; this.style.backgroundColor='transparent'" id="dropZone">
                    <input type="file" name="property_image" id="property_image" accept="image/jpeg,image/jpg,image/png"
                        style="width: 100%; height: 100%; position: absolute; top: 0; left: 0; opacity: 0; cursor: pointer;">
                    <div style="pointer-events: none;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem; color: #667eea;"><i class="fas fa-cloud-upload-alt"></i></div>
                        <p style="margin: 0 0 0.5rem 0; color: #333; font-weight: 600;">Click to upload or drag and drop</p>
                        <p style="margin: 0; color: #666; font-size: 0.9rem;">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</p>
                    </div>
                </div>
                @error('property_image')
                    <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; display: block;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.3)'">
                <i class="fas fa-save"></i>Save Changes
            </button>
        </div>
    </form>
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
@endsection
