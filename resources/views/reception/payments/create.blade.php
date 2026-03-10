<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Payment - Reception Dashboard</title>
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
                        <h2 class="text-2xl font-bold text-gray-800">Record Payment</h2>
                        <p class="text-gray-600 text-sm">Create a new payment record</p>
                    </div>
                    <a href="{{ route('reception.payments.index') }}" class="text-purple-600 hover:text-purple-900">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Payments
                    </a>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <form method="POST" action="{{ route('reception.payments.store') }}">
                            @csrf

                            <!-- Booking Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Booking *</label>
                                <select name="booking_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">-- Select a booking --</option>
                                    @foreach($bookings as $booking)
                                        <option value="{{ $booking->id }}">
                                            #{{ $booking->booking_id ?? $booking->id }} - {{ $booking->user->name }} - Room {{ $booking->room->room_number }} - Nu. {{ number_format($booking->total_price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Amount (Nu.) *</label>
                                <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @error('amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                                <select name="payment_method" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">-- Select method --</option>
                                    <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>Cash</option>
                                    <option value="CARD" {{ old('payment_method') == 'CARD' ? 'selected' : '' }}>Card</option>
                                    <option value="ONLINE" {{ old('payment_method') == 'ONLINE' ? 'selected' : '' }}>Online</option>
                                    <option value="BANK_TRANSFER" {{ old('payment_method') == 'BANK_TRANSFER' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('payment_method')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Transaction ID -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction ID (Optional)</label>
                                <input type="text" name="transaction_id" value="{{ old('transaction_id') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @error('transaction_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Status -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status *</label>
                                <select name="status" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="PAID" {{ old('status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                                    <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition">
                                    <i class="fas fa-save mr-2"></i>Record Payment
                                </button>
                                <a href="{{ route('reception.payments.index') }}" 
                                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg transition text-center">
                                    Cancel
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
