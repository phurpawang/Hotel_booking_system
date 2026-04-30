@extends(
    strtoupper(Auth::user()->role) === 'MANAGER' ? 'manager.layouts.app' :
    (in_array(strtoupper(Auth::user()->role), ['RECEPTIONIST', 'RECEPTION']) ? 'reception.layouts.app' : 'layouts.owner-bootstrap')
)

@section('title', 'Rooms Management')

@section('header')
    <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Rooms Management</h2>
    <p class="text-gray-600 text-sm mt-1">{{ $hotel->name }}</p>
@endsection

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
    
    <!-- Dashboard Header -->
    <div class="dashboard-header mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 15px; color: white; box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3); position: relative; z-index: 5;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="margin: 0; font-size: 1.8rem; font-weight: 700; color: white;"><i class="bi bi-door-closed-fill me-2"></i>Rooms Management</h2>
                <p style="margin: 0.5rem 0 0; font-size: 0.9rem; opacity: 0.95; color: white;">{{ $hotel->name }}</p>
            </div>
            @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
            <button type="button" 
                    onclick='window.location.href="{{ route(strtolower(Auth::user()->role) . ".rooms.create") }}"'
                    style="background: white; color: #667eea; font-weight: 700; border-radius: 10px; padding: 0.6rem 1.5rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease; cursor: pointer; border: none; line-height: 1.5;"
                    onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'; this.style.transform='translateY(0)';">
                <i class="bi bi-plus-circle-fill"></i>Add New Room
            </button>
            @endif
        </div>
    </div>

    <!-- Statistics Cards - Smaller -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <!-- Total Rooms -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.2rem; border-radius: 12px; color: white; box-shadow: 0 3px 10px rgba(102, 126, 234, 0.2); transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;"><i class="bi bi-door-closed-fill"></i></div>
            <h3 style="font-size: 1.8rem; font-weight: 700; margin: 0.5rem 0; color: white;">{{ $stats['total'] }}</h3>
            <p style="font-size: 0.85rem; margin: 0; opacity: 0.95;">Total Rooms</p>
        </div>

        <!-- Available Rooms -->
        <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 1.2rem; border-radius: 12px; color: white; box-shadow: 0 3px 10px rgba(17, 153, 142, 0.2); transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;"><i class="bi bi-check-circle-fill"></i></div>
            <h3 style="font-size: 1.8rem; font-weight: 700; margin: 0.5rem 0; color: white;">{{ $stats['available'] }}</h3>
            <p style="font-size: 0.85rem; margin: 0; opacity: 0.95;">Available</p>
        </div>

        <!-- Occupied Rooms -->
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 1.2rem; border-radius: 12px; color: white; box-shadow: 0 3px 10px rgba(240, 147, 251, 0.2); transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;"><i class="bi bi-person-fill-check"></i></div>
            <h3 style="font-size: 1.8rem; font-weight: 700; margin: 0.5rem 0; color: white;">{{ $stats['occupied'] }}</h3>
            <p style="font-size: 0.85rem; margin: 0; opacity: 0.95;">Occupied</p>
        </div>

        <!-- Maintenance -->
        <div style="background: linear-gradient(135deg, #ffa751 0%, #ffe259 100%); padding: 1.2rem; border-radius: 12px; color: white; box-shadow: 0 3px 10px rgba(255, 167, 81, 0.2); transition: all 0.3s ease;">
            <div style="font-size: 1.8rem; margin-bottom: 0.5rem;"><i class="bi bi-tools"></i></div>
            <h3 style="font-size: 1.8rem; font-weight: 700; margin: 0.5rem 0; color: white;">{{ $stats['maintenance'] }}</h3>
            <p style="font-size: 0.85rem; margin: 0; opacity: 0.95;">Maintenance</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div style="background: white; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 3px 10px rgba(0,0,0,0.08); border-left: 4px solid #667eea;">
        <h5 style="color: #667eea; font-weight: 700; margin-bottom: 1.5rem; font-size: 1rem;">
            <i class="bi bi-funnel-fill me-2"></i>Filter Rooms
        </h5>
        <form method="GET" action="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}">
            <div style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
                <!-- Search -->
                <div style="flex: 1 1 200px; min-width: 200px;">
                    <label style="color: #667eea; font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <i class="bi bi-search me-1"></i>Search Room Number
                    </label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Enter room number..." 
                           value="{{ request('search') }}"
                           style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 0.6rem; transition: all 0.3s ease;">
                </div>
                
                <!-- Status Filter -->
                <div style="flex: 1 1 150px; min-width: 150px;">
                    <label style="color: #667eea; font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <i class="bi bi-bookmark-fill me-1"></i>Status
                    </label>
                    <select name="status" 
                            class="form-control"
                            style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 0.6rem; transition: all 0.3s ease;">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                
                <!-- Room Type Filter -->
                <div style="flex: 1 1 200px; min-width: 200px;">
                    <label style="color: #667eea; font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <i class="bi bi-grid-3x3-gap-fill me-1"></i>Room Type
                    </label>
                    <input type="text" 
                           name="room_type" 
                           class="form-control" 
                           placeholder="e.g., Single, Double..." 
                           value="{{ request('room_type') }}"
                           style="border: 2px solid #e0e0e0; border-radius: 8px; padding: 0.6rem; transition: all 0.3s ease;">
                </div>
                
                <!-- Filter Buttons -->
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-search"></i>Apply
                    </button>
                    <a href="{{ route(strtolower(Auth::user()->role) . '.rooms.index') }}" style="background: #f5f5f5; color: #667eea; border: 2px solid #e0e0e0; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="bi bi-arrow-clockwise"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Rooms Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.08); overflow: hidden;">
        <!-- Table Header -->
        <div style="padding: 1.5rem; border-bottom: 2px solid #f0f0f0; background: #fafafa;">
            <h5 style="margin: 0; color: #667eea; font-weight: 700; font-size: 1.1rem;">
                <i class="bi bi-list-ul me-2"></i>All Rooms
                <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3); margin-left: 0.5rem;">{{ $rooms->total() }}</span>
            </h5>
        </div>
        
        <!-- Table Body -->
        <div style="overflow-x: auto;">
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
                                            onclick="confirmDelete('{{ $room->id }}')"
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
            <div style="padding: 1.5rem; border-top: 2px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                <div style="font-size: 0.9rem; color: #666;">
                    Showing {{ $rooms->firstItem() ?? 0 }} to {{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }} rooms
                </div>
                <div>
                    {{ $rooms->links() }}
                </div>
            </div>
            @else
            <div style="padding: 3rem 2rem; text-align: center;">
                <div style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"><i class="bi bi-inbox"></i></div>
                <h5 style="color: #999; margin-bottom: 0.5rem;">No rooms found</h5>
                <p style="color: #bbb; margin: 0;">Try adjusting your filters or add a new room.</p>
                @if(in_array(strtoupper(Auth::user()->role), ['OWNER', 'MANAGER']))
                <button type="button"
                        onclick='window.location.href="{{ route(strtolower(Auth::user()->role) . ".rooms.create") }}"'
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; margin-top: 1rem; cursor: pointer; transition: all 0.3s ease; line-height: 1.5;">
                    <i class="bi bi-plus-circle me-1"></i>Add First Room
                </button>
                @endif
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    /* Enhanced Styles - Same as Bookings Dashboard */
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

    /* Add New Room Button Styling */
    button[onclick*="rooms/create"] {
        cursor: pointer !important;
        pointer-events: auto !important;
        user-select: none;
        position: relative;
        z-index: 10;
    }

    button[onclick*="rooms/create"]:hover {
        opacity: 0.95 !important;
    }

    button[onclick*="rooms/create"]:active {
        transform: scale(0.98) !important;
    }

    a[href*="rooms/create"] {
        cursor: pointer !important;
        pointer-events: auto !important;
        user-select: none;
    }

    a[href*="rooms/create"]:hover {
        opacity: 0.95;
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
