@extends('admin.layout')

@section('title', 'Payments Management')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
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
        border-top: 4px solid #11998e;
    }
    .filter-section h5 {
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        font-size: 16px;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
    }
    .stat-card:nth-child(1) {
        border-left-color: #11998e;
        background: linear-gradient(135deg, rgba(17, 153, 142, 0.05) 0%, white 100%);
    }
    .stat-card:nth-child(2) {
        border-left-color: #fa709a;
        background: linear-gradient(135deg, rgba(250, 112, 154, 0.05) 0%, white 100%);
    }
    .stat-card:nth-child(3) {
        border-left-color: #868f96;
        background: linear-gradient(135deg, rgba(134, 143, 150, 0.05) 0%, white 100%);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
    }
    .stat-icon:nth-child(1) { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .stat-details h3 {
        margin: 0 0 5px 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    .stat-details p {
        margin: 0;
        font-size: 13px;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .data-table tbody tr:nth-child(even) {
        background: #f9f9f9;
    }
    .data-table tbody tr:nth-child(odd) {
        background: white;
    }
</style>

<div class="header-section">
    <h1><i class="fas fa-wallet"></i>Payments Management</h1>
</div>

<!-- Filters -->
<div class="filter-section">
    <h5><i class="fas fa-filter" style="color: #11998e; margin-right: 8px;"></i>Filter Payments</h5>
    <form action="{{ route('admin.payments.index') }}" method="GET" class="filter-form">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <div style="display: flex; flex-direction: column;">
                <label style="font-size: 12px; font-weight: 600; color: #666; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Payment Status</label>
                <select name="payment_status" class="form-control" style="padding: 12px 15px !important; border: 2px solid #e0e0e0 !important; border-radius: 8px !important; transition: all 0.3s ease;">
                    <option value="">All Payment Status</option>
                    <option value="PENDING" {{ request('payment_status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="PAID" {{ request('payment_status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                    <option value="REFUNDED" {{ request('payment_status') == 'REFUNDED' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-filter" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; color: white !important; padding: 12px 25px !important; border: none !important; border-radius: 8px !important; font-weight: 600 !important; cursor: pointer !important; display: inline-flex; align-items: center; gap: 8px;"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('admin.payments.index') }}" class="btn-reset" style="background: #f0f0f0 !important; color: #666 !important; padding: 12px 25px !important; border: 2px solid #e0e0e0 !important; border-radius: 8px !important; font-weight: 600 !important; cursor: pointer !important; text-decoration: none !important; display: inline-flex; align-items: center; gap: 8px;"><i class="fas fa-redo"></i> Reset</a>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($payments->where('payment_status', 'PAID')->sum('total_price'), 2) }}</h3>
            <p>Total Paid</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($payments->where('payment_status', 'PENDING')->sum('total_price'), 2) }}</h3>
            <p>Pending Payments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%);">
            <i class="fas fa-undo"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($payments->where('payment_status', 'REFUNDED')->sum('total_price'), 2) }}</h3>
            <p>Total Refunded</p>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="dashboard-card" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); border: none;">
    <div class="card-body" style="padding: 0;">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; font-weight: 600;">
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Booking ID</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Guest Name</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Hotel</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Amount</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Method</th>
                            <th style="padding: 18px 15px; text-align: left; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Status</th>
                            <th style="padding: 18px 15px; text-align: center; font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $key => $payment)
                            <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" data-index="{{ $key }}" onmouseover="this.style.background='#f0f6ff'" onmouseout="this.style.background=this.dataset.index % 2 == 0 ? '#f9f9f9' : 'white'">
                                <td style="padding: 16px 15px; font-weight: 600; color: #11998e;">#{{ $payment->id }}</td>
                                <td style="padding: 16px 15px;">
                                    <div style="font-weight: 600; color: #333; margin-bottom: 4px;">{{ $payment->guest_name }}</div>
                                    <div style="font-size: 12px; color: #999;">{{ $payment->guest_email }}</div>
                                </td>
                                <td style="padding: 16px 15px; color: #555;">{{ $payment->hotel->name ?? 'N/A' }}</td>
                                <td style="padding: 16px 15px; font-weight: 600; color: #11998e;">Nu. {{ number_format($payment->total_price, 2) }}</td>
                                <td style="padding: 16px 15px; color: #555;">{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                <td style="padding: 16px 15px;">
                                    @if($payment->payment_status == 'PAID')
                                        <span class="badge" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-check-circle" style="margin-right: 5px;"></i>Paid</span>
                                    @elseif($payment->payment_status == 'PENDING')
                                        <span class="badge" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-clock" style="margin-right: 5px;"></i>Pending</span>
                                    @elseif($payment->payment_status == 'REFUNDED')
                                        <span class="badge" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;"><i class="fas fa-undo" style="margin-right: 5px;"></i>Refunded</span>
                                    @endif
                                </td>
                                <td style="padding: 16px 15px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        @if($payment->payment_screenshot)
                                            <a href="{{ asset('storage/' . $payment->payment_screenshot) }}" target="_blank" class="btn-action" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';"><i class="fas fa-image"></i></a>
                                        @endif
                                        <a href="{{ route('admin.reservations.show', $payment->id) }}" class="btn-action" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 8px; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 12px rgba(79, 172, 254, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='none';"><i class="fas fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrapper" style="padding: 20px 15px; border-top: 1px solid #f0f0f0; background: #f9f9f9;">
                {{ $payments->links() }}
            </div>
        @else
            <div style="padding: 60px 20px; text-align: center;">
                <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 20px; display: block;"></i>
                <p class="no-data" style="color: #999; font-size: 16px;">No payment records found</p>
            </div>
        @endif
    </div>
</div>
@endsection
