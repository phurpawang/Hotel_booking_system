@extends('owner.layouts.app')

@section('title', 'Property Settings')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Property Settings</h2>
        <p class="text-gray-600 text-sm">Manage your hotel information and settings</p>
    </div>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
</div>
@endif

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
    <form action="{{ route('owner.property.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Hotel Information Section -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Hotel Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hotel_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Hotel Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="hotel_name" id="hotel_name" value="{{ old('hotel_name', $hotel->hotel_name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Person <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $hotel->contact_person) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $hotel->contact_email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $hotel->contact_phone) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" id="address" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('address', $hotel->address) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Hotel Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $hotel->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Check-in/Check-out Times -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Check-in & Check-out</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="check_in_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Check-in Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="check_in_time" id="check_in_time" value="{{ old('check_in_time', $hotel->check_in_time) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="check_out_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Check-out Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="check_out_time" id="check_out_time" value="{{ old('check_out_time', $hotel->check_out_time) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Policies -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Policies</h3>
            <div>
                <label for="cancellation_policy" class="block text-sm font-medium text-gray-700 mb-2">
                    Cancellation Policy
                </label>
                <textarea name="cancellation_policy" id="cancellation_policy" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('cancellation_policy', $hotel->cancellation_policy) }}</textarea>
            </div>
        </div>

        <!-- Property Image -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Property Image</h3>
            
            @if($hotel->image_path)
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                <img src="{{ asset('storage/' . $hotel->image_path) }}" alt="Property" 
                    class="w-48 h-32 object-cover rounded-lg border border-gray-200">
            </div>
            @endif

            <div>
                <label for="property_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Upload New Image
                </label>
                <input type="file" name="property_image" id="property_image" accept="image/jpeg,image/jpg,image/png"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</p>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fas fa-save mr-2"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
