<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Status - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Back Button -->
            <a href="{{ route('hotel.check-status') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold mb-6">
                <i class="fas fa-arrow-left mr-2"></i> Back to Check Status
            </a>

            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <!-- Status Badge - Always Visible -->
                <div class="text-center mb-6">
                    <!-- Application Status -->
                    <div class="mb-4">
                        <span class="text-sm font-semibold text-gray-600 uppercase">Application Status</span>
                    </div>
                    
                    @if(strtolower($hotel->status) === 'approved')
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                            <i class="fas fa-check-circle text-green-600 text-5xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-green-600 mb-2">✓ APPROVED</h1>
                        <p class="text-gray-600">Your hotel registration has been approved</p>
                    @elseif(strtolower($hotel->status) === 'pending')
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                            <i class="fas fa-clock text-yellow-600 text-5xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-yellow-600 mb-2">⏳ PENDING REVIEW</h1>
                        <p class="text-gray-600">Your application is under review by our admin team</p>
                    @elseif(strtolower($hotel->status) === 'rejected')
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                            <i class="fas fa-times-circle text-red-600 text-5xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-red-600 mb-2">✗ REJECTED</h1>
                        <p class="text-gray-600">Your application was not approved</p>
                    @else
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                            <i class="fas fa-question-circle text-gray-600 text-5xl"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-600 mb-2">{{ strtoupper($hotel->status) }}</h1>
                        <p class="text-gray-600">Current status</p>
                    @endif
                </div>

                <!-- Hotel ID Display - Always Visible -->
                @if($hotel->hotel_id)
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 border-2 border-purple-200 rounded-lg p-6 mb-6">
                    <div class="text-center">
                        <p class="text-sm font-semibold text-gray-600 mb-2">YOUR HOTEL ID</p>
                        <p class="text-4xl font-bold text-purple-600 mb-2 tracking-wider">{{ $hotel->hotel_id }}</p>
                        <p class="text-sm text-gray-600">Use this ID to login</p>
                    </div>
                </div>
                @endif

                <!-- Hotel Details -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Hotel Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Hotel Name</p>
                            <p class="font-semibold text-gray-900">{{ $hotel->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Property Type</p>
                            <p class="font-semibold text-gray-900">{{ $hotel->property_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email</p>
                            <p class="font-semibold text-gray-900">{{ $hotel->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Mobile</p>
                            <p class="font-semibold text-gray-900">{{ $hotel->mobile }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600 mb-1">Tourism License Number</p>
                            <p class="font-semibold text-gray-900">{{ $hotel->tourism_license_number }}</p>
                        </div>
                    </div>
                </div>

                @if(strtolower($hotel->status) === 'approved' && $user)
                    <!-- Credentials Information (Only for Approved) -->
                    <div class="bg-green-50 border border-green-300 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-key text-green-600 mr-2"></i>
                            Your Login Credentials
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Hotel ID</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $hotel->hotel_id ?? 'Not assigned yet' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Email</p>
                                <p class="font-semibold text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Password</p>
                                <p class="text-gray-700">Your password was set during registration</p>
                            </div>
                        </div>
                        <div class="mt-4 bg-white border border-green-200 rounded p-4">
                            <p class="text-sm text-gray-700">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Use your <strong>Hotel ID</strong>, <strong>Email</strong>, and <strong>Password</strong> to login to your hotel dashboard.
                            </p>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <div class="text-center">
                        <a href="{{ route('hotel.login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-lg transition shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Go to Login
                        </a>
                    </div>
                @elseif(strtolower($hotel->status) === 'pending')
                    <!-- Pending Message -->
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-6">
                        <p class="text-yellow-800">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            Your application is currently under review. You will receive an email notification with your Hotel ID once approved.
                        </p>
                    </div>
                @elseif(strtolower($hotel->status) === 'rejected')
                    <!-- Rejection Reason -->
                    <div class="bg-red-50 border border-red-300 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Rejection Reason</h3>
                        <p class="text-gray-700">
                            {{ $hotel->rejection_reason ?? 'No specific reason provided. Please contact admin for more details.' }}
                        </p>
                    </div>

                    <!-- Reapply Option -->
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">If you believe this is an error, please contact our support team or submit a new application.</p>
                        <a href="{{ route('hotel.register') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition">
                            Submit New Application
                        </a>
                    </div>
                @endif
            </div>

            <!-- Help Section -->
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-800 mb-3">Need Help?</h3>
                <p class="text-gray-600 text-sm">
                    If you have any questions or concerns, please contact our support team at <a href="mailto:support@bhbs.bt" class="text-blue-600 hover:underline">support@bhbs.bt</a> or call us at +975-2-123456.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
