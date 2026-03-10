<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deregistration Request - {{ $hotel->name }}</title>
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
                <a href="{{ route('manager.property.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('manager.messages.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('manager.deregistration.index') }}" class="flex items-center px-4 py-3 bg-green-800 rounded-lg mb-2">
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
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Deregistration Request</h2>
                    <p class="text-gray-600">Request to close your hotel registration</p>
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

                <!-- Error Message -->
                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <!-- Validation Errors -->
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

                @if($deregistrationRequest && $deregistrationRequest->status == 'PENDING')
                <!-- Existing Pending Request -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-800">Pending Deregistration Request</h3>
                            <p class="text-gray-600 text-sm">Submitted on {{ $deregistrationRequest->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="mb-3">
                            <label class="font-semibold text-gray-700">Reason:</label>
                            <p class="text-gray-600">{{ str_replace('_', ' ', $deregistrationRequest->reason) }}</p>
                        </div>
                        <div>
                            <label class="font-semibold text-gray-700">Details:</label>
                            <p class="text-gray-600">{{ $deregistrationRequest->reason_details }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <form method="POST" action="{{ route('manager.deregistration.cancel', $deregistrationRequest->id) }}" onsubmit="return confirm('Are you sure you want to cancel this deregistration request?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                                <i class="fas fa-times mr-2"></i>Cancel Request
                            </button>
                        </form>
                    </div>
                </div>

                @elseif($deregistrationRequest && $deregistrationRequest->status == 'APPROVED')
                <!-- Approved Request -->
                <div class="bg-green-100 border-l-4 border-green-500 p-6 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-bold text-green-800">Deregistration Request Approved</h3>
                            <p class="text-green-700 mt-1">Your deregistration request has been approved by the administrator.</p>
                        </div>
                    </div>
                </div>

                @else
                <!-- Future Bookings Warning -->
                @if($futureBookingsCount > 0)
                <div class="bg-orange-100 border-l-4 border-orange-500 p-6 rounded mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-bold text-orange-800 mb-2">Cannot Submit Deregistration Request</h3>
                            <p class="text-orange-700">You have <strong>{{ $futureBookingsCount }}</strong> future confirmed booking(s).</p>
                            <p class="text-orange-700 mt-2">Please complete or cancel all future bookings before submitting a deregistration request.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Information Card -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-bold text-blue-800 mb-2">Important Information</h3>
                            <ul class="text-blue-700 space-y-2">
                                <li>• Deregistration requests are reviewed by administrators</li>
                                <li>• You cannot submit a request if you have future confirmed bookings</li>
                                <li>• Once approved, your hotel will be removed from the booking system</li>
                                <li>• You can cancel your request anytime before it's approved</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Deregistration Request Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Submit Deregistration Request</h3>

                    <form method="POST" action="{{ route('manager.deregistration.store') }}">
                        @csrf

                        <!-- Reason Selection -->
                        <div class="mb-6">
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-question-circle mr-2 text-red-600"></i>Reason for Deregistration
                            </label>
                            <select name="reason" id="reason" required {{ $futureBookingsCount > 0 ? 'disabled' : '' }}
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select a reason</option>
                                <option value="BUSINESS_CLOSURE" {{ old('reason') == 'BUSINESS_CLOSURE' ? 'selected' : '' }}>Business Closure</option>
                                <option value="PROPERTY_SOLD" {{ old('reason') == 'PROPERTY_SOLD' ? 'selected' : '' }}>Property Sold</option>
                                <option value="RENOVATION" {{ old('reason') == 'RENOVATION' ? 'selected' : '' }}>Renovation</option>
                                <option value="OTHER" {{ old('reason') == 'OTHER' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Reason Details -->
                        <div class="mb-6">
                            <label for="reason_details" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-red-600"></i>Details
                            </label>
                            <textarea name="reason_details" id="reason_details" rows="5" required {{ $futureBookingsCount > 0 ? 'disabled' : '' }}
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Please provide detailed information about your deregistration request...">{{ old('reason_details') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Minimum 20 characters required</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" {{ $futureBookingsCount > 0 ? 'disabled' : '' }}
                                class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-lg font-semibold hover:from-red-700 hover:to-red-800 transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Deregistration Request
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
