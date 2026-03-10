@extends('layouts.dashboard')

@section('title', 'Receptionist Dashboard')

@section('sidebar')
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('receptionist.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('receptionist.arrivals') }}">
            <i class="bi bi-arrow-down-circle"></i> Today's Arrivals
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('receptionist.departures') }}">
            <i class="bi bi-arrow-up-circle"></i> Today's Departures
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('receptionist.bookings') }}">
            <i class="bi bi-calendar-check"></i> All Bookings
        </a>
    </li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-speedometer2"></i> Reception Dashboard</h2>
        <span class="badge bg-secondary">{{ $hotel->hotel_id }}</span>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Check-ins Today</h6>
                            <h2 class="mb-0">{{ $todayCheckIns }}</h2>
                        </div>
                        <i class="bi bi-arrow-down-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Check-outs Today</h6>
                            <h2 class="mb-0">{{ $todayCheckOuts }}</h2>
                        </div>
                        <i class="bi bi-arrow-up-circle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Current Guests</h6>
                            <h2 class="mb-0">{{ $currentGuests }}</h2>
                        </div>
                        <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-uppercase mb-0">Upcoming</h6>
                            <h2 class="mb-0">{{ $upcomingArrivals->count() ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-calendar-event" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status Overview -->
    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Status Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                <i class="bi bi-credit-card-fill text-success" style="font-size: 2rem;"></i>
                                <div class="ms-3">
                                    <h4 class="mb-0 text-success">{{ $paymentStats['paid_online'] ?? 0 }}</h4>
                                    <small class="text-muted">Paid Online (Completed)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
                                <i class="bi bi-cash-stack text-warning" style="font-size: 2rem;"></i>
                                <div class="ms-3">
                                    <h4 class="mb-0 text-warning">{{ $paymentStats['pending_at_hotel'] ?? 0 }}</h4>
                                    <small class="text-muted">Pay at Hotel (Pending Collection)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Note:</strong> Guests who selected "Pay at Hotel" need to complete payment at reception upon check-in.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Today's Arrivals -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-down-circle"></i> Today's Arrivals</h5>
                </div>
                <div class="card-body">
                    @if($todayArrivals->count() > 0)
                        <div class="list-group">
                            @foreach($todayArrivals as $arrival)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $arrival->guest_name ?? 'N/A' }}</h6>
                                            <small class="text-muted">
                                                Room {{ $arrival->room->room_number ?? 'N/A' }}
                                            </small>
                                            <br>
                                            @if($arrival->commission)
                                                @if($arrival->commission->payment_method == 'pay_online')
                                                    <span class="badge bg-success mt-1">
                                                        <i class="bi bi-credit-card"></i> Paid Online
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark mt-1">
                                                        <i class="bi bi-cash"></i> Pay at Hotel - Nu. {{ number_format($arrival->commission->final_amount, 2) }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        @if($arrival->status === 'confirmed')
                                            <form action="{{ route('receptionist.bookings.checkin', $arrival->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle"></i> Check-in
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-info">Checked In</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mb-0">No arrivals today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Departures -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-arrow-up-circle"></i> Today's Departures</h5>
                </div>
                <div class="card-body">
                    @if($todayDepartures->count() > 0)
                        <div class="list-group">
                            @foreach($todayDepartures as $departure)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $departure->guest_name ?? 'N/A' }}</h6>
                                            <small class="text-muted">
                                                Room {{ $departure->room->room_number ?? 'N/A' }}
                                            </small>
                                            <br>
                                            @if($departure->commission)
                                                <small class="text-muted d-block mt-1">
                                                    Room Price: Nu. {{ number_format($departure->commission->final_amount, 2) }}
                                                </small>
                                                @if($departure->commission->payment_method == 'pay_online')
                                                    <span class="badge bg-success mt-1">
                                                        <i class="bi bi-credit-card"></i> Paid Online
                                                    </span>
                                                @else
                                                    <span class="badge bg-info mt-1">
                                                        <i class="bi bi-cash"></i> Paid at Hotel
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        @if($departure->status === 'checked_in')
                                            <form action="{{ route('receptionist.bookings.checkout', $departure->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-box-arrow-right"></i> Check-out
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">Completed</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mb-0">No departures today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
