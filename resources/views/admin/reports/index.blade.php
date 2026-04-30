@extends('admin.layout')

@section('title', 'Reports & Analytics')

@section('content')
<style>
    .header-section {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
    }
    .header-section h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
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
    <h1><i class="fas fa-chart-line"></i>Reports & Analytics</h1>
</div>

<!-- Summary Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="border-left-color: #11998e; background: linear-gradient(135deg, rgba(17, 153, 142, 0.05) 0%, white 100%);">
        <div class="stat-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($totalRevenue, 2) }}</h3>
            <p>Total Revenue</p>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #667eea; background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, white 100%);">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-details">
            <h3>{{ $totalReservations }}</h3>
            <p>Total Reservations</p>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #fa709a; background: linear-gradient(135deg, rgba(250, 112, 154, 0.05) 0%, white 100%);">
        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <i class="fas fa-hotel"></i>
        </div>
        <div class="stat-details">
            <h3>{{ $totalHotels }}</h3>
            <p>Registered Hotels</p>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: #4facfe; background: linear-gradient(135deg, rgba(79, 172, 254, 0.05) 0%, white 100%);">
        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($monthlyRevenue->sum('revenue'), 2) }}</h3>
            <p>This Year Revenue</p>
        </div>
    </div>
</div>

<!-- Monthly Revenue Chart -->
<div class="dashboard-card">
    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Monthly Revenue (Last 12 Months)</h3>
    <div class="card-body">
        <canvas id="monthlyRevenueChart" style="max-height: 400px;"></canvas>
    </div>
</div>

<div class="dashboard-grid" style="margin-top: 20px;">
    <!-- Bookings by Status -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-tasks"></i> Bookings by Status</h3>
        <div class="card-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookingsByStatus as $status)
                        <tr>
                            <td>
                                @if(strtoupper($status->status) == 'CONFIRMED')
                                    <span class="badge badge-success">{{ ucfirst($status->status) }}</span>
                                @elseif(strtoupper($status->status) == 'PENDING')
                                    <span class="badge badge-warning">{{ ucfirst($status->status) }}</span>
                                @elseif(strtoupper($status->status) == 'CANCELLED')
                                    <span class="badge badge-danger">{{ ucfirst($status->status) }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($status->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $status->count }}</td>
                            <td>{{ round(($status->count / $totalReservations) * 100, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Revenue by Hotel -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-trophy"></i> Top 10 Hotels by Revenue</h3>
        <div class="card-body">
            @if($revenueByHotel->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Hotel</th>
                            <th>Revenue</th>
                            <th>Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenueByHotel as $hotel)
                            <tr>
                                <td>{{ $hotel->hotel_name }}</td>
                                <td>Nu. {{ number_format($hotel->revenue, 2) }}</td>
                                <td>{{ $hotel->total_bookings }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No revenue data available</p>
            @endif
        </div>
    </div>
</div>

<div id="monthlyData" style="display: none;">{{ json_encode($monthlyRevenue) }}</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyData = JSON.parse(document.getElementById('monthlyData').textContent);
    
    const labels = [];
    const data = [];
    
    // Get last 12 months
    for (let i = 11; i >= 0; i--) {
        const date = new Date();
        date.setMonth(date.getMonth() - i);
        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const key = `${year}-${String(month).padStart(2, '0')}`;
        
        labels.push(date.toLocaleString('default', { month: 'short', year: 'numeric' }));
        data.push(monthlyData[key] || 0);
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (Nu.)',
                data: data,
                borderColor: '#003580',
                backgroundColor: 'rgba(0, 53, 128, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: Nu. ' + context.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Nu. ' + value.toLocaleString('en-IN');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
