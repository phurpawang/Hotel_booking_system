@extends('admin.layout')

@section('title', 'Payments Management')

@section('content')
<div class="dashboard-header">
    <h1>Payments Management</h1>
</div>

<!-- Filters -->
<div class="dashboard-card" style="margin-bottom: 20px;">
    <div class="card-body">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <select name="payment_status" class="form-control">
                        <option value="">All Payment Status</option>
                        <option value="PENDING" {{ request('payment_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="PAID" {{ request('payment_status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                        <option value="REFUNDED" {{ request('payment_status') == 'REFUNDED' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.payments.index') }}" class="btn-reset"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #28a745;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($payments->where('payment_status', 'PAID')->sum('total_price'), 2) }}</h3>
            <p>Total Paid</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ffc107;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($payments->where('payment_status', 'PENDING')->sum('total_price'), 2) }}</h3>
            <p>Pending Payments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #6c757d;">
            <i class="fas fa-undo"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($payments->where('payment_status', 'REFUNDED')->sum('total_price'), 2) }}</h3>
            <p>Total Refunded</p>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="dashboard-card">
    <div class="card-body">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest Name</th>
                            <th>Hotel</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Screenshot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>#{{ $payment->id }}</td>
                                <td>
                                    <strong>{{ $payment->guest_name }}</strong><br>
                                    <small class="text-muted">{{ $payment->guest_email }}</small>
                                </td>
                                <td>{{ $payment->hotel->name ?? 'N/A' }}</td>
                                <td>
                                    <strong style="color: var(--primary-color);">Nu. {{ number_format($payment->total_price, 2) }}</strong>
                                </td>
                                <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                <td>
                                    @if($payment->payment_status == 'PAID')
                                        <span class="badge badge-success">Paid</span>
                                    @elseif($payment->payment_status == 'PENDING')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($payment->payment_status == 'REFUNDED')
                                        <span class="badge badge-secondary">Refunded</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->payment_screenshot)
                                        <a href="{{ asset('storage/' . $payment->payment_screenshot) }}" target="_blank" class="btn-action btn-view" title="View Screenshot">
                                            <i class="fas fa-image"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="action-buttons">
                                    @if($payment->payment_status == 'PENDING')
                                        <form action="{{ route('admin.payments.mark-paid', $payment->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-action btn-success" title="Mark as Paid">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($payment->payment_status == 'PAID')
                                        <form action="{{ route('admin.payments.refund', $payment->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="refund_amount" value="{{ $payment->total_price }}">
                                            <button type="submit" class="btn-action btn-warning" title="Refund">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.reservations.show', $payment->id) }}" class="btn-action btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                {{ $payments->links() }}
            </div>
        @else
            <p class="no-data">No payment records found</p>
        @endif
    </div>
</div>
@endsection
