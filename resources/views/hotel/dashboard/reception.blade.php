<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception Dashboard - {{ $hotelName ?? 'Hotel' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .stat-card.blue { border-left: 4px solid #003580; }
        .stat-card.blue .stat-icon { color: #003580; }
        
        .stat-card.green { border-left: 4px solid #28a745; }
        .stat-card.green .stat-icon { color: #28a745; }
        
        .stat-card.orange { border-left: 4px solid #fd7e14; }
        .stat-card.orange .stat-icon { color: #fd7e14; }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin: 30px 0 15px;
            color: #333;
            border-bottom: 2px solid #003580;
            padding-bottom: 10px;
        }

        .data-table {
            width: 100%;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .data-table th {
            background: #003580;
            color: #fff;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.2s;
            margin-right: 5px;
        }

        .btn-success {
            background: #28a745;
            color: #fff;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-primary {
            background: #003580;
            color: #fff;
        }

        .btn-primary:hover {
            background: #002659;
        }

        .search-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .search-box input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-box input:focus {
            outline: none;
            border-color: #003580;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
            <div>
                <h1 style="margin: 0; font-size: 28px; color: #333;">Reception Dashboard</h1>
                <p style="margin: 5px 0 0; color: #666;">{{ $hotelName ?? 'Hotel Management' }}</p>
            </div>
            <div>
                <span style="margin-right: 15px; color: #666;">
                    <i class="fas fa-user"></i> {{ session('hotel_user_name') }}
                </span>
                <form action="{{ route('hotel.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Search -->
        <div class="search-box">
            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333;">
                <i class="fas fa-search"></i> Quick Reservation Lookup
            </label>
            <input type="text" id="searchInput" placeholder="Search by Booking ID, Guest Name, or Room Number..." onkeyup="searchTable()">
        </div>

        <!-- Statistics Cards -->
        <div class="dashboard-grid">
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fas fa-sign-in-alt"></i></div>
                <div class="stat-value">{{ $todayCheckIns }}</div>
                <div class="stat-label">Today's Check-ins</div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon"><i class="fas fa-sign-out-alt"></i></div>
                <div class="stat-value">{{ $todayCheckOuts }}</div>
                <div class="stat-label">Today's Check-outs</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-value">{{ $pendingBookings }}</div>
                <div class="stat-label">Pending Bookings</div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Recent Bookings</h2>
        <table class="data-table" id="bookingsTable">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Guest Email</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                <tr>
                    <td><strong>#{{ $booking->id }}</strong></td>
                    <td>{{ $booking->guest_name }}</td>
                    <td>{{ $booking->guest_email }}</td>
                    <td>{{ $booking->room->room_number ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>₹{{ number_format($booking->total_amount, 2) }}</td>
                    <td>
                        @if($booking->status == 'CONFIRMED')
                            <span class="badge badge-success">Confirmed</span>
                        @elseif($booking->status == 'PENDING')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($booking->status == 'CHECKED_IN')
                            <span class="badge badge-info">Checked In</span>
                        @elseif($booking->status == 'CHECKED_OUT')
                            <span class="badge badge-success">Checked Out</span>
                        @elseif($booking->status == 'CANCELLED')
                            <span class="badge badge-danger">Cancelled</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($booking->status == 'CONFIRMED')
                                <form action="{{ route('hotel.booking.checkin', $booking->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" title="Check In Guest">
                                        <i class="fas fa-sign-in-alt"></i> Check In
                                    </button>
                                </form>
                            @elseif($booking->status == 'CHECKED_IN')
                                <form action="{{ route('hotel.booking.checkout', $booking->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" title="Check Out Guest">
                                        <i class="fas fa-sign-out-alt"></i> Check Out
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        No bookings found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('bookingsTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }

                if (found) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
