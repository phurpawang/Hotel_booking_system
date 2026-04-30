<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Guest Question - {{ $hotel->name ?? 'Manager' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-900 text-white flex-shrink-0">
            <div class="p-6 border-b border-green-800">
                <a href="{{ route('manager.dashboard') }}" class="flex flex-col items-center justify-center">
                    <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS Logo" style="height: 55px; width: 55px; border-radius: 50%; object-fit: cover; margin-bottom: 0.75rem;">
                    <h1 class="text-lg font-bold text-center">{{ $hotel->name ?? 'Hotel' }}</h1>
                    <span class="text-xs bg-green-700 px-2 py-1 rounded mt-2 inline-block">Manager</span>
                </a>
            </div>
            
            <nav class="p-4">
                <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-line mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('manager.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-calendar-check mr-3"></i>
                    <span>Reservations</span>
                </a>
                <a href="{{ route('manager.rooms.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-bed mr-3"></i>
                    <span>Rooms</span>
                </a>
                <a href="{{ route('manager.rates') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-dollar-sign mr-3"></i>
                    <span>Rates</span>
                </a>
                <a href="{{ route('manager.reports') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="{{ route('manager.property.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Property Settings</span>
                </a>
                <a href="{{ route('manager.inquiries.index') }}" class="flex items-center px-4 py-3 bg-green-800 rounded-lg mb-2">
                    <i class="fas fa-comments mr-3"></i>
                    <span>Guest Questions</span>
                </a>
                <a href="{{ route('manager.messages.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('manager.deregistration.index') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Deregistration</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 hover:bg-green-800 rounded-lg mb-2 transition">
                    <i class="fas fa-user mr-3"></i>
                    <span>Profile</span>
                </a>
                <form method="POST" action="{{ route('hotel.logout') }}" class="mt-4 pt-4 border-t border-green-800">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 hover:bg-red-800 rounded-lg w-full transition">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b flex-shrink-0">
                <div class="px-8 py-3 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/bhbs-logo.png') }}" alt="BHBS" style="height: 40px; width: 40px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Answer Guest Question</h2>
                            <p class="text-gray-600 text-xs">Provide a response to the guest's inquiry</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs text-gray-600">{{ \Carbon\Carbon::now()->format('M d, Y') }}</span>
                        <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center text-white text-sm font-semibold">
                            {{ substr(Auth::user()->name ?? 'M', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <p class="font-semibold mb-2">Please correct the following errors:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Guest Question (Left Column) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-4 border-b">Guest's Question</h3>
                            
                            <!-- Guest Info -->
                            <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Guest Name</p>
                                    <p class="text-gray-800 font-semibold">{{ $inquiry->guest_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Email</p>
                                    <p class="text-blue-600 hover:text-blue-800">
                                        <a href="mailto:{{ $inquiry->guest_email }}">{{ $inquiry->guest_email }}</a>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Submitted</p>
                                    <p class="text-gray-800">{{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm font-semibold">Status</p>
                                    @if($inquiry->status === 'PENDING')
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                            <i class="fas fa-hourglass-half mr-1"></i>Pending
                                        </span>
                                    @elseif($inquiry->status === 'ANSWERED')
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Answered
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800">
                                            <i class="fas fa-times mr-1"></i>Closed
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Question Content -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $inquiry->question }}</p>
                            </div>
                        </div>

                        <!-- Answer Form -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4 pb-4 border-b">Your Answer</h3>
                            
                            @if($inquiry->reply)
                                <!-- Display Existing Answer -->
                                <div class="mb-6">
                                    <p class="text-gray-600 text-sm font-semibold mb-2">Current Answer</p>
                                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $inquiry->reply }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Answered on {{ $inquiry->updated_at->format('M d, Y \a\t h:i A') }}
                                    </p>
                                </div>

                                <!-- Edit Form (Hidden by default) -->
                                <form id="editForm" method="POST" action="{{ route('manager.inquiries.update', $inquiry->id) }}" style="display:none;" class="mb-6">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label for="reply" class="block text-gray-700 font-semibold mb-2">Your Reply</label>
                                        <textarea name="reply" id="reply" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" required>{{ $inquiry->reply }}</textarea>
                                    </div>
                                    <div>
                                        <label for="status" class="block text-gray-700 font-semibold mb-2">Status</label>
                                        <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="PENDING" {{ $inquiry->status === 'PENDING' ? 'selected' : '' }}>Pending</option>
                                            <option value="ANSWERED" {{ $inquiry->status === 'ANSWERED' ? 'selected' : '' }}>Answered</option>
                                            <option value="CLOSED" {{ $inquiry->status === 'CLOSED' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                    <div class="mt-4 flex gap-3">
                                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            <i class="fas fa-save mr-2"></i>Save Changes
                                        </button>
                                        <button type="button" onclick="toggleEditForm()" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                                            <i class="fas fa-times mr-2"></i>Cancel
                                        </button>
                                    </div>
                                </form>

                                <!-- Edit/Delete Buttons -->
                                <div class="flex gap-3">
                                    <button onclick="toggleEditForm()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                                        <i class="fas fa-edit mr-2"></i>Edit Answer
                                    </button>
                                    <form method="POST" action="{{ route('manager.inquiries.destroy', $inquiry->id) }}" onsubmit="return confirm('Are you sure you want to delete this inquiry?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- New Answer Form -->
                                <form method="POST" action="{{ route('manager.inquiries.update', $inquiry->id) }}" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label for="reply" class="block text-gray-700 font-semibold mb-2">Your Reply</label>
                                        <textarea name="reply" id="reply" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Type your answer to the guest's question..." required></textarea>
                                        <p class="text-xs text-gray-500 mt-1">The guest will receive this answer via email.</p>
                                    </div>
                                    <div>
                                        <label for="status" class="block text-gray-700 font-semibold mb-2">Status</label>
                                        <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="ANSWERED" selected>Answered</option>
                                            <option value="PENDING">Pending</option>
                                            <option value="CLOSED">Closed</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                        <i class="fas fa-paper-plane mr-2"></i>Send Answer to Guest
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Sidebar (Right Column) -->
                    <aside class="lg:col-span-1">
                        <!-- Navigation -->
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <h4 class="font-bold text-gray-800 mb-4">Actions</h4>
                            <a href="{{ route('manager.inquiries.index') }}" class="block w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-center mb-2">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Questions
                            </a>
                        </div>

                        <!-- Related Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-bold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>Helpful Tips
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-2">
                                <li><strong>Be Professional:</strong> Maintain a courteous and professional tone.</li>
                                <li><strong>Be Clear:</strong> Provide specific, helpful information.</li>
                                <li><strong>Be Prompt:</strong> Answer questions quickly when possible.</li>
                                <li><strong>Personal Touch:</strong> Reference specific amenities or features when relevant.</li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleEditForm() {
            const form = document.getElementById('editForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
