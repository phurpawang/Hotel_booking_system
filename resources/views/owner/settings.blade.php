@extends('owner.layouts.app')

@section('title', 'Property Settings')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Property Settings</h2>
    <p class="text-gray-600 text-sm">Manage your hotel information and preferences</p>
@endsection

@section('content')
    <!-- Hotel Information Card -->
    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4">
            <h3 class="text-lg font-bold">
                <i class="fas fa-building mr-2"></i>Hotel Information
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Hotel Name</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->name }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Hotel ID</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->hotel_id }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Property Type</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->property_type }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Star Rating</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->star_rating }} Star" readonly>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Address</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->address }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Phone</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->phone }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Email</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->email }}" readonly>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Description</label>
                    <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" rows="4" readonly>{{ $hotel->description }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- License Information Card -->
    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-800 text-white p-4">
            <h3 class="text-lg font-bold">
                <i class="fas fa-file-alt mr-2"></i>Tourism License Information
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">License Number</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->tourism_license_number }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Issuing Authority</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ $hotel->issuing_authority }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Issue Date</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ \Carbon\Carbon::parse($hotel->license_issue_date)->format('F d, Y') }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Expiry Date</label>
                    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" value="{{ \Carbon\Carbon::parse($hotel->license_expiry_date)->format('F d, Y') }}" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold text-sm mb-2">Status</label>
                    <div>
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>{{ $hotel->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white p-4">
            <h3 class="text-lg font-bold">
                <i class="fas fa-sliders-h mr-2"></i>Actions
            </h3>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    To update hotel information, please contact the system administrator or use the hotel deregistration process.
                </p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-3">
                <a href="{{ route('owner.deregistration.index') }}" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold text-center flex items-center justify-center">
                    <i class="fas fa-times-circle mr-2"></i>Request Deregistration
                </a>
                <a href="{{ route('owner.dashboard') }}" class="flex-1 px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold text-center flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
@endsection

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }
</style>

@endsection
