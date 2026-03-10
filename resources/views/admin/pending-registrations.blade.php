<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Hotel Registrations - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-6">
                <h1 class="text-2xl font-bold">BHBS Admin</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block px-6 py-3 hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                </a>
                <a href="{{ route('admin.pending-registrations') }}" class="block px-6 py-3 bg-blue-600 border-l-4 border-blue-400">
                    <i class="fas fa-clock mr-3"></i> Pending Registrations
                </a>
                <a href="{{ route('admin.hotels') }}" class="block px-6 py-3 hover:bg-gray-700">
                    <i class="fas fa-hotel mr-3"></i> All Hotels
                </a>
                <a href="{{ route('admin.pending-deregistrations') }}" class="block px-6 py-3 hover:bg-gray-700">
                    <i class="fas fa-ban mr-3"></i> Deregistration Requests
                </a>
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-6 py-3 hover:bg-gray-700 text-red-400">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm">
                <div class="px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Pending Hotel Registrations</h2>
                    <p class="text-gray-600">Review and approve hotel registration requests</p>
                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
                @endif

                @if($pendingHotels->count() > 0)
                <div class="space-y-6">
                    @foreach($pendingHotels as $hotel)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $hotel->name }}</h3>
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    Bhutan
                                </p>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">
                                Pending Review
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Property Details -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Property Details</h4>
                                <dl class="space-y-2 text-sm">
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Type:</dt>
                                        <dd class="text-gray-800 font-medium">{{ $hotel->property_type }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Star Rating:</dt>
                                        <dd class="text-gray-800">
                                            @if($hotel->star_rating)
                                                @for($i = 0; $i < $hotel->star_rating; $i++)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @endfor
                                            @else
                                                Not provided
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Phone:</dt>
                                        <dd class="text-gray-800">{{ $hotel->phone }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Email:</dt>
                                        <dd class="text-gray-800">{{ $hotel->email }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Owner Details -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Owner/Manager Details</h4>
                                <dl class="space-y-2 text-sm">
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Name:</dt>
                                        <dd class="text-gray-800 font-medium">{{ $hotel->owner->name }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Role:</dt>
                                        <dd class="text-gray-800">{{ $hotel->owner->role }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Email:</dt>
                                        <dd class="text-gray-800">{{ $hotel->owner->email }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Mobile:</dt>
                                        <dd class="text-gray-800">{{ $hotel->owner->mobile }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Tourism License -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Tourism License</h4>
                                <dl class="space-y-2 text-sm">
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">License No:</dt>
                                        <dd class="text-gray-800 font-medium">{{ $hotel->tourism_license_number }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Authority:</dt>
                                        <dd class="text-gray-800">{{ $hotel->issuing_authority }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Issue Date:</dt>
                                        <dd class="text-gray-800">{{ $hotel->license_issue_date->format('M d, Y') }}</dd>
                                    </div>
                                    <div class="flex">
                                        <dt class="text-gray-600 w-32">Expiry Date:</dt>
                                        <dd class="text-gray-800 {{ $hotel->license_expiry_date < now() ? 'text-red-600 font-bold' : '' }}">
                                            {{ $hotel->license_expiry_date->format('M d, Y') }}
                                            @if($hotel->license_expiry_date < now())
                                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs ml-2">EXPIRED</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Documents -->
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-3">Uploaded Documents</h4>
                                <div class="space-y-2">
                                    @if($hotel->license_document)
                                    <a href="{{ asset('storage/' . $hotel->license_document) }}" target="_blank" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        <span>License Document</span>
                                        <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                                    </a>
                                    @endif
                                    @if($hotel->ownership_document)
                                    <a href="{{ asset('storage/' . $hotel->ownership_document) }}" target="_blank" class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        <span>Ownership Document</span>
                                        <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4 pt-4 border-t">
                            <form method="POST" action="{{ route('admin.hotel.approve', $hotel->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                                    <i class="fas fa-check mr-2"></i> Approve Hotel
                                </button>
                            </form>

                            <button onclick="showRejectModal({{ $hotel->id }})" class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-semibold">
                                <i class="fas fa-times mr-2"></i> Reject Registration
                            </button>

                            <a href="{{ route('admin.hotel.details', $hotel->id) }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition font-semibold">
                                <i class="fas fa-eye mr-2"></i> View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $pendingHotels->links() }}
                </div>
                @else
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Pending Registrations</h3>
                    <p class="text-gray-600">All hotel registrations have been reviewed.</p>
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Reject Hotel Registration</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Rejection Reason *</label>
                    <textarea name="rejection_reason" required rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" placeholder="Please provide a detailed reason for rejection..."></textarea>
                </div>
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-semibold">
                        Confirm Rejection
                    </button>
                    <button type="button" onclick="hideRejectModal()" class="flex-1 bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 transition font-semibold">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(hotelId) {
            document.getElementById('rejectForm').action = `/admin/hotel/${hotelId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</body>
</html>
