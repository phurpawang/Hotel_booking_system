<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Login - BHBS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">
    <div class="container mx-auto max-w-2xl">
        <!-- Go Back Button -->
        <a href="{{ route('home') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Go Back
        </a>

        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                @foreach($errors->all() as $error)
                    <p class="text-sm"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">Hotel / Reception Login</h1>
                <p class="text-gray-500 text-lg">Access your dashboard to manage rooms, bookings, and payments.</p>
            </div>

            <form action="{{ route('hotel.login.submit') }}" method="POST" class="space-y-6" autocomplete="off">
                @csrf

                <div>
                    <label for="hotel_id" class="block text-gray-700 font-semibold mb-2 text-lg">Hotel ID</label>
                    <input 
                        type="text" 
                        id="hotel_id"
                        name="hotel_id" 
                        required 
                        value="{{ old('hotel_id') }}" 
                        autocomplete="off"
                        class="w-full px-4 py-4 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg uppercase transition"
                    >
                </div>

                <div>
                    <label for="pin" class="block text-gray-700 font-semibold mb-2 text-lg">PIN</label>
                    <input 
                        type="password" 
                        id="pin"
                        name="pin" 
                        required 
                        maxlength="4" 
                        pattern="[0-9]{4}" 
                        autocomplete="off"
                        class="w-full px-4 py-4 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg transition"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold px-6 py-4 rounded-lg text-xl transition shadow-md"
                >
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Didn't get your Hotel ID? 
                    <a href="{{ route('hotel.check-status') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">
                        Check approval status
                    </a>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
