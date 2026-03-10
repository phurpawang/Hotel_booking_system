@extends('admin.layout')

@section('title', 'Reservations Management')

@section('content')
<div class="dashboard-header">
    <h1>Reservations Management</h1>
</div>

<!-- Filters -->
<div class="dashboard-card" style="margin-bottom: 20px;">
    <div class="card-body">
        <form action="{{ route('admin.reservations.index') }}" method="GET" class="filter-form">
            <div class="filter-grid">
                <div class="filter-item">
                    <input type="text" name="search" placeholder="Search by guest name..." value="{{ request('search') }}" class="form-control">
                </div>
                <div class="filter-item">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
                        <option value="CHECKED_IN" {{ request('status') == 'CHECKED_IN' ? 'selected' : '' }}>Checked In</option>
                        <option value="CHECKED_OUT" {{ request('status') == 'CHECKED_OUT' ? 'selected' : '' }}>Checked Out</option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="filter-item">
                    <select name="payment_status" class="form-control">
                        <option value="">All Payments</option>
                        <option value="PENDING" {{ request('payment_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="PAID" {{ request('payment_status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                        <option value="REFUNDED" {{ request('payment_status') == 'REFUNDED' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="filter-item">
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
                    <a href="{{ route('admin.reservations.index') }}" class="btn-reset"><i class="fas fa-redo"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reservations Table -->
<div class="dashboard-card">
    <div class="card-body">
        @if($reservations->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest Name</th>
                            <th>Hotel</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>#{{ $reservation->id }}</td>
                                <td>
                                    <strong>{{ $reservation->guest_name }}</strong><br>
                                    <small class="text-muted">{{ $reservation->guest_email }}</small>
                                </td>
                                <td>{{ $reservation->hotel->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
                                <td>Nu. {{ number_format($reservation->total_price, 2) }}</td>
                                <td>
                                    @if($reservation->payment_status == 'PAID')
                                        <span class="badge badge-success">Paid</span>
                                    @elseif($reservation->payment_status == 'PENDING')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($reservation->payment_status == 'REFUNDED')
                                        <span class="badge badge-secondary">Refunded</span>
                                    @endif
                                </td>
                                <td>
                                    @if(strtoupper($reservation->status) == 'CONFIRMED')
                                        <span class="badge badge-success">Confirmed</span>
                                    @elseif(strtoupper($reservation->status) == 'PENDING')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif(strtoupper($reservation->status) == 'CANCELLED')
                                        <span class="badge badge-danger">Cancelled</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($reservation->status) }}</span>
                                    @endif
                                </td>
                                <td class="action-buttons">
                                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" data-confirm="Are you sure you want to delete this reservation?">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper">
                {{ $reservations->links() }}
            </div>
        @else
            <p class="no-data">No reservations found</p>
        @endif
    </div>
</div>
@endsection
