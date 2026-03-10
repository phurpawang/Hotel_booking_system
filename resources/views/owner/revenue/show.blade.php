@extends('layouts.dashboard-bootstrap')

@section('title', 'Payout Details - ' . $payout->hotel->name)

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        min-height: 100vh;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }
    
    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .stat-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .stat-box-value {
        font-size: 2rem;
        font-weight: 800;
        margin: 0.5rem 0;
    }
    
    .badge-paid {
        background: #28a745;
        color: white;
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
    
    .badge-pending {
        background: #ffc107;
        color: #000;
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2">
                    <i class="bi bi-file-earmark-text me-2"></i>Payout Details
                </h1>
                <p class="mb-0 opacity-90">
                    {{ date('F', mktime(0, 0, 0, $payout->month, 1)) }} {{ $payout->year }} - {{ $payout->hotel->name }}
                </p>
            </div>
            <div>
                @if($payout->payout_status == 'paid')
                    <span class="badge badge-paid">
                        <i class="bi bi-check-circle me-2"></i>Paid
                    </span>
                @else
                    <span class="badge badge-pending">
                        <i class="bi bi-clock me-2"></i>Pending
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('owner.revenue.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to Revenue
        </a>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted mb-2">Total Bookings</div>
                <div class="stat-box-value text-primary">{{ $payout->total_bookings }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted mb-2">Guest Payments</div>
                <div class="stat-box-value text-success">Nu. {{ number_format($payout->total_guest_payments, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted mb-2">Platform Commission</div>
                <div class="stat-box-value text-danger">Nu. {{ number_format($payout->total_commission, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="text-muted mb-2">Hotel Payout</div>
                <div class="stat-box-value text-info">Nu. {{ number_format($payout->hotel_payout_amount, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="detail-card">
                <h5 class="mb-3"><i class="bi bi-building me-2"></i>Pay at Hotel</h5>
                <div class="mb-2">
                    <strong>Amount:</strong> Nu. {{ number_format($payout->pay_at_hotel_amount ?? 0, 2) }}
                </div>
                <p class="text-muted small mb-0">Cash, Card, and Bank Transfer payments collected by hotel</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="detail-card">
                <h5 class="mb-3"><i class="bi bi-globe me-2"></i>Pay Online</h5>
                <div class="mb-2">
                    <strong>Amount:</strong> Nu. {{ number_format($payout->pay_online_amount ?? 0, 2) }}
                </div>
                <p class="text-muted small mb-0">Online payments collected by platform</p>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="detail-card">
        <h4 class="mb-4">
            <i class="bi bi-list-check me-2" style="color: #667eea;"></i>Booking Details
        </h4>
        
        @if($commissions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room</th>
                        <th>Dates</th>
                        <th>Base Amount</th>
                        <th>Commission</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commissions as $commission)
                    <tr>
                        <td>
                            <strong>{{ $commission->booking->booking_id ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                Room {{ $commission->room->room_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <small>
                                {{ $commission->check_in_date->format('M d') }} - 
                                {{ $commission->check_out_date->format('M d, Y') }}
                            </small>
                        </td>
                        <td>Nu. {{ number_format($commission->base_amount, 2) }}</td>
                        <td>Nu. {{ number_format($commission->commission_amount, 2) }}</td>
                        <td><strong>Nu. {{ number_format($commission->final_amount, 2) }}</strong></td>
                        <td>
                            @if($commission->payment_method == 'pay_online')
                                <span class="badge bg-primary">Online</span>
                            @else
                                <span class="badge bg-success">At Hotel</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold" style="background: #f8f9fa;">
                        <td colspan="3">TOTAL</td>
                        <td>Nu. {{ number_format($commissions->sum('base_amount'), 2) }}</td>
                        <td>Nu. {{ number_format($commissions->sum('commission_amount'), 2) }}</td>
                        <td>Nu. {{ number_format($commissions->sum('final_amount'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="text-muted mt-3">No booking details found</h5>
        </div>
        @endif
    </div>

    <!-- Payout Information -->
    @if($payout->payout_status == 'paid')
    <div class="alert alert-success">
        <h5><i class="bi bi-check-circle me-2"></i>Payout Completed</h5>
        <div class="row mt-3">
            <div class="col-md-4">
                <strong>Paid Date:</strong> {{ $payout->payout_date ? $payout->payout_date->format('M d, Y') : 'N/A' }}
            </div>
            <div class="col-md-4">
                <strong>Reference:</strong> {{ $payout->payout_reference ?? 'N/A' }}
            </div>
            <div class="col-md-4">
                <strong>Processed By:</strong> {{ $payout->processor->name ?? 'Admin' }}
            </div>
        </div>
        @if($payout->payout_notes)
        <div class="mt-3">
            <strong>Notes:</strong> {{ $payout->payout_notes }}
        </div>
        @endif
    </div>
    @else
    <div class="alert alert-warning">
        <h5><i class="bi bi-clock me-2"></i>Payout Pending</h5>
        <p class="mb-0">This payout is pending and will be processed by the platform administrator.</p>
    </div>
    @endif
</div>
@endsection
