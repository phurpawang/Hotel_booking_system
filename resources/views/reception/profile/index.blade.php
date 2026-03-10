<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'profile'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
                    <p class="text-gray-600 text-sm">Manage your account information and settings</p>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
                @endif

                <div class="max-w-4xl mx-auto">
                    <!-- Profile Photo Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Profile Photo</h3>
                        
                        <div class="flex items-center gap-6">
                            <!-- Current Photo -->
                            <div class="flex-shrink-0">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                        alt="Profile Photo" 
                                        class="w-24 h-24 rounded-full object-cover border-4 border-purple-200">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-purple-200 flex items-center justify-center text-purple-700 text-3xl font-bold border-4 border-purple-300">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Upload Form -->
                            <div class="flex-1">
                                <form method="POST" action="{{ route('reception.profile.photo') }}" enctype="multipart/form-data" class="flex items-center gap-4">
                                    @csrf
                                    <input type="file" name="profile_photo" accept="image/jpeg,image/jpg,image/png" 
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                                        Upload
                                    </button>
                                </form>
                                @error('profile_photo')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-2">JPG, JPEG or PNG. Max size 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Profile Information</h3>
                        
                        <form method="POST" action="{{ route('reception.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Role (read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <input type="text" value="{{ Auth::user()->role }}" disabled
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea name="address" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('address', Auth::user()->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg transition">
                                <i class="fas fa-save mr-2"></i>Update Profile
                            </button>
                        </form>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Change Password</h3>
                        
                        <form method="POST" action="{{ route('reception.profile.password') }}">
                            @csrf

                            <!-- Current Password -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                                <input type="password" name="current_password" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- New Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                                    <input type="password" name="password" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                Password must be at least 8 characters long
                            </p>

                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg transition">
                                <i class="fas fa-key mr-2"></i>Change Password
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
