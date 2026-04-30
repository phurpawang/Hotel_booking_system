<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Payment - Reception Dashboard</title>
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
        .form-input textarea:focus,
        .form-input select:focus {
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
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .status-paid {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .status-pending {
            background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
            color: #333;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('reception.partials.sidebar', ['active' => 'payments'])

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden">
            <!-- Header -->
            <header class="header-gradient shadow-xl">
                <div class="px-8 py-10">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-4xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-money-bill-wave"></i>Record Payment
                            </h2>
                            <p class="text-purple-100 text-sm mt-2">Create a new payment record for guest booking</p>
                        </div>
                        <a href="{{ route('reception.payments.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-lg transition flex items-center gap-2 font-semibold">
                            <i class="fas fa-arrow-left"></i>Back to Payments
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-8">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-10">
                        <form method="POST" action="{{ route('reception.payments.store') }}">
                            @csrf

                            <!-- Form Title -->
                            <div class="mb-10 pb-6 border-b-2 border-gray-100">
                                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                    <i class="fas fa-clipboard-list text-purple-600"></i>Payment Details
                                </h3>
                                <p class="text-gray-600 text-sm mt-1">Enter payment information below</p>
                            </div>

                            <!-- Two Column Layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Booking Selection -->
                                <div class="field-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-calendar-check text-blue-600"></i>Select Booking <span class="text-red-500">*</span>
                                    </label>
                                    <select name="booking_id" required
                                        class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white text-gray-800">
                                        <option value="">-- Select a booking --</option>
                                        @foreach($bookings as $booking)
                                            @if($booking->user && $booking->room)
                                            <option value="{{ $booking->id }}">
                                                #{{ $booking->booking_id ?? $booking->id }} - {{ $booking->user->name }} - Room {{ $booking->room->room_number }} - Nu. {{ number_format($booking->total_price, 2) }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('booking_id')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Amount -->
                                <div class="field-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-coins text-green-600"></i>Amount (Nu.) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                                        placeholder="Enter amount"
                                        class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                    @error('amount')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Two Column Layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                <!-- Payment Method -->
                                <div class="field-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-credit-card text-orange-600"></i>Payment Method <span class="text-red-500">*</span>
                                    </label>
                                    <select name="payment_method" required
                                        class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition bg-white text-gray-800">
                                        <option value="">-- Select method --</option>
                                        <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>
                                            <i class="fas fa-money-bill"></i> Cash
                                        </option>
                                        <option value="CARD" {{ old('payment_method') == 'CARD' ? 'selected' : '' }}>
                                            <i class="fas fa-credit-card"></i> Card
                                        </option>
                                        <option value="ONLINE" {{ old('payment_method') == 'ONLINE' ? 'selected' : '' }}>
                                            <i class="fas fa-globe"></i> Online
                                        </option>
                                        <option value="BANK_TRANSFER" {{ old('payment_method') == 'BANK_TRANSFER' ? 'selected' : '' }}>
                                            <i class="fas fa-university"></i> Bank Transfer
                                        </option>
                                    </select>
                                    @error('payment_method')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Payment Status -->
                                <div class="field-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-check-circle text-indigo-600"></i>Payment Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" required
                                        class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white text-gray-800">
                                        <option value="PAID" {{ old('status') == 'PAID' ? 'selected' : '' }}>
                                            <i class="fas fa-check"></i> Paid
                                        </option>
                                        <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>
                                            <i class="fas fa-clock"></i> Pending
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Transaction ID -->
                            <div class="field-group mb-8">
                                <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-receipt text-cyan-600"></i>Transaction ID (Optional)
                                </label>
                                <input type="text" name="transaction_id" value="{{ old('transaction_id') }}"
                                    placeholder="Enter transaction ID (e.g., TXN-2026-001)"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">
                                @error('transaction_id')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="field-group mb-10">
                                <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-sticky-note text-pink-600"></i>Notes (Optional)
                                </label>
                                <textarea name="notes" rows="4" placeholder="Add any additional notes about this payment..."
                                    class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition bg-white text-gray-800 placeholder-gray-400">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-4 pt-6 border-t-2 border-gray-100">
                                <button type="submit" class="flex-1 button-gradient text-white font-bold px-8 py-4 rounded-xl transition shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i>Record Payment
                                </button>
                                <a href="{{ route('reception.payments.index') }}" 
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-8 py-4 rounded-xl transition shadow-lg hover:shadow-xl transform hover:scale-105 text-center flex items-center justify-center gap-2">
                                    <i class="fas fa-times-circle"></i>Cancel
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
