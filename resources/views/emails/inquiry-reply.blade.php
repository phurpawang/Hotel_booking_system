<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e5a3f 0%, #2d8659 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .question-box {
            background-color: #f0f4f8;
            border-left: 4px solid #1e5a3f;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .question-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .question-text {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }
        .answer-box {
            background-color: #f0f8f5;
            border-left: 4px solid #2d8659;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .answer-label {
            font-size: 12px;
            color: #1e5a3f;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .answer-text {
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .divider {
            border-top: 2px solid #eee;
            margin: 25px 0;
        }
        .hotel-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            font-size: 13px;
            color: #666;
        }
        .hotel-info strong {
            color: #1e5a3f;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #1e5a3f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: 600;
        }
        .button:hover {
            background-color: #2d8659;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Your Question Has Been Answered! ✓</h1>
            <p>{{ $hotelName }} has replied to your inquiry</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <p>Hi <strong>{{ $inquiry->guest_name }}</strong>,</p>
                <p>Thank you for your interest in {{ $hotelName }}! We're happy to answer your question.</p>
            </div>

            <!-- Original Question Section -->
            <div class="question-box">
                <div class="question-label">📋 Your Question:</div>
                <div class="question-text">{{ $inquiry->question }}</div>
            </div>

            <!-- Hotel's Answer Section -->
            <div class="answer-box">
                <div class="answer-label">💬 Hotel's Answer:</div>
                <div class="answer-text">{{ $inquiry->reply }}</div>
            </div>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Hotel Information -->
            <div class="hotel-info">
                <p><strong>Hotel:</strong> {{ $hotelName }}</p>
                <p><strong>Status:</strong> <span style="color: #2d8659; font-weight: 600;">✓ Answered</span></p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('M d, Y') }}</p>
            </div>

            <!-- Additional Info -->
            <p style="margin-top: 20px; font-size: 14px; color: #666;">
                If you have any more questions about {{ $hotelName }}, feel free to reach out to them directly. 
                We're here to help make your stay memorable!
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bhutan Hotel Booking System. All rights reserved.</p>
            <p>This is an automated email, please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
