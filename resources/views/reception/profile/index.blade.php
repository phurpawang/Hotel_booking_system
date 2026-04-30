<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .button-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .button-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input input:focus,
        .form-input textarea:focus {
            border-color: #7c3aed !important;
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1) !important;
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
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'profile'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="header-gradient shadow-xl">
                <div class="px-8 py-10">
                    <div>
                        <h2 class="text-4xl font-bold text-white flex items-center gap-3">
                            <i class="fas fa-user-circle"></i>My Profile
                        </h2>
                        <p class="text-purple-100 text-sm mt-2">Manage your account information and settings</p>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-gradient-to-r from-green-400 to-green-600 border-0 text-white px-6 py-4 rounded-xl mb-6 shadow-lg flex items-center max-w-4xl mx-auto">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-gradient-to-r from-red-400 to-red-600 border-0 text-white px-6 py-4 rounded-xl mb-6 shadow-lg flex items-center max-w-4xl mx-auto">
                    <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                    {{ session('error') }}
                </div>
                @endif

                <div class="max-w-4xl mx-auto space-y-8">
                    <!-- Profile Information -->
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="card-header px-10 py-8">
                            <h3 class="text-2xl font-bold flex items-center gap-3">
                                <i class="fas fa-address-card"></i>Profile Information
                            </h3>
                            <p class="text-purple-100 text-sm mt-2">Update your personal details</p>
                        </div>

                        <!-- Card Content -->
                        <div class="p-10">
                            <form method="POST" action="{{ route('reception.profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                    <!-- Name -->
                                    <div class="field-group">
                                        <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="fas fa-user text-purple-600"></i>Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                            placeholder="Enter your full name"
                                            class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="field-group">
                                        <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="fas fa-envelope text-blue-600"></i>Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                            placeholder="your.email@example.com"
                                            class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                    <!-- Phone -->
                                    <div class="field-group">
                                        <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="fas fa-phone text-green-600"></i>Phone Number
                                        </label>
                                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                            placeholder="Enter your phone number"
                                            class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                        @error('phone')
                                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Role (read-only) -->
                                    <div class="field-group">
                                        <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="fas fa-badge text-orange-600"></i>Role
                                        </label>
                                        <div class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gradient-to-r from-orange-50 to-yellow-50 text-gray-600 font-semibold flex items-center gap-2">
                                            <i class="fas fa-check-circle text-orange-600"></i>{{ Auth::user()->role }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="field-group mb-8">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-red-600"></i>Address
                                    </label>
                                    <textarea name="address" rows="4" placeholder="Enter your address..."
                                        class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">{{ old('address', Auth::user()->address) }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <button type="submit" class="button-gradient text-white font-bold px-10 py-4 rounded-xl transition shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-2">
                                    <i class="fas fa-save"></i>Update Profile
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="card-header px-10 py-8">
                            <h3 class="text-2xl font-bold flex items-center gap-3">
                                <i class="fas fa-lock"></i>Change Password
                            </h3>
                            <p class="text-purple-100 text-sm mt-2">Update your password to keep your account secure</p>
                        </div>

                        <!-- Card Content -->
                        <div class="p-10">
                            <form method="POST" action="{{ route('reception.profile.password') }}">
                                @csrf

                                <!-- Current Password -->
                                <div class="field-group mb-8">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-key text-indigo-600"></i>Current Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="current_password" required
                                        placeholder="Enter your current password"
                                        class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                    @error('current_password')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                    <!-- New Password -->
                                    <div class="field-group">
                                        <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="fas fa-shield-alt text-cyan-600"></i>New Password <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" name="password" required
                                            placeholder="Enter your new password"
                                            class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                        @error('password')
                                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="field-group">
                                        <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="fas fa-check-shield text-lime-600"></i>Confirm New Password <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" name="password_confirmation" required
                                            placeholder="Confirm your new password"
                                            class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-lime-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-xl p-4 mb-8">
                                    <p class="text-sm text-blue-800 flex items-center gap-2">
                                        <i class="fas fa-info-circle"></i>
                                        <span><strong>Password Requirements:</strong> Must be at least 8 characters long</span>
                                    </p>
                                </div>

                                <button type="submit" class="button-gradient text-white font-bold px-10 py-4 rounded-xl transition shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center gap-2">
                                    <i class="fas fa-lock"></i>Change Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
