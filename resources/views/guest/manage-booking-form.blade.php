<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Booking - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 45px; width: 45px; border-radius: 50%; object-fit: cover;">
                    <h1 class="text-2xl font-bold">Manage Your Booking</h1>
                </div>
                <a href="{{ route('home') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- Reusable Search Bar -->
    @include('components.search-bar', [
        'dzongkhags' => \App\Models\Dzongkhag::all(),
        'sticky' => true,
        'check_in' => request('check_in'),
        'check_out' => request('check_out'),
        'adults' => request('adults', 1),
        'children' => request('children', 0),
        'rooms' => request('rooms', 1),
        'dzongkhag_id' => request('dzongkhag_id')
    ])

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <i class="fas fa-search text-blue-600 text-5xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800">Find Your Booking</h2>
                <p class="text-gray-600 mt-2">Enter your Booking ID and Email or Mobile number</p>
            </div>

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            <form action="{{ route('guest.booking.view') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-ticket-alt text-blue-600 mr-1"></i> Booking ID *
                        </label>
                        <input type="text" name="booking_id" required placeholder="e.g., BK12ABC45XYZ" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase">
                        <p class="text-sm text-gray-600 mt-1">You received this in your confirmation email/SMS</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-envelope text-blue-600 mr-1"></i> Email or Mobile Number *
                        </label>
                        <input type="text" name="identifier" required placeholder="Email or Phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 transition shadow-lg">
                        <i class="fas fa-search mr-2"></i> View Booking
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="font-semibold text-gray-800 mb-3">What can you do?</h3>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li><i class="fas fa-check text-green-500 mr-2"></i> View booking details</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i> View hotel contact information</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i> Cancel booking (subject to policy)</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i> View past bookings</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
