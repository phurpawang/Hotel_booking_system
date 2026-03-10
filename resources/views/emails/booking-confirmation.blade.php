<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.8;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 40px;
        }
        .content {
            white-space: pre-line;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .booking-details, .payment-info {
            margin-left: 0;
            padding-left: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
Dear {{ $booking->guest_name }},

Your booking has been successfully confirmed. Please find your booking details below.

<strong>Booking Details:</strong>
Booking ID: {{ $booking->booking_id }}
Hotel Name: {{ $booking->hotel->name ?? 'N/A' }}
Room Type: {{ $booking->room->room_type ?? 'N/A' }}
Check-in Date: {{ \Carbon\Carbon::parse($booking->check_in_date)->format('F d, Y') }}
Check-out Date: {{ \Carbon\Carbon::parse($booking->check_out_date)->format('F d, Y') }}

<strong>Payment Information:</strong>
Total Amount: Nu. {{ number_format($booking->total_price, 2) }}
Payment Method: {{ $booking->payment_method == 'ONLINE' ? 'Online Payment' : ($booking->payment_method == 'PAY_AT_HOTEL' ? 'Pay at Hotel' : $booking->payment_method) }}
Payment Status: {{ ucfirst(strtolower($booking->payment_status ?? 'Pending')) }}

Please keep your Booking ID for future reference during check-in or if you need to contact the hotel.

Thank you for choosing our Bhutan Hotel Booking System. We wish you a pleasant stay.

Best regards,
Bhutan Hotel Booking System
        </div>
    </div>
</body>
</html>
