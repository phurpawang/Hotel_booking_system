<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Guest - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-input {
            position: relative;
            transition: all 0.3s ease;
        }
        .form-input input:focus,
        .form-input textarea:focus {
            border-color: #7c3aed !important;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1) !important;
        }
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a78bfa;
            font-size: 18px;
        }
        .textarea-icon {
            position: absolute;
            left: 16px;
            top: 16px;
            color: #a78bfa;
            font-size: 18px;
        }
        .field-group {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .button-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .button-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'guests'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="header-gradient shadow-xl">
                <div class="px-8 py-10">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-4xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-user-plus"></i>Add New Guest
                            </h2>
                            <p class="text-purple-100 text-sm mt-2">Create a new guest profile</p>
                        </div>
                        <a href="{{ route('reception.guests.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg transition flex items-center gap-2 font-semibold">
                            <i class="fas fa-arrow-left"></i>Back to Guests
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-10">
                        <form method="POST" action="{{ route('reception.guests.store') }}">
                            @csrf

                            <!-- Form Title -->
                            <div class="mb-10 pb-6 border-b-2 border-gray-100">
                                <h3 class="text-2xl font-bold text-gray-800">Guest Information</h3>
                                <p class="text-gray-600 text-sm mt-1">Fill in the guest details below</p>
                            </div>

                            <!-- Name -->
                            <div class="field-group mb-8">
                                <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-user text-purple-600"></i>Full Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        placeholder="Enter guest full name"
                                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                    <i class="fas fa-user absolute left-4 top-4 text-purple-600"></i>
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Two Column Layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Email -->
                                <div class="field-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-envelope text-blue-600"></i>Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            placeholder="guest@example.com"
                                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                        <i class="fas fa-envelope absolute left-4 top-4 text-blue-600"></i>
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="field-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-phone text-green-600"></i>Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="phone" value="{{ old('phone') }}" required
                                            placeholder="+1 (555) 123-4567"
                                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                        <i class="fas fa-phone absolute left-4 top-4 text-green-600"></i>
                                    </div>
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="field-group mb-8">
                                <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-orange-600"></i>Address
                                </label>
                                <div class="relative">
                                    <textarea name="address" rows="4" placeholder="Enter guest address..."
                                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">{{ old('address') }}</textarea>
                                    <i class="fas fa-map-marker-alt absolute left-4 top-4 text-orange-600"></i>
                                </div>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Info Note -->
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl p-5 mb-10 shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-blue-900 mb-1">Default Login Credentials</h4>
                                        <p class="text-sm text-blue-800">
                                            Default password will be set to <span class="bg-blue-200 px-2 py-1 rounded font-mono font-bold">guest123</span>. Guest can change it after first login.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                                <button type="submit" class="flex-1 button-gradient text-white font-bold px-6 py-4 rounded-xl transition shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>Add Guest
                                </button>
                                <a href="{{ route('reception.guests.index') }}" 
                                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-6 py-4 rounded-xl transition shadow-lg hover:shadow-xl transform hover:scale-105 text-center flex items-center justify-center gap-2">
                                    <i class="fas fa-times-circle"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
