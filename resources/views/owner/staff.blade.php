<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - {{ $hotel->name ?? 'BHBS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar (Same as dashboard) -->
        <aside class="w-64 bg-blue-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-blue-800">
                <h1 class="text-2xl font-bold">BHBS</h1>
                <p class="text-sm text-blue-200 mt-1">{{ $hotel->name ?? 'Hotel Name' }}</p>
            </div>
            
            <nav class="p-4">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('owner.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('owner.rooms.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('owner.rates') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates & Availability</span>
                </a>
                <a href="{{ route('owner.reports') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('owner.staff') }}" class="flex items-center px-4 py-3 bg-blue-800 rounded-lg mb-2">
                    <i class="fas fa-users mr-3"></i>
                    <span>Staff Management</span>
                </a>
                <a href="{{ route('owner.settings') }}" class="flex items-center px-4 py-3 hover:bg-blue-800 rounded-lg mb-2 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                
                <div class="border-t border-blue-800 mt-4 pt-4">
                    <form method="POST" action="{{ route('hotel.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center px-4 py-3 hover:bg-red-600 rounded-lg w-full transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Staff Management</h2>
                        <p class="text-gray-600 text-sm">Manage your hotel staff members</p>
                    </div>
                    <button onclick="toggleModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-plus mr-2"></i> Add New Staff
                    </button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Staff List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">Staff Members</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($staff ?? [] as $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold mr-3">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                            <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $member->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if(strtoupper($member->role) == 'MANAGER') bg-purple-100 text-purple-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($member->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $member->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">                                        <button onclick="openEditModal({{ $member->id }}, '{{ $member->name }}', '{{ $member->email }}', '{{ $member->role }}')"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>                                        <form method="POST" action="{{ route('owner.staff.delete', $member->id) }}" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                                        <p>No staff members yet. Add your first staff member above.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div id="staffModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Add New Staff</h3>
                <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="POST" action="{{ route('owner.staff.create') }}" id="addStaffForm">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter staff name">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="email" required value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter email address">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Role *</label>
                    <select name="role" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Role</option>
                        <option value="MANAGER">Manager</option>
                        <option value="RECEPTION">Receptionist</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Password *</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Minimum 8 characters">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Re-enter password">
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-plus mr-2"></i> Add Staff
                    </button>
                    <button type="button" onclick="toggleModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-semibold transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Staff Modal -->
    <div id="editStaffModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Edit Staff Member</h3>
                <button onclick="toggleEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="POST" action="" id="editStaffForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="staff_id" id="edit_staff_id">
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Name *</label>
                    <input type="text" name="name" id="edit_name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter staff name">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="email" id="edit_email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter email address">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Role *</label>
                    <select name="role" id="edit_role" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Role</option>
                        <option value="MANAGER">Manager</option>
                        <option value="RECEPTION">Receptionist</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                    <input type="password" name="password" id="edit_password" minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Leave blank to keep current password">
                    <p class="text-xs text-gray-500 mt-1">Leave blank if you don't want to change the password</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="edit_password_confirmation" minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Re-enter new password">
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-save mr-2"></i> Update Staff
                    </button>
                    <button type="button" onclick="toggleEditModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-semibold transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal() {
            const modal = document.getElementById('staffModal');
            modal.classList.toggle('hidden');
        }

        function toggleEditModal() {
            const modal = document.getElementById('editStaffModal');
            modal.classList.toggle('hidden');
        }

        function openEditModal(id, name, email, role) {
            document.getElementById('edit_staff_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role.toUpperCase();
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            
            // Update form action URL
            document.getElementById('editStaffForm').action = '/owner/staff/' + id + '/update';
            
            toggleEditModal();
        }

        // Close modal when clicking outside
        document.getElementById('staffModal').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleModal();
            }
        });

        document.getElementById('editStaffModal').addEventListener('click', function(e) {
            if (e.target === this) {
                toggleEditModal();
            }
        });

        // Show modal if there are errors (for edit)
        @if($errors->any() && old('staff_id'))
            window.onload = function() {
                openEditModal(
                    '{{ old('staff_id') }}',
                    '{{ old('name') }}',
                    '{{ old('email') }}',
                    '{{ old('role') }}'
                );
            };
        @endif
    </script>
</body>
</html>
