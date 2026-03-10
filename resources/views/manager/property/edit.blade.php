<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Settings - {{ $hotel->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-green-800">
                <a href="{{ route('manager.dashboard') }}" class="block">
                    <h1 class="text-2xl font-bold hover:text-green-300 transition cursor-pointer">
                        <i class="fas fa-building mr-2"></i>BHBS
                    </h1>
                </a>
                <p class="text-sm text-green-200 mt-1">{{ $hotel->name }}</p>
                <span class="text-xs bg-green-700 px-2 py-1 rounded mt-2 inline-block">Manager</span>
            </div>
            
            <nav class="p-4">
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('manager.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('manager.rooms.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('manager.rates') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates</span>
                </a>
                <a href="{{ route('manager.reports.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('manager.property.edit') }}" class="flex items-center px-4 py-3 bg-green-800 rounded-lg mb-2">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('manager.messages.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('manager.deregistration.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Deregistration</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-user mr-3"></i>
                    <span>Profile</span>
                </a>
                <form method="POST" action="{{ route('hotel.logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 hover:bg-red-800 rounded-lg mb-2 transition w-full text-left">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Property Settings</h2>
                    <p class="text-gray-600">Manage your hotel property information</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <p class="font-semibold">Please correct the following errors:</p>
                    </div>
                    <ul class="list-disc list-inside ml-6">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Property Settings Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <form method="POST" action="{{ route('manager.property.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Hotel Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-hotel mr-2 text-green-600"></i>Hotel Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $hotel->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>Address
                            </label>
                            <textarea name="address" id="address" rows="3" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('address', $hotel->address) }}</textarea>
                        </div>

                        <!-- Dzongkhag -->
                        <div class="mb-6">
                            <label for="dzongkhag_id" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-flag mr-2 text-green-600"></i>Dzongkhag
                            </label>
                            <select name="dzongkhag_id" id="dzongkhag_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select Dzongkhag</option>
                                @foreach($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag_id', $hotel->dzongkhag_id) == $dzongkhag->id ? 'selected' : '' }}>
                                    {{ $dzongkhag->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-green-600"></i>Description
                            </label>
                            <textarea name="description" id="description" rows="5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('description', $hotel->description) }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Describe your property, amenities, and services</p>
                        </div>

                        <!-- Current Property Image -->
                        @if($hotel->property_image)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-image mr-2 text-green-600"></i>Current Property Image
                            </label>
                            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                                <img src="{{ asset('storage/' . $hotel->property_image) }}" alt="Property Image"
                                    class="max-w-md h-48 object-cover rounded-lg shadow-sm">
                            </div>
                        </div>
                        @endif

                        <!-- Upload New Property Image -->
                        <div class="mb-6">
                            <label for="property_image" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-upload mr-2 text-green-600"></i>{{ $hotel->property_image ? 'Update Property Image' : 'Upload Property Image' }}
                            </label>
                            <input type="file" name="property_image" id="property_image" accept="image/jpeg,image/jpg,image/png"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <p class="text-sm text-gray-500 mt-1">Accepted formats: JPG, JPEG, PNG (Max size: 2MB)</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition shadow-md">
                                <i class="fas fa-save mr-2"></i>Update Property Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
