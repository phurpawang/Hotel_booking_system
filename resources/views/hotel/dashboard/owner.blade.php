<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - {{ $hotelName ?? 'Hotel' }}</title>
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
        
        .stat-card.red { border-left: 4px solid #dc3545; }
        .stat-card.red .stat-icon { color: #dc3545; }

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
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #003580;
            color: #fff;
        }

        .btn-primary:hover {
            background: #002659;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
            <div>
                <h1 style="margin: 0; font-size: 28px; color: #333;">Owner Dashboard</h1>
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


    </div>
</body>
</html>
