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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            border-bottom: 2px solid #dc3545;
            padding-bottom: 10px;
        }
        .hotel-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #dc3545;
            margin: 15px 0;
            border-radius: 4px;
        }
        .hotel-details p {
            margin: 8px 0;
            color: #333;
        }
        .reason-box {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
            border-radius: 4px;
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
        .rejection-badge {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 600;
            margin: 10px 0;
        }
        a {
            color: #dc3545;
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
            <h1>❌ Hotel Registration Rejected</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $hotel->owner->name ?? 'Hotel Owner' }},</p>
            
            <p>Thank you for submitting your hotel registration. Unfortunately, your hotel registration has been <strong>rejected</strong> by the BHBS (Bhutan Booking System) administration team after careful review.</p>
            
            <div class="rejection-badge">✗ REJECTED</div>
            
            <div class="section">
                <h2>Hotel Information</h2>
                <div class="hotel-details">
                    <p><span class="label">Hotel Name:</span> {{ $hotel->name }}</p>
                    <p><span class="label">Location:</span> {{ $hotel->dzongkhag }}</p>
                    <p><span class="label">Email:</span> {{ $hotel->email }}</p>
                    <p><span class="label">Phone:</span> {{ $hotel->phone }}</p>
                </div>
            </div>
            
            <div class="section">
                <h2>Rejection Reason</h2>
                <div class="reason-box">
                    <p><strong>{{ $reason ?? $hotel->rejection_reason ?? 'N/A' }}</strong></p>
                </div>
            </div>
            
            <div class="section">
                <h2>What to Do Next</h2>
                <p>We understand this may be disappointing. Here are your options:</p>
                <ul>
                    <li><strong>Appeal:</strong> If you believe this decision was made in error, you can contact our support team to appeal the decision.</li>
                    <li><strong>Reapply:</strong> You may resubmit your application with required corrections after addressing the issues mentioned above.</li>
                    <li><strong>Contact Support:</strong> Reach out to our team for clarification on the rejection reason.</li>
                </ul>
            </div>
            
            <p>If you have any questions or would like to discuss this decision, please contact us immediately.</p>
            
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
