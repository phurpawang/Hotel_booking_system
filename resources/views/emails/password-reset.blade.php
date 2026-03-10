<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body h2 {
            color: #333;
            font-size: 20px;
            margin-top: 0;
        }
        .email-body p {
            margin: 15px 0;
        }
        .reset-button {
            display: block;
            width: fit-content;
            margin: 30px auto;
            padding: 15px 40px;
            background-color: #667eea;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .reset-button:hover {
            background-color: #5568d3;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .warning-text {
            color: #dc3545;
            font-weight: bold;
        }
        .link-box {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔐 Password Reset Request</h1>
            <p style="margin: 5px 0 0 0;">Bhutan Hotel Booking System</p>
        </div>
        
        <div class="email-body">
            <h2>Hello!</h2>
            
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <p>Click the button below to reset your password:</p>
            
            <a href="{{ $resetUrl }}" class="reset-button">Reset Password</a>
            
            <div class="info-box">
                <p style="margin: 0;"><strong>⏰ This link will expire in 60 minutes</strong></p>
            </div>
            
            <p>If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
            
            <div class="link-box">
                <small>{{ $resetUrl }}</small>
            </div>
            
            <div class="info-box">
                <p class="warning-text" style="margin: 0;">
                    ⚠️ If you did not request a password reset, no further action is required. Your password remains secure.
                </p>
            </div>
            
            <p><strong>Security Tips:</strong></p>
            <ul>
                <li>Never share your password with anyone</li>
                <li>Use a strong password with at least 8 characters</li>
                <li>Don't reuse passwords from other websites</li>
            </ul>
            
            <p>If you have any questions or concerns, please contact our support team.</p>
            
            <p>Best regards,<br>
            <strong>Bhutan Hotel Booking System Team</strong></p>
        </div>
        
        <div class="email-footer">
            <p>This is an automated email, please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Bhutan Hotel Booking System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
