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
            margin-bottom: 20px;
        }
        .section h2 {
            color: #333;
            font-size: 18px;
            margin: 0 0 10px 0;
            border-bottom: 2px solid #003580;
            padding-bottom: 10px;
        }
        .hotel-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 15px 0;
            border-radius: 4px;
        }
        .hotel-details p {
            margin: 8px 0;
            color: #333;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .success-badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 600;
            margin: 10px 0;
        }
        a {
            color: #003580;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Hotel Registration Approved</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $hotel->owner->name ?? 'Hotel Owner' }},</p>
            
            <p>Congratulations! Your hotel registration has been <strong>approved</strong> by the BHBS (Bhutan Booking System) administration team.</p>
            
            <div class="success-badge">✓ APPROVED</div>
            
            <div class="section">
                <h2>Hotel Information</h2>
                <div class="hotel-details">
                    <p><span class="label">Hotel Name:</span> {{ $hotel->name }}</p>
                    <p><span class="label">Hotel ID:</span> {{ $hotel->hotel_id }}</p>
                    <p><span class="label">Location:</span> {{ $hotel->dzongkhag }}</p>
                    <p><span class="label">Email:</span> {{ $hotel->email }}</p>
                    <p><span class="label">Phone:</span> {{ $hotel->phone }}</p>
                </div>
            </div>
            
            <div class="section">
                <h2>What's Next?</h2>
                <p>Your hotel is now approved and active on the BHBS platform. You can now:</p>
                <ul>
                    <li>Manage your hotel's details and amenities</li>
                    <li>Add and manage rooms</li>
                    <li>View and manage bookings</li>
                    <li>Track payments and commissions</li>
                </ul>
            </div>
            
            <div class="section">
                <h2>Login to Your Dashboard</h2>
                <p>Visit the BHBS portal to manage your hotel:</p>
                <p><a href="http://127.0.0.1:8000/hotel/login" style="background-color: #003580; color: white; padding: 10px 20px; border-radius: 4px; display: inline-block;">Access Hotel Dashboard</a></p>
            </div>
            
            <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>
            <strong>BHBS Administration Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Bhutan Booking System. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
