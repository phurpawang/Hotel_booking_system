<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Staff - Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Same as index -->
        <aside class="w-64 bg-gradient-to-b from-blue-900 to-blue-700 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-blue-800">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center space-x-3">
                    <i class="fas fa-building text-3xl"></i>
                    <span class="text-xl font-bold">BHBS</span>
                </a>
            </div>
            
            <nav class="p-4 overflow-y-auto max-h-[calc(100vh-140px)]">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.staff.index') }}" class="flex items-center px-4 py-3 bg-blue-800 rounded-lg mb-2">
                    <i class="fas fa-user-tie mr-3"></i>
                    <span>Staff Management</span>
                </a>
                <!-- Add other menu items as needed -->
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4">
                    <div class="flex items-center mb-2">
                        <a href="{{ route('owner.staff.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="text-2xl font-bold text-gray-800">Add New Staff Member</h2>
                    </div>
                    <p class="text-gray-600 text-sm">Create a new staff account for your hotel</p>
                </div>
            </header>

            <!-- Form -->
            <main class="p-8">
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <p class="font-bold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <form action="{{ route('owner.staff.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Mobile -->
                            <div>
                                <label for="mobile" class="block text-sm font-medium text-gray-700 mb-2">
                                    Mobile Number
                                </label>
                                <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select Role</option>
                                    <option value="MANAGER" {{ old('role') == 'MANAGER' ? 'selected' : '' }}>Manager</option>
                                    <option value="RECEPTION" {{ old('role') == 'RECEPTION' ? 'selected' : '' }}>Reception</option>
                                </select>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('owner.staff.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>
                                Add Staff Member
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
