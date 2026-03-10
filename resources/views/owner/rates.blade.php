@extends('layouts.dashboard-bootstrap')

@section('title', 'Rates & Availability - ' . $hotel->name)

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2"><i class="bi bi-cash-coin me-2"></i>Rates & Availability</h2>
                <p class="text-muted mb-0">{{ $hotel->name }}</p>
            </div>
        </div>
    </div>

    <!-- Rooms List with Pricing -->
    <div class="row g-4">
        @forelse($rooms as $room)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0"><i class="bi bi-door-closed-fill me-2"></i>Room {{ $room->room_number }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600;">
                            {{ $room->room_type }}
                        </span>
                        <span class="badge ms-2 @if(strtoupper($room->status) === 'AVAILABLE') bg-success @elseif(strtoupper($room->status) === 'OCCUPIED') bg-danger @else bg-warning @endif">
                            {{ $room->status }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Current Rate:</h6>
                        <h3 class="text-primary mb-0">Nu. {{ number_format($room->price_per_night, 2) }}</h3>
                        <small class="text-muted">per night</small>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1"><i class="bi bi-people-fill me-2 text-primary"></i><strong>Capacity:</strong> {{ $room->capacity }} {{ $room->capacity == 1 ? 'Person' : 'People' }}</p>
                    </div>
                    
                    @if($room->description)
                    <div class="mb-3">
                        <p class="text-muted small mb-0">{{ Str::limit($room->description, 100) }}</p>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('owner.rooms.edit', $room->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-fill me-2"></i>Edit Rate & Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle display-1 mb-3 d-block"></i>
                <h5>No Rooms Found</h5>
                <p>Please add rooms to manage rates and availability.</p>
                <a href="{{ route('owner.rooms.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Add First Room
                </a>
            </div>
        </div>
        @endforelse
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

    .card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
        border: none;
    }

    .btn-outline-primary {
        border: 2px solid #667eea;
        color: #667eea;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        transform: translateY(-2px);
    }
</style>

@endsection
