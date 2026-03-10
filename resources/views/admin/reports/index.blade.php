@extends('admin.layout')

@section('title', 'Reports & Analytics')

@section('content')
<div class="dashboard-header">
    <h1>Reports & Analytics</h1>
</div>

<!-- Summary Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #28a745;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-details">
            <h3>Nu. {{ number_format($totalRevenue, 2) }}</h3>
            <p>Total Revenue</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #007bff;">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-details">
            <h3>{{ $totalReservations }}</h3>
            <p>Total Reservations</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #ffc107;">
            <i class="fas fa-hotel"></i>
        </div>
        <div class="stat-details">
            <h3>{{ $totalHotels }}</h3>
            <p>Registered Hotels</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #17a2b8;">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyData = @json($monthlyRevenue);
    
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
