@extends('admin.layout')

@section('title', 'Commission Management')

@push('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-left: 4px solid #667eea;
    }

    .stats-label {
        color: #999;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .stats-value {
        color: #333;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
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
        color: #333;
    }

    .table tbody tr:hover {
        background: #f8f9ff;
    }

    .badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
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

    .amount {
        font-weight: 700;
        color: #667eea;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
        color: white;
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-control {
        padding: 0.6rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 2rem; font-weight: 700; color: #333; margin: 0 0 0.5rem 0;">Commission Management</h2>
        <p style="color: #999; margin: 0;">Manage platform commissions and hotel payouts</p>
    </div>

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stats-card">
            <div class="stats-label">Total Commissions (Current Year)</div>
            <div class="stats-value amount">Nu. {{ number_format($stats['total_commission'] ?? 0, 2) }}</div>
        </div>
        <div class="stats-card">
            <div class="stats-label">Pending Commission</div>
            <div class="stats-value amount">Nu. {{ number_format($stats['pending_commission'] ?? 0, 2) }}</div>
        </div>
        <div class="stats-card">
            <div class="stats-label">Paid Commission</div>
            <div class="stats-value amount">Nu. {{ number_format($stats['paid_commission'] ?? 0, 2) }}</div>
        </div>
        <div class="stats-card">
            <div class="stats-label">Total Bookings</div>
            <div class="stats-value">{{ $stats['total_bookings'] ?? 0 }}</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-3">Filter by Month</h3>
        <form method="GET" action="{{ route('admin.commissions.index') }}" class="flex gap-4 flex-wrap">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                <select name="year" class="form-control" onchange="this.form.submit()">
                    @for($y = 2024; $y <= 2030; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                <select name="month" class="form-control" onchange="this.form.submit()">
                    <option value="">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <!-- Payouts Table -->
    <div class="table-container">
        <h3 class="text-lg font-bold text-gray-800 p-4 border-b">Hotel Payouts</h3>
        
        @if($payouts->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Month</th>
                        <th>Bookings</th>
                        <th>Guest Payments</th>
                        <th>Commission</th>
                        <th>Hotel Payout</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payouts as $payout)
                    <tr>
                        <td>
                            <strong>{{ $payout->hotel->name ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            {{ date('F Y', mktime(0, 0, 0, $payout->month ?: 1, 1, $payout->year)) }}
                        </td>
                        <td>
                            <strong>{{ $payout->total_bookings ?? 0 }}</strong>
                        </td>
                        <td>
                            <span class="amount">Nu. {{ number_format($payout->total_guest_payments ?? 0, 2) }}</span>
                        </td>
                        <td>
                            <span class="amount">Nu. {{ number_format($payout->total_commissions ?? 0, 2) }}</span>
                        </td>
                        <td>
                            <span class="amount">Nu. {{ number_format($payout->hotel_payout_amount ?? 0, 2) }}</span>
                        </td>
                        <td>
                            @if($payout->payout_status === 'paid')
                                <span class="badge badge-success">✓ Paid</span>
                            @elseif($payout->payout_status === 'pending')
                                <span class="badge badge-warning">⏳ Pending</span>
                            @else
                                <span class="badge badge-danger">✗ Failed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.commissions.show', $payout->id) }}" class="action-btn btn-view">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="p-4 border-t">
                {{ $payouts->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3rem;">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #ddd;"></i>
                <h4 class="text-gray-500 mt-3">No payouts found</h4>
                <p class="text-gray-400">No commission data available for the selected period</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh page every 30 seconds
    setTimeout(() => {
        location.reload();
    }, 30000);
</script>
@endpush
