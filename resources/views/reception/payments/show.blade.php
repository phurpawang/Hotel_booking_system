<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details - Reception Dashboard</title>
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
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Payment Details</h2>
                        <p class="text-gray-600 text-sm">View complete payment information</p>
                    </div>
                    <a href="{{ route('reception.payments.index') }}" class="text-purple-600 hover:text-purple-900">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Payments
                    </a>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
                @endif

                <div class="max-w-4xl mx-auto">
                    <!-- Payment Status Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Payment #{{ $payment->id }}</h3>
                                <p class="text-gray-600">Recorded on {{ $payment->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="text-right">
                                @if(strtoupper($payment->status) == 'PAID')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Paid
                                    </span>
                                @elseif(strtoupper($payment->status) == 'PENDING')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif(strtoupper($payment->status) == 'REFUNDED')
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-undo mr-1"></i>Refunded
                                    </span>
                                @else
                                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Failed
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Amount Display -->
                        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl p-6 text-white mb-6">
                            <p class="text-sm opacity-90 mb-2">Payment Amount</p>
                            <p class="text-5xl font-bold">Nu. {{ number_format($payment->amount, 2) }}</p>
                        </div>

                        <!-- Payment Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                                <p class="text-lg font-semibold text-gray-900">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Transaction ID</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $payment->transaction_id ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Booking ID</p>
                                <p class="text-lg font-semibold text-purple-600">#{{ $payment->booking->booking_id ?? $payment->booking_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Payment Date</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $payment->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        @if($payment->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-500 mb-2">Notes</p>
                            <p class="text-gray-900">{{ $payment->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Booking Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Booking Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Guest Name</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $payment->booking->user->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Room Number</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $payment->booking->room->room_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Check-in Date</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $payment->booking->check_in_date?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Check-out Date</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $payment->booking->check_out_date?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Booking Total</p>
                                <p class="text-lg font-semibold text-gray-900">Nu. {{ number_format($payment->booking->total_price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Booking Status</p>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst(strtolower($payment->booking->status)) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if(strtoupper($payment->status) == 'PENDING')
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Update Payment Status</h4>
                        <form method="POST" action="{{ route('reception.payments.updateStatus', $payment->id) }}" class="flex gap-4">
                            @csrf
                            <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <option value="PAID">Mark as Paid</option>
                                <option value="FAILED">Mark as Failed</option>
                            </select>
                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                                Update Status
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
