<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Approval Status - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header with Back to Login Button -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Check Approval Status</h1>
                    <p class="text-gray-600 text-lg">Find your hotel ID if email was missed.</p>
                </div>
                <a href="{{ route('hotel.login') }}" class="bg-white hover:bg-gray-50 text-gray-800 font-semibold px-6 py-3 rounded-lg border border-gray-300 transition shadow-sm">
                    Back to Login
                </a>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800 px-6 py-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Status Check Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form action="{{ route('hotel.check-status.submit') }}" method="POST" id="statusCheckForm">
                    @csrf

                    <!-- Registration Email -->
                    <div class="mb-6">
                        <label class="block text-gray-900 font-semibold mb-3 text-lg">Registration Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            required 
                            value="{{ old('email') }}"
                            autocomplete="off"
                            class="w-full px-5 py-4 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                            placeholder="Enter your registration email"
                        >
                    </div>

                    <!-- Mobile Number -->
                    <div class="mb-6">
                        <label class="block text-gray-900 font-semibold mb-3 text-lg">Mobile Number</label>
                        <input 
                            type="text" 
                            name="mobile" 
                            required 
                            value="{{ old('mobile') }}"
                            class="w-full px-5 py-4 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                            placeholder="Enter your mobile number"
                        >
                    </div>

                    <!-- Tourism License Number -->
                    <div class="mb-8">
                        <label class="block text-gray-900 font-semibold mb-3 text-lg">Tourism License Number</label>
                        <input 
                            type="text" 
                            name="tourism_license_number" 
                            required 
                            value="{{ old('tourism_license_number') }}"
                            class="w-full px-5 py-4 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                            placeholder="Enter your tourism license number"
                        >
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-lg text-lg transition shadow-md"
                        >
                            Check Status
                        </button>
                        <button 
                            type="reset" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-8 py-4 rounded-lg text-lg transition"
                        >
                            Reset
                        </button>
                    </div>
                </form>

                <!-- Help Text -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Enter the details you provided during hotel registration to check your approval status and retrieve your Hotel ID.
                    </p>
                </div>
            </div>

            <!-- Additional Help -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Need to register? 
                    <a href="{{ route('hotel.register') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                        Register your hotel
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
