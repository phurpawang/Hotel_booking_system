<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Questions & Inquiries - {{ $hotel->name ?? 'Manager' }}</title>
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
                            <h2 class="text-xl font-bold text-gray-800">Guest Questions & Inquiries</h2>
                            <p class="text-gray-600 text-xs">Manage questions from guests about your hotel</p>
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

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Total Questions</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $totalInquiries ?? 0 }}</p>
                            </div>
                            <i class="fas fa-comments text-blue-500 text-4xl opacity-20"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Pending Answers</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $pendingCount ?? 0 }}</p>
                            </div>
                            <i class="fas fa-hourglass-half text-orange-500 text-4xl opacity-20"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Answered</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $answeredCount ?? 0 }}</p>
                            </div>
                            <i class="fas fa-check-circle text-green-500 text-4xl opacity-20"></i>
                        </div>
                    </div>
                </div>

                <!-- Inquiries Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($inquiries->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-green-800 to-green-900 text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold">Guest Name</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold">Question</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold">Status</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold">Submitted</th>
                                        <th class="px-6 py-3 text-center text-sm font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($inquiries as $inquiry)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-800">{{ $inquiry->guest_name }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="mailto:{{ $inquiry->guest_email }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $inquiry->guest_email }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-gray-600 text-sm truncate max-w-xs" title="{{ $inquiry->question }}">
                                                {{ Str::limit($inquiry->question, 60) }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
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
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                                            {{ $inquiry->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('manager.inquiries.show', $inquiry->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t">
                            {{ $inquiries->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-comments text-gray-300 text-6xl mb-3"></i>
                            <p class="text-gray-600 text-lg">No guest inquiries yet.</p>
                            <p class="text-gray-500">When guests ask questions about your hotel, they will appear here.</p>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
</body>
</html>
