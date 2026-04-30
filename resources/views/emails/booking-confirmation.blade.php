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
            border-left: 4px solid #28a745;
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
        .price-highlight {
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 4px;
            font-weight: 600;
            color: #28a745;
            font-size: 18px;
            text-align: center;
            margin: 15px 0;
        }
        .payment-status {
            background-color: #fff3cd;
            padding: 12px;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
            margin: 15px 0;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .confirmation-badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
            margin: 10px 0;
        }
        .important-note {
            background-color: #ffe6e6;
            padding: 12px;
            border-left: 4px solid #dc3545;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Booking Confirmation</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Your reservation has been confirmed</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $booking->guest_name }},</p>
            
            <p>Thank you for booking with us! Your reservation has been successfully confirmed. Please review your booking details below.</p>
            
            <div class="confirmation-badge">CONFIRMED</div>
            
            <div class="section">
                <h2>Booking Details</h2>
                <div class="booking-details">
                    <div class="detail-row">
                        <span class="label">Booking ID:</span>
                        <span class="value"><strong>{{ $booking->booking_id }}</strong></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Hotel:</span>
                        <span class="value">{{ $booking->hotel->name ?? 'N/A' }}</span>
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
                </div>
            </div>
            
            <div class="section">
                <h2>Payment Information</h2>
                <div class="price-highlight">
                    Total: Nu. {{ number_format($booking->total_price, 2) }}
                </div>
                <div class="payment-status">
                    <strong>Payment Method:</strong> {{ $booking->payment_method == 'ONLINE' ? 'Online Payment' : ($booking->payment_method == 'PAY_AT_HOTEL' ? 'Pay at Hotel' : $booking->payment_method) }}
                    <br>
                    <strong>Status:</strong> {{ ucfirst(strtolower($booking->payment_status ?? 'Pending')) }}
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
            
            <div class="important-note">
                <strong>⚠️ Important:</strong> Please keep your Booking ID for future reference. You will need it during check-in and for any communications with the hotel.
            </div>
            
            <p>If you have any questions about your booking, please contact the hotel directly or reply to this email.</p>
            
            <p>We wish you a wonderful stay!<br>
            <strong>BHBS Hotel Booking System</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Bhutan Hotel Booking System. All rights reserved.</p>
            <p>This is an automated email. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
