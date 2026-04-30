@extends('admin.layout')

@section('title', 'Commission Details')

@push('styles')
<style>
    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .detail-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .detail-label {
        color: #999;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        color: #333;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .amount {
        color: #667eea;
    }

    .payment-method-badge {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 16px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .payment-online {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .payment-offline {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    .table tbody tr:hover {
        background: #f8f9ff;
    }

    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 16px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);
        color: #333;
    }

    .badge-danger {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: white;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 1.5rem;
        margin-right: 0.5rem;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
        margin-top: 1rem;
        margin-right: 0.5rem;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .payment-breakdown {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .breakdown-section {
        margin-bottom: 1.5rem;
    }

    .breakdown-section:last-child {
        margin-bottom: 0;
    }

    .breakdown-title {
        font-size: 1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breakdown-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .breakdown-item {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .breakdown-item.offline {
        border-left-color: #f5576c;
    }

    .breakdown-label {
        color: #999;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .breakdown-value {
        color: #333;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .breakdown-value.amount {
        color: #667eea;
    }

    .action-buttons {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 2rem; font-weight: 700; color: #333; margin: 0 0 0.5rem 0;">Commission Details</h2>
        <p style="color: #999; margin: 0;">{{ $payout->hotel->name ?? 'Hotel' }} - {{ date('F Y', mktime(0, 0, 0, $payout->month ?: 1, 1, $payout->year)) }}</p>
    </div>

    <a href="{{ route('admin.commissions.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to Commissions
    </a>

    <!-- Payout Summary -->
    <div class="detail-card">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Payout Summary</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Hotel</div>
                <div class="detail-value">{{ $payout->hotel->name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Period</div>
                <div class="detail-value">{{ date('F Y', mktime(0, 0, 0, $payout->month ?: 1, 1, $payout->year)) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Total Bookings</div>
                <div class="detail-value">{{ $payout->total_bookings ?? 0 }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Total Guest Payments</div>
                <div class="detail-value amount">Nu. {{ number_format($payout->total_guest_payments ?? 0, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Platform Commission</div>
                <div class="detail-value amount">Nu. {{ number_format($payout->total_commission ?? 0, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    @if($payout->hasOnlinePayments() || $payout->hasOfflinePayments())
    <div class="detail-card">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Payment Method Breakdown</h3>
        <div class="payment-breakdown">
            @if($payout->hasOnlinePayments())
            <div class="breakdown-section">
                <div class="breakdown-title">
                    <span class="payment-method-badge payment-online">ONLINE</span>
                    Platform Collects Payment
                </div>
                <div class="breakdown-grid">
                    <div class="breakdown-item">
                        <div class="breakdown-label">Guest Payment</div>
                        <div class="breakdown-value amount">Nu. {{ number_format($payout->online_payment_amount ?? 0, 2) }}</div>
                    </div>
                    <div class="breakdown-item">
                        <div class="breakdown-label">Commission Deducted</div>
                        <div class="breakdown-value amount">Nu. {{ number_format($payout->online_commission_amount ?? 0, 2) }}</div>
                    </div>
                    <div class="breakdown-item">
                        <div class="breakdown-label">Net Payout to Hotel</div>
                        <div class="breakdown-value amount">Nu. {{ number_format($payout->online_payout_amount ?? 0, 2) }}</div>
                    </div>
                    <div class="breakdown-item">
                        <div class="breakdown-label">Status</div>
                        @php $badge = $payout->online_payout_status_badge; @endphp
                        <span class="badge badge-{{ $badge['class'] }}">{{ $badge['text'] }}</span>
                    </div>
                </div>
            </div>
            @endif

            @if($payout->hasOfflinePayments())
            <div class="breakdown-section">
                <div class="breakdown-title">
                    <span class="payment-method-badge payment-offline">OFFLINE</span>
                    Hotel Collects Payment
                </div>
                <div class="breakdown-grid">
                    <div class="breakdown-item offline">
                        <div class="breakdown-label">Amount Collected by Hotel</div>
                        <div class="breakdown-value amount">Nu. {{ number_format($payout->offline_payment_amount ?? 0, 2) }}</div>
                    </div>
                    <div class="breakdown-item offline">
                        <div class="breakdown-label">Commission Due from Hotel</div>
                        <div class="breakdown-value amount">Nu. {{ number_format($payout->offline_commission_due ?? 0, 2) }}</div>
                    </div>
                    <div class="breakdown-item offline">
                        <div class="breakdown-label">Commission Status</div>
                        @php $badge = $payout->offline_commission_status_badge; @endphp
                        <span class="badge badge-{{ $badge['class'] }}">{{ $badge['text'] }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Commissions Table -->
    <div class="detail-card">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Booking Commissions</h3>
        
        @if($commissions->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest Name</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Commission</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commissions as $commission)
                    <tr>
                        <td>
                            <strong>{{ $commission->booking->booking_id ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            {{ $commission->booking->guest_name ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $commission->room->room_number ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $commission->check_in_date->format('M d, Y') }}
                        </td>
                        <td>
                            {{ $commission->check_out_date->format('M d, Y') }}
                        </td>
                        <td>
                            @if($commission->isOnlinePayment())
                                <span class="payment-method-badge payment-online">Online</span>
                            @else
                                <span class="payment-method-badge payment-offline">Offline</span>
                            @endif
                        </td>
                        <td>
                            <span class="amount">Nu. {{ number_format($commission->final_amount ?? 0, 2) }}</span>
                        </td>
                        <td>
                            <span class="amount">Nu. {{ number_format($commission->commission_amount ?? 0, 2) }}</span>
                        </td>
                        <td>
                            @php $badge = $commission->commission_status_badge; @endphp
                            <span class="badge badge-{{ $badge['class'] }}">{{ $badge['text'] }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 2rem;">
                <p class="text-gray-500">No commissions found for this period</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            @if($payout->hasOnlinePayments() && $payout->online_payout_status !== 'paid')
                <a href="{{ route('admin.commissions.payout-form', $payout->id) }}" class="btn-action">
                    <i class="bi bi-cash-coin"></i> Mark Online Payout as Paid
                </a>
            @endif

            @if($payout->hasOfflinePayments() && $payout->offline_commission_status !== 'received')
                <a href="{{ route('admin.commissions.offline-commission-form', $payout->id) }}" class="btn-action">
                    <i class="bi bi-check-circle"></i> Mark Offline Commission as Received
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh page every 60 seconds
    setTimeout(() => {
        location.reload();
    }, 60000);
</script>
@endpush
