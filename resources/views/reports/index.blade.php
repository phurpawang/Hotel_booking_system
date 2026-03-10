@extends('layouts.dashboard-bootstrap')

@section('title', 'Reports - ' . $hotel->name)

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2"><i class="bi bi-bar-chart-line-fill me-2"></i>Reports & Analytics</h2>
                <p class="text-muted mb-0">{{ $hotel->name }}</p>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4" style="color: #667eea;">
                <i class="bi bi-calendar-range me-2"></i>Date Range
            </h5>
            <form method="GET" action="{{ route(strtolower(Auth::user()->role) . '.reports') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" style="color: #667eea; font-weight: 600;">From Date</label>
                        <input type="date" name="start_date" class="form-control form-control-lg" value="{{ $startDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="color: #667eea; font-weight: 600;">To Date</label>
                        <input type="date" name="end_date" class="form-control form-control-lg" value="{{ $endDate }}" required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-gradient-primary btn-lg w-100">
                            <i class="bi bi-search me-2"></i>Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($revenueData)
    <!-- Revenue Report (Owner Only) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Revenue Report</h5>
                <a href="{{ route(strtolower(Auth::user()->role) . '.reports.export.revenue') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-light btn-sm">
                    <i class="bi bi-download me-1"></i>Export
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Total Revenue</h6>
                        <h3 class="text-success mb-0">Nu. {{ number_format($revenueData['total_revenue'] ?? 0, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Paid Bookings</h6>
                        <h3 class="text-primary mb-0">{{ $revenueData['paid_bookings'] ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Pending Payments</h6>
                        <h3 class="text-warning mb-0">{{ $revenueData['pending_payments'] ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Average Booking Value</h6>
                        <h3 class="text-info mb-0">Nu. {{ number_format($revenueData['average_booking_value'] ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Booking Report -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Booking Report</h5>
                <a href="{{ route(strtolower(Auth::user()->role) . '.reports.export.bookings') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-light btn-sm">
                    <i class="bi bi-download me-1"></i>Export
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Total Bookings</h6>
                        <h3 class="text-primary mb-0">{{ $bookingData['total_bookings'] ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Confirmed</h6>
                        <h3 class="text-success mb-0">{{ $bookingData['confirmed'] ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Checked In</h6>
                        <h3 class="text-info mb-0">{{ $bookingData['checked_in'] ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Cancelled</h6>
                        <h3 class="text-danger mb-0">{{ $bookingData['cancelled'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Occupancy Report -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-pie-chart-fill me-2"></i>Occupancy Report</h5>
                <a href="{{ route(strtolower(Auth::user()->role) . '.reports.export.occupancy') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" class="btn btn-light btn-sm">
                    <i class="bi bi-download me-1"></i>Export
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Total Rooms</h6>
                        <h3 class="text-primary mb-0">{{ $occupancyData['total_rooms'] ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Occupied Rooms</h6>
                        <h3 class="text-success mb-0">{{ $occupancyData['occupied_rooms'] ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <h6 class="text-muted mb-2">Occupancy Rate</h6>
                        <h3 class="text-info mb-0">{{ number_format($occupancyData['occupancy_rate'] ?? 0, 1) }}%</h3>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <div class="progress" style="height: 30px; border-radius: 15px;">
                    <div class="progress-bar" 
                         role="progressbar" 
                         style="width: {{ $occupancyData['occupancy_rate'] ?? 0 }}%; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); font-weight: 600; font-size: 1rem;"
                         aria-valuenow="{{ $occupancyData['occupancy_rate'] ?? 0 }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ number_format($occupancyData['occupancy_rate'] ?? 0, 1) }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2.5rem;
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .dashboard-header h2 {
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .filter-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border-left: 5px solid #667eea;
    }

    .filter-card .form-control {
        border: 2px solid #e0e6ed;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .filter-card .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }

    .card-header {
        border: none;
    }

    .stat-box {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }

    .btn-gradient-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }
</style>

@endsection
