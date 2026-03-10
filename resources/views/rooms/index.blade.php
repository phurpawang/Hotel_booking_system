@extends('layouts.dashboard-bootstrap')

@section('title', 'Rooms Management - ' . $hotel->name)

@section('content')
<div class="container-fluid py-4">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2"><i class="bi bi-door-closed-fill me-2"></i>Rooms Management</h2>
                <p class="text-muted mb-0">{{ $hotel->name }}</p>
            </div>
            @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
            <div>
                <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.create') }}" class="btn btn-gradient-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Add New Room
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Rooms -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="bi bi-door-closed-fill"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Rooms</p>
                </div>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value">{{ $stats['available'] }}</h3>
                    <p class="stat-label">Available</p>
                </div>
            </div>
        </div>

        <!-- Occupied Rooms -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-icon">
                    <i class="bi bi-person-fill-check"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value">{{ $stats['occupied'] }}</h3>
                    <p class="stat-label">Occupied</p>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="col-md-6 col-lg-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%);">
                <div class="stat-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <div class="stat-details">
                    <h3 class="stat-value">{{ $stats['maintenance'] }}</h3>
                    <p class="stat-label">Maintenance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4" style="color: #667eea;">
                <i class="bi bi-funnel-fill me-2"></i>Filter Rooms
            </h5>
            <form method="GET" action="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label" style="color: #667eea; font-weight: 600;">
                            <i class="bi bi-search me-1"></i>Search Room Number
                        </label>
                        <input type="text" 
                               name="search" 
                               class="form-control form-control-lg" 
                               placeholder="Enter room number..." 
                               value="{{ request('search') }}">
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="col-md-4">
                        <label class="form-label" style="color: #667eea; font-weight: 600;">
                            <i class="bi bi-bookmark-fill me-1"></i>Status
                        </label>
                        <select name="status" class="form-select form-select-lg">
                            <option value="">All Statuses</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>
                    
                    <!-- Room Type Filter -->
                    <div class="col-md-4">
                        <label class="form-label" style="color: #667eea; font-weight: 600;">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i>Room Type
                        </label>
                        <input type="text" 
                               name="room_type" 
                               class="form-control form-control-lg" 
                               placeholder="e.g., Single, Double..." 
                               value="{{ request('room_type') }}">
                    </div>
                    
                    <!-- Filter Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-gradient-primary btn-lg me-2">
                            <i class="bi bi-search me-2"></i>Apply Filters
                        </button>
                        <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card table-card">
        <div class="card-header">
            <h5 class="mb-0" style="color: #667eea; font-weight: 600;">
                <i class="bi bi-list-ul me-2"></i>All Rooms
                <span class="badge ms-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 0.6rem 1.2rem; border-radius: 25px; font-size: 0.9rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">{{ $rooms->total() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($rooms->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Capacity</th>
                            <th>Base Price</th>
                            <th>Commission (10%)</th>
                            <th>Final Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr>
                            <td style="border-left: 4px solid #667eea;">
                                <span style="font-weight: 700; color: #667eea; font-size: 1.1rem;">
                                    {{ $room->room_number }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.85rem; box-shadow: 0 3px 10px rgba(79, 172, 254, 0.3);">
                                    {{ $room->room_type }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-people-fill me-2" style="color: #667eea;"></i>
                                <span style="font-weight: 600; color: #333;">{{ $room->capacity }} {{ $room->capacity == 1 ? 'Person' : 'People' }}</span>
                            </td>
                            <td>
                                <div style="font-size: 0.75rem; color: #95a5a6; margin-bottom: 0.2rem;">Your Earning</div>
                                <span style="font-weight: 700; color: #11998e; font-size: 1.1rem;">
                                    Nu. {{ number_format($room->base_price ?? $room->price_per_night, 2) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.75rem; color: #95a5a6; margin-bottom: 0.2rem;">Platform Fee</div>
                                <span style="font-weight: 600; color: #e74c3c; font-size: 0.95rem;">
                                    Nu. {{ number_format($room->commission_amount ?? ($room->base_price ?? $room->price_per_night) * 0.10, 2) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.75rem; color: #95a5a6; margin-bottom: 0.2rem;">Guest Pays</div>
                                <span style="font-weight: 700; color: #2980b9; font-size: 1.1rem;">
                                    Nu. {{ number_format($room->final_price ?? (($room->base_price ?? $room->price_per_night) * 1.10), 2) }}
                                </span>
                            </td>
                            <td>
                                @if(strtoupper($room->status) === 'AVAILABLE')
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 0.6rem 1.2rem; border-radius: 25px; font-weight: 600; font-size: 0.85rem; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);">
                                        <i class="bi bi-check-circle-fill me-1"></i>Available
                                    </span>
                                @elseif(strtoupper($room->status) === 'OCCUPIED')
                                    <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 0.6rem 1.2rem; border-radius: 25px; font-weight: 600; font-size: 0.85rem; box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);">
                                        <i class="bi bi-person-fill-check me-1"></i>Occupied
                                    </span>
                                @elseif(strtoupper($room->status) === 'MAINTENANCE')
                                    <span class="badge" style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); padding: 0.6rem 1.2rem; border-radius: 25px; font-weight: 600; font-size: 0.85rem; box-shadow: 0 4px 15px rgba(255, 167, 81, 0.4);">
                                        <i class="bi bi-tools me-1"></i>Maintenance
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ $room->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.show', $room->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Details">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
                                    <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.edit', $room->id) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit Room">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    @endif
                                    @if(strtoupper(Auth::user()->role) == 'OWNER')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete({{ $room->id }})"
                                            title="Delete Room">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                    @endif
                                </div>
                                
                                @if(strtoupper(Auth::user()->role) == 'OWNER')
                                <form id="delete-form-{{ $room->id }}" 
                                      action="{{ route('owner.rooms.destroy', $room->id) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $rooms->firstItem() ?? 0 }} to {{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }} rooms
                    </div>
                    <div>
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">No rooms found</h5>
                <p class="text-muted">Try adjusting your filters or add a new room.</p>
                @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
                <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.create') }}" class="btn btn-gradient-primary mt-3">
                    <i class="bi bi-plus-circle me-2"></i>Add First Room
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    /* Enhanced Styles - Same as Bookings Dashboard */
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
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .dashboard-header h2 {
        font-weight: 700;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .stat-card {
        padding: 2rem;
        border-radius: 20px;
        color: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(45deg);
        transition: all 0.5s ease;
    }

    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    }

    .stat-card:hover::before {
        top: -30%;
        right: -30%;
    }

    .stat-icon {
        font-size: 3rem;
        opacity: 0.9;
        margin-bottom: 1rem;
        filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2));
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .stat-label {
        font-size: 1rem;
        opacity: 0.95;
        margin: 0;
        font-weight: 500;
    }

    .filter-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border-left: 5px solid #667eea;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border: 2px solid #e0e6ed;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .table-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .table-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border: none;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }

    .table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table thead th {
        border: none;
        padding: 1.2rem 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 3px solid rgba(255,255,255,0.2);
    }

    .table thead th:first-child {
        border-radius: 12px 0 0 0;
    }

    .table thead th:last-child {
        border-radius: 0 12px 0 0;
    }

    .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .table tbody td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        font-size: 0.95rem;
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

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }

    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.3s ease;
    }

    .btn-group .btn:hover {
        transform: scale(1.08);
    }

    .card-footer {
        border-top: 1px solid #e9ecef;
        padding: 1.5rem;
        background: #f8f9fa;
    }
</style>

@if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
<script>
    function confirmDelete(roomId) {
        if (confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
            document.getElementById('delete-form-' + roomId).submit();
        }
    }
</script>
@endif

@endsection
