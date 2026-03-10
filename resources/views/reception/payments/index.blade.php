<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Reception Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'payments'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Payment Management</h2>
                    <p class="text-gray-600 text-sm">View and manage guest payments</p>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-90">Total Revenue</span>
                            <i class="fas fa-dollar-sign text-2xl opacity-90"></i>
                        </div>
                        <div class="text-3xl font-bold">Nu. {{ number_format($stats['total_revenue'], 2) }}</div>
                        <p class="text-xs opacity-90 mt-1">From paid bookings</p>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-90">Pending Amount</span>
                            <i class="fas fa-clock text-2xl opacity-90"></i>
                        </div>
                        <div class="text-3xl font-bold">Nu. {{ number_format($stats['pending'], 2) }}</div>
                        <p class="text-xs opacity-90 mt-1">Awaiting payment</p>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-90">Paid Transactions</span>
                            <i class="fas fa-check-circle text-2xl opacity-90"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['paid_count'] }}</div>
                        <p class="text-xs opacity-90 mt-1">Completed payments</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm opacity-90">Pending Count</span>
                            <i class="fas fa-hourglass-half text-2xl opacity-90"></i>
                        </div>
                        <div class="text-3xl font-bold">{{ $stats['pending_count'] }}</div>
                        <p class="text-xs opacity-90 mt-1">Pending payments</p>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <form method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ $search ?? '' }}" 
                                placeholder="Search by booking ID or guest name..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        </div>
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Status</option>
                            <option value="PAID" {{ ($status ?? '') == 'PAID' ? 'selected' : '' }}>Paid</option>
                            <option value="PENDING" {{ ($status ?? '') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="REFUNDED" {{ ($status ?? '') == 'REFUNDED' ? 'selected' : '' }}>Refunded</option>
                            <option value="FAILED" {{ ($status ?? '') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                        </select>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        <a href="{{ route('reception.payments.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-plus mr-2"></i>Record Payment
                        </a>
                    </form>
                </div>

                <!-- Payments Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-600">
                                        #{{ $payment->booking->booking_id ?? $payment->booking_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->booking->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->booking->room->room_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        Nu. {{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ str_replace('_', ' ', $payment->payment_method) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(strtoupper($payment->status) == 'PAID')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                        @elseif(strtoupper($payment->status) == 'PENDING')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif(strtoupper($payment->status) == 'REFUNDED')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Refunded
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Failed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $payment->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('reception.payments.show', $payment->id) }}" 
                                            class="text-purple-600 hover:text-purple-900">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-credit-card text-4xl mb-2"></i>
                                        <p>No payment records found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $payments->appends(['search' => $search, 'status' => $status])->links() }}
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
