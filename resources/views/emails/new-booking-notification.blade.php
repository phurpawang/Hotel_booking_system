<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #003580 0%, #0047ab 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            color: #333;
            font-size: 18px;
            margin: 0 0 15px 0;
            border-bottom: 2px solid #003580;
            padding-bottom: 10px;
        }
        .booking-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #0047ab;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #333;
            text-align: right;
        }
        .guest-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-left: 4px solid #2196f3;
            border-radius: 4px;
        }
        .payment-status {
            background-color: #fff3cd;
            padding: 12px;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .new-booking-badge {
            display: inline-block;
            background-color: #2196f3;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 600;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 New Booking Received</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">A new reservation has been made at your hotel</p>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>You have received a new booking. Here are the details:</p>
            
            <div class="new-booking-badge">NEW BOOKING</div>
            
            <div class="section">
                <h2>Booking Information</h2>
                <div class="booking-details">
                    <div class="detail-row">
                        <span class="label">Booking ID:</span>
                        <span class="value"><strong>{{ $booking->booking_id }}</strong></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Room Type:</span>
                        <span class="value">{{ $booking->room->room_type ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Number of Rooms:</span>
                        <span class="value">{{ $booking->num_rooms }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Check-in:</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('F d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Check-out:</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('F d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Number of Nights:</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h2>Guest Information</h2>
                <div class="guest-info">
                    <div class="detail-row">
                        <span class="label">Guest Name:</span>
                        <span class="value">{{ $booking->guest_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Email:</span>
                        <span class="value">{{ $booking->guest_email }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Phone:</span>
                        <span class="value">{{ $booking->guest_phone }}</span>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h2>Payment Information</h2>
                <div class="payment-status">
                    <strong>Total Amount:</strong> Nu. {{ number_format($booking->total_price, 2) }}<br>
                    <strong>Payment Method:</strong> {{ $booking->payment_method == 'ONLINE' ? 'Online Payment' : ($booking->payment_method == 'PAY_AT_HOTEL' ? 'Pay at Hotel' : $booking->payment_method) }}<br>
                    <strong>Payment Status:</strong> {{ ucfirst(strtolower($booking->payment_status ?? 'Pending')) }}
                </div>
            </div>
            
            @if($booking->special_requests)
            <div class="section">
                <h2>Special Requests</h2>
                <div class="booking-details">
                    <p style="margin: 0;">{{ $booking->special_requests }}</p>
                </div>
            </div>
            @endif
            
            <p>Booking Status: <strong>{{ ucfirst(strtolower($booking->status ?? 'Confirmed')) }}</strong></p>
            
            <p>Please log in to your hotel dashboard to manage this booking. If you have any questions, please contact the BHBS support team.</p>
            
            <p>Best regards,<br>
            <strong>BHBS Hotel Booking System</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Bhutan Hotel Booking System. All rights reserved.</p>
            <p>This is an automated email. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
