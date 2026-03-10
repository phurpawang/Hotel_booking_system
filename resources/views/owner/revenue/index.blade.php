@extends('layouts.dashboard-bootstrap')

@section('title', 'Revenue & Commission - ' . $hotel->name)

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
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .stat-card.revenue {
        border-left: 5px solid #28a745;
    }
    
    .stat-card.commission {
        border-left: 5px solid #dc3545;
    }
    
    .stat-card.net {
        border-left: 5px solid #17a2b8;
    }
    
    .stat-card.bookings {
        border-left: 5px solid #ffc107;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0.5rem 0;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    }
    
    .payout-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .payout-card:hover {
        box-shadow: 0 5px 25px rgba(0,0,0,0.12);
    }
    
    .badge-pending {
        background: #ffc107;
        color: #000;
    }
    
    .badge-paid {
        background: #28a745;
        color: white;
    }
    
    .payment-method-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0.2rem;
    }
    
    .method-cash {
        background: #28a745;
        color: white;
    }
    
    .method-card {
        background: #17a2b8;
        color: white;
    }
    
    .method-bank {
        background: #6c757d;
        color: white;
    }
    
    .method-online {
        background: #667eea;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="dashboard-header">
        <h1 class="mb-2">
            <i class="bi bi-graph-up-arrow me-2"></i>Revenue & Commission Report
        </h1>
        <p class="mb-0 opacity-90">Track your earnings, commissions, and payouts</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('owner.revenue.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar me-1"></i>Year
                </label>
                <select name="year" class="form-select">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar-month me-1"></i>Month
                </label>
                <select name="month" class="form-select">
                    <option value="">All Months</option>
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $i => $monthName)
                        <option value="{{ $i + 1 }}" {{ $month == ($i + 1) ? 'selected' : '' }}>{{ $monthName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card revenue">
                <div class="stat-label">
                    <i class="bi bi-cash-stack me-1"></i>Total Bookings
                </div>
                <div class="stat-value text-success">
                    {{ $summary['total_bookings'] ?? 0 }}
                </div>
                <small class="text-muted">Number of reservations</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card revenue">
                <div class="stat-label">
                    <i class="bi bi-currency-exchange me-1"></i>Gross Revenue
                </div>
                <div class="stat-value text-success">
                    Nu. {{ number_format($summary['gross_revenue'] ?? 0, 2) }}
                </div>
                <small class="text-muted">Total guest payments</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card commission">
                <div class="stat-label">
                    <i class="bi bi-percent me-1"></i>Platform Commission
                </div>
                <div class="stat-value text-danger">
                    Nu. {{ number_format($summary['total_commission'] ?? 0, 2) }}
                </div>
                <small class="text-muted">10% commission to platform</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card net">
                <div class="stat-label">
                    <i class="bi bi-wallet2 me-1"></i>Net Revenue
                </div>
                <div class="stat-value text-info">
                    Nu. {{ number_format($summary['net_revenue'] ?? 0, 2) }}
                </div>
                <small class="text-muted">Your earnings (90%)</small>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <h5 class="mb-3">
                    <i class="bi bi-credit-card me-2" style="color: #667eea;"></i>Payments Collected by Hotel
                </h5>
                <div class="mb-2">
                    <strong>Amount Collected:</strong> Nu. {{ number_format($summary['pay_at_hotel_amount'] ?? 0, 2) }}
                </div>
                <div class="mb-3">
                    <strong>Commission Owed to Platform:</strong> 
                    <span class="text-danger">Nu. {{ number_format($summary['pay_at_hotel_commission'] ?? 0, 2) }}</span>
                </div>
                <div class="mb-2">
                    <strong>Bookings:</strong> {{ $summary['hotel_bookings'] ?? 0 }}
                </div>
                <div>
                    <span class="payment-method-badge method-cash">
                        <i class="bi bi-cash me-1"></i>Cash
                    </span>
                    <span class="payment-method-badge method-card">
                        <i class="bi bi-credit-card me-1"></i>Card
                    </span>
                    <span class="payment-method-badge method-bank">
                        <i class="bi bi-bank me-1"></i>Bank Transfer
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <h5 class="mb-3">
                    <i class="bi bi-globe me-2" style="color: #667eea;"></i>Online Payments (Platform)
                </h5>
                <div class="mb-2">
                    <strong>Platform Collected:</strong> Nu. {{ number_format($summary['pay_online_amount'] ?? 0, 2) }}
                </div>
                <div class="mb-3">
                    <strong>Payout to Hotel (90%):</strong> 
                    <span class="text-success">Nu. {{ number_format($summary['pay_online_payout'] ?? 0, 2) }}</span>
                </div>
                <div class="mb-2">
                    <strong>Bookings:</strong> {{ $summary['online_bookings'] ?? 0 }}
                </div>
                <div>
                    <span class="payment-method-badge method-online">
                        <i class="bi bi-globe me-1"></i>Online Payment
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Payouts -->
    <div class="payout-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">
                <i class="bi bi-calendar-range me-2" style="color: #667eea;"></i>Monthly Payout History
            </h4>
            @if($month)
                <span class="badge bg-primary">
                    Showing: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
                </span>
            @else
                <span class="badge bg-primary">
                    Showing: All of {{ $year }}
                </span>
            @endif
        </div>

        @if($payouts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Bookings</th>
                        <th>Guest Payments</th>
                        <th>Commission (10%)</th>
                        <th>Hotel Payout</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payouts as $payout)
                    <tr>
                        <td>
                            <strong>{{ date('F', mktime(0, 0, 0, $payout->month, 1)) }} {{ $payout->year }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $payout->total_bookings }}</span>
                        </td>
                        <td>
                            <strong style="color: #28a745;">Nu. {{ number_format($payout->total_guest_payments, 2) }}</strong>
                        </td>
                        <td>
                            <strong style="color: #dc3545;">Nu. {{ number_format($payout->total_commission, 2) }}</strong>
                        </td>
                        <td>
                            <strong style="color: #17a2b8;">Nu. {{ number_format($payout->hotel_payout_amount, 2) }}</strong>
                        </td>
                        <td>
                            @if($payout->payout_status == 'paid')
                                <span class="badge badge-paid">
                                    <i class="bi bi-check-circle me-1"></i>Paid
                                </span>
                            @else
                                <span class="badge badge-pending">
                                    <i class="bi bi-clock me-1"></i>Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('owner.revenue.show', $payout->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold" style="background: #f8f9fa;">
                        <td>TOTAL</td>
                        <td><span class="badge bg-info">{{ $payouts->sum('total_bookings') }}</span></td>
                        <td><strong style="color: #28a745;">Nu. {{ number_format($payouts->sum('total_guest_payments'), 2) }}</strong></td>
                        <td><strong style="color: #dc3545;">Nu. {{ number_format($payouts->sum('total_commission'), 2) }}</strong></td>
                        <td><strong style="color: #17a2b8;">Nu. {{ number_format($payouts->sum('hotel_payout_amount'), 2) }}</strong></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
            <h5 class="text-muted mt-3">No payout records found</h5>
            <p class="text-muted">Payouts will appear here once bookings are checked out</p>
        </div>
        @endif
    </div>

    <!-- Current Month Status -->
    @if($currentPayout)
    <div class="alert alert-info">
        <h5><i class="bi bi-info-circle me-2"></i>Current Month Status ({{ date('F Y') }})</h5>
        <div class="row mt-3">
            <div class="col-md-3">
                <strong>Bookings:</strong> {{ $currentPayout->total_bookings }}
            </div>
            <div class="col-md-3">
                <strong>Guest Payments:</strong> Nu. {{ number_format($currentPayout->total_guest_payments, 2) }}
            </div>
            <div class="col-md-3">
                <strong>Commission:</strong> Nu. {{ number_format($currentPayout->total_commission, 2) }}
            </div>
            <div class="col-md-3">
                <strong>Your Payout:</strong> Nu. {{ number_format($currentPayout->hotel_payout_amount, 2) }}
            </div>
        </div>
    </div>
    @endif

    <!-- Commission Info -->
    <div class="alert alert-light border">
        <h6><i class="bi bi-info-circle-fill me-2" style="color: #667eea;"></i>Commission Information</h6>
        <ul class="mb-0">
            <li><strong>Platform Commission Rate:</strong> 10% on all bookings</li>
            <li><strong>Cash/Card/Bank Transfer:</strong> You collect full payment from guest, then owe 10% commission to platform</li>
            <li><strong>Online Payment:</strong> Platform collects payment, then pays you 90% monthly</li>
            <li><strong>Payout Schedule:</strong> Monthly payouts processed by platform admin</li>
        </ul>
    </div>
</div>
@endsection
