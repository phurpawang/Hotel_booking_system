@extends('admin.layout')

@section('title', 'Reservations Management')

@section('content')
<!-- Header Section -->
<style>
    .header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .header-section h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .header-section i {
        font-size: 40px;
    }
    
    .filter-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-top: 4px solid #667eea;
    }
    
    .filter-section h5 {
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        font-size: 16px;
    }
    
    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-group input,
    .filter-group select {
        padding: 12px 15px !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        transition: all 0.3s ease;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        outline: none;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        padding: 12px 25px !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
    }
    
    .btn-reset {
        background: #f0f0f0 !important;
        color: #666 !important;
        padding: 12px 25px !important;
        border: 2px solid #e0e0e0 !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-reset:hover {
        background: #e0e0e0 !important;
        border-color: #999 !important;
    }
    
    .filter-buttons {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }
    
    .data-table tbody tr:nth-child(even) {
        background: #f9f9f9;
    }
    
    .data-table tbody tr:nth-child(odd) {
        background: white;
    }
</style>

<div class="header-section">
    <h1><i class="fas fa-calendar-check"></i>Reservations Management</h1>
</div>

<!-- Filters -->
<div class="filter-section">
    <h5><i class="fas fa-filter" style="color: #667eea; margin-right: 8px;"></i>Search & Filter</h5>
    <form action="{{ route('admin.reservations.index') }}" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="filter-group">
                <label for="search">Guest Name</label>
                <input type="text" id="search" name="search" placeholder="Enter guest name..." value="{{ request('search') }}" class="form-control">
            </div>
            <div class="filter-group">
                <label for="status">Reservation Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
                    <option value="CHECKED_IN" {{ request('status') == 'CHECKED_IN' ? 'selected' : '' }}>Checked In</option>
                    <option value="CHECKED_OUT" {{ request('status') == 'CHECKED_OUT' ? 'selected' : '' }}>Checked Out</option>
                    <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="payment_status">Payment Status</label>
                <select id="payment_status" name="payment_status" class="form-control">
                    <option value="">All Payments</option>
                    <option value="PENDING" {{ request('payment_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="PAID" {{ request('payment_status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                    <option value="REFUNDED" {{ request('payment_status') == 'REFUNDED' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
        </div>
        <div class="filter-buttons">
            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('admin.reservations.index') }}" class="btn-reset"><i class="fas fa-redo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Reservations Table -->
<div class="dashboard-card" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); border: none;">
    <div class="card-body" style="padding: 0;">
        @if($reservations->count() > 0)
            <div class="table-responsive">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600;">
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">ID</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Guest Name</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Hotel</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Check-in</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Check-out</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Amount</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Payment</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Status</th>
                            <th style="padding: 18px 15px; text-align: center; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $key => $reservation)
                            <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" data-index="{{ $key }}" onmouseover="this.style.background='#f0f6ff'" onmouseout="this.style.background=this.dataset.index % 2 == 0 ? '#f9f9f9' : 'white'">
                                <td style="padding: 16px 15px; font-weight: 600; color: #667eea;">#{{ $reservation->id }}</td>
                                <td style="padding: 16px 15px;">
                                    <div style="font-weight: 600; color: #333; margin-bottom: 4px;">{{ $reservation->guest_name }}</div>
                                    <div style="font-size: 12px; color: #999;">{{ $reservation->guest_email }}</div>
                                </td>
                                <td style="padding: 16px 15px; color: #555;">{{ $reservation->hotel->name ?? 'N/A' }}</td>
                                <td style="padding: 16px 15px; color: #555;">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
                                <td style="padding: 16px 15px; color: #555;">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
                                <td style="padding: 16px 15px; font-weight: 600; color: #11998e;">Nu. {{ number_format($reservation->total_price, 2) }}</td>
                                <td style="padding: 16px 15px;">
                                    @if($reservation->payment_status == 'PAID')
                                        <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-check-circle" style="margin-right: 5px;"></i>Paid
                                        </span>
                                    @elseif($reservation->payment_status == 'PENDING')
                                        <span class="badge" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-clock" style="margin-right: 5px;"></i>Pending
                                        </span>
                                    @elseif($reservation->payment_status == 'REFUNDED')
                                        <span class="badge" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-redo" style="margin-right: 5px;"></i>Refunded
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 16px 15px;">
                                    @if(strtoupper($reservation->status) == 'CONFIRMED')
                                        <span class="badge" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-check" style="margin-right: 5px;"></i>Confirmed
                                        </span>
                                    @elseif(strtoupper($reservation->status) == 'PENDING')
                                        <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-hourglass-half" style="margin-right: 5px;"></i>Pending
                                        </span>
                                    @elseif(strtoupper($reservation->status) == 'CHECKED_IN')
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i>Checked In
                                        </span>
                                    @elseif(strtoupper($reservation->status) == 'CHECKED_OUT')
                                        <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-sign-out-alt" style="margin-right: 5px;"></i>Checked Out
                                        </span>
                                    @elseif(strtoupper($reservation->status) == 'CANCELLED')
                                        <span class="badge" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            <i class="fas fa-times-circle" style="margin-right: 5px;"></i>Cancelled
                                        </span>
                                    @else
                                        <span class="badge" style="background: #ccc; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">{{ ucfirst($reservation->status) }}</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 15px; text-align: center;">
                                    <div class="action-buttons" style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="btn-action btn-view" title="View" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 12px rgba(79, 172, 254, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Delete" data-confirm="Are you sure you want to delete this reservation?" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 12px rgba(235, 51, 73, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper" style="padding: 20px 15px; border-top: 1px solid #f0f0f0; background: #f9f9f9;">
                {{ $reservations->links() }}
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center;">
                <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 20px; display: block;"></i>
                <p class="no-data" style="color: #999; font-size: 16px;">No reservations found</p>
            </div>
        @endif
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .filter-row {
            grid-template-columns: 1fr !important;
        }
        
        .table-responsive {
            font-size: 13px;
        }
    }

    .data-table tbody tr:nth-child(even) {
        background: #f9f9f9;
    }

    .data-table tbody tr:nth-child(odd) {
        background: white;
    }
</style>
@endsection
