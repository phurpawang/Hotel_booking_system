@extends('owner.layouts.app')

@section('title', 'Payments')

@section('header')
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Payments & Transactions</h2>
        <p class="text-gray-600 text-sm">Track payment history and revenue</p>
    </div>
@endsection

@section('content')
<!-- Revenue Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 mb-2">Total Revenue</p>
                <p class="text-4xl font-bold">Nu. {{ number_format($totalRevenue, 2) }}</p>
            </div>
            <i class="fas fa-chart-line text-5xl opacity-20"></i>
        </div>
    </div>
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90 mb-2">Pending Payments</p>
                <p class="text-4xl font-bold">Nu. {{ number_format($pendingPayments, 2) }}</p>
            </div>
            <i class="fas fa-clock text-5xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800">Payment History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                        {{ $payment->transaction_id ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        #{{ $payment->booking_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        Nu. {{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->payment_method }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payment->status == 'PAID')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Paid
                            </span>
                        @elseif($payment->status == 'PENDING')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($payment->status == 'REFUNDED')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Refunded
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Failed
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('owner.payments.show', $payment->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-credit-card text-4xl mb-2"></i>
                        <p>No payments found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
