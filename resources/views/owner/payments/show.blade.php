@extends('owner.layouts.app')

@section('title', 'Payment Details')

@section('header')
    <div class="flex items-center mb-2">
        <a href="{{ route('owner.payments.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Payment Details</h2>
    </div>
    <p class="text-gray-600 text-sm">Transaction information</p>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Payment Information -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Payment Information</h3>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Transaction ID</p>
                    <p class="font-mono font-semibold text-gray-900">{{ $payment->transaction_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Booking ID</p>
                    <p class="font-semibold text-gray-900">#{{ $payment->booking_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Amount</p>
                    <p class="text-2xl font-bold text-green-600">Nu. {{ number_format($payment->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                    <p class="font-semibold text-gray-900">{{ $payment->payment_method }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    @if($payment->status == 'PAID')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Paid
                        </span>
                    @elseif($payment->status == 'PENDING')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </span>
                    @elseif($payment->status == 'REFUNDED')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-undo mr-1"></i>Refunded
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>Failed
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Payment Date</p>
                    <p class="font-semibold text-gray-900">
                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y H:i A') : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Information -->
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Booking Details</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Guest Name</p>
                    <p class="font-semibold text-gray-900">{{ $payment->booking->guest_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="text-sm text-gray-900">{{ $payment->booking->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Mobile</p>
                    <p class="text-sm text-gray-900">{{ $payment->booking->mobile }}</p>
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-sm text-gray-500 mb-1">Check-in Date</p>
                    <p class="font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($payment->booking->check_in_date)->format('M d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Check-out Date</p>
                    <p class="font-semibold text-gray-900">
                        {{ \Carbon\Carbon::parse($payment->booking->check_out_date)->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
