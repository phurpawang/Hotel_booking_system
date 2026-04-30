@extends(
    strtoupper(Auth::user()->role) === 'MANAGER' ? 'manager.layouts.app' :
    (in_array(strtoupper(Auth::user()->role), ['RECEPTIONIST', 'RECEPTION']) ? 'reception.layouts.app' : 'layouts.owner-bootstrap')
)

@section('title', 'Reports')

@section('header')
    <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Reports & Analytics</h2>
    <p class="text-gray-600 text-sm mt-1">{{ $hotel->name }}</p>
@endsection

@section('content')
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Dashboard Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem 2.5rem; border-radius: 20px; margin-bottom: 2.5rem; box-shadow: 0 10px 40px rgba(102, 126, 234, 0.35); animation: slideInDown 0.5s ease-out;">
        <h2 style="margin: 0; font-weight: 700; font-size: 2.2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-chart-bar me-3"></i>Reports & Analytics</h2>
        <p style="margin: 0.5rem 0 0 0; opacity: 0.95; font-size: 1.05rem;">{{ $hotel->name }}</p>
    </div>

    <!-- Date Range Filter Section -->
    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 2.5rem; border-left: 5px solid #667eea; animation: slideInUp 0.5s ease-out;">
        <h6 style="color: #667eea; font-weight: 700; margin-bottom: 1.5rem; font-size: 1.1rem;"><i class="fas fa-filter me-2"></i>Filter Report By Date Range</h6>
        <form method="GET" action="{{ route(strtolower(Auth::user()->role) . '.reports') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: flex-end;">
            <div>
                <label style="display: block; color: #667eea; font-weight: 700; margin-bottom: 0.7rem; font-size: 0.95rem;"><i class="fas fa-calendar-alt me-2"></i>From Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" required style="width: 100%; padding: 0.9rem; border: 2px solid #e0e6ed; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; background: white;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e6ed'; this.style.boxShadow='none'">
            </div>
            <div>
                <label style="display: block; color: #667eea; font-weight: 700; margin-bottom: 0.7rem; font-size: 0.95rem;"><i class="fas fa-calendar-check me-2"></i>To Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" required style="width: 100%; padding: 0.9rem; border: 2px solid #e0e6ed; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; background: white;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'" onblur="this.style.borderColor='#e0e6ed'; this.style.boxShadow='none'">
            </div>
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.9rem 2.5rem; border-radius: 10px; border: none; font-weight: 700; cursor: pointer; transition: all 0.3s ease; white-space: nowrap; font-size: 1rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.3)'">
                <i class="fas fa-search me-2"></i>Generate Report
            </button>
        </form>
    </div>

    @if($revenueData)
    <!-- Revenue Report (Owner Only) -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 2.5rem; overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.6s ease-out;">
        <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 1.8rem; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-weight: 700; font-size: 1.2rem;"><i class="fas fa-wallet me-2"></i>Revenue Report</h5>
            <a href="{{ route(strtolower(Auth::user()->role) . '.reports.export.revenue') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" style="background: rgba(255,255,255,0.25); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.background='rgba(255,255,255,0.35)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(0)'">
                <i class="fas fa-download"></i>Export
            </a>
        </div>
        <div style="padding: 2rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">💰 Total Revenue</div>
                    <div style="font-size: 2rem; font-weight: 800;">Nu. {{ number_format($revenueData['total_revenue'] ?? 0, 0) }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">✅ Paid Bookings</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $revenueData['paid_bookings'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">⏳ Pending Payments</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $revenueData['pending_payments'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">📊 Avg Value</div>
                    <div style="font-size: 2rem; font-weight: 800;">Nu. {{ number_format($revenueData['average_booking_value'] ?? 0, 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Booking Report -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 2.5rem; overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.7s ease-out;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.8rem; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-weight: 700; font-size: 1.2rem;"><i class="fas fa-calendar-check me-2"></i>Booking Report</h5>
            <a href="{{ route(strtolower(Auth::user()->role) . '.reports.export.bookings') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" style="background: rgba(255,255,255,0.25); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.background='rgba(255,255,255,0.35)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(0)'">
                <i class="fas fa-download"></i>Export
            </a>
        </div>
        <div style="padding: 2rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">📅 Total Bookings</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $bookingData['total_bookings'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">✅ Confirmed</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $bookingData['confirmed'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">🏠 Checked In</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $bookingData['checked_in'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">❌ Cancelled</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $bookingData['cancelled'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Occupancy Report -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 2.5rem; overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.8s ease-out;">
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 1.8rem; display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-weight: 700; font-size: 1.2rem;"><i class="fas fa-door-open me-2"></i>Occupancy Report</h5>
            <a href="{{ route(strtolower(Auth::user()->role) . '.reports.export.occupancy') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" style="background: rgba(255,255,255,0.25); color: white; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.background='rgba(255,255,255,0.35)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(0)'">
                <i class="fas fa-download"></i>Export
            </a>
        </div>
        <div style="padding: 2rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">🏨 Total Rooms</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $occupancyData['total_rooms'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">🔒 Occupied</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ $occupancyData['occupied_rooms'] ?? 0 }}</div>
                </div>
                <div style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); padding: 1.8rem; border-radius: 12px; color: white; text-align: center; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="color: rgba(255,255,255,0.85); font-size: 0.9rem; margin-bottom: 0.8rem; font-weight: 600;">📈 Occupancy Rate</div>
                    <div style="font-size: 2rem; font-weight: 800;">{{ number_format($occupancyData['occupancy_rate'] ?? 0, 1) }}%</div>
                </div>
            </div>
            
            <!-- Occupancy Progress Bar -->
            <div>
                <div style="margin-bottom: 0.8rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #667eea; font-weight: 700; font-size: 0.95rem;">Occupancy Progress</span>
                    <span style="color: #f59e0b; font-weight: 700; font-size: 1rem;">{{ number_format($occupancyData['occupancy_rate'] ?? 0, 1) }}%</span>
                </div>
                <div style="background: #f3f4f6; border-radius: 12px; height: 45px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.06); border: 2px solid #e5e7eb;">
                    <div class="occupancy-bar" 
                         style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1rem; transition: all 0.3s ease; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);"
                         data-occupancy="{{ (int)($occupancyData['occupancy_rate'] ?? 0) }}">
                        <span style="text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">{{ number_format($occupancyData['occupancy_rate'] ?? 0, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .occupancy-bar {
        border-radius: 12px;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        color: #667eea;
    }
</style>

@endsection
