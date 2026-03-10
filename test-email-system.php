<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

echo "========================================\n";
echo "  EMAIL SYSTEM VERIFICATION TEST\n";
echo "========================================\n\n";

// Display configuration
echo "📧 SMTP Configuration:\n";
echo "   Host: " . config('mail.mailers.smtp.host') . "\n";
echo "   Port: " . config('mail.mailers.smtp.port') . "\n";
echo "   Username: " . config('mail.mailers.smtp.username') . "\n";
echo "   Encryption: " . config('mail.mailers.smtp.encryption') . "\n";
echo "   From: " . config('mail.from.address') . "\n\n";

// Test email sending
echo "🧪 Testing Email Sending...\n";

try {
    Mail::raw('This is a test email from BHBS Hotel Booking System.', function ($message) {
        $message->to(config('mail.from.address'))
                ->subject('BHBS - Email System Test');
    });
    
    echo "✅ SUCCESS: Test email sent successfully!\n";
    echo "   Email sent to: " . config('mail.from.address') . "\n";
    echo "   Check your inbox (and spam folder)\n\n";
    
    Log::info('Test email sent successfully', [
        'to' => config('mail.from.address'),
        'time' => now()
    ]);
    
    echo "✅ Email system is WORKING!\n";
    echo "   - Booking confirmation emails will be sent automatically\n";
    echo "   - Password reset emails will be sent automatically\n\n";
    
    exit(0);
    
} catch (\Exception $e) {
    echo "❌ ERROR: Failed to send email\n";
    echo "   Error: " . $e->getMessage() . "\n\n";
    
    echo "🔍 Troubleshooting:\n";
    echo "   1. Check if Gmail App Password is correct\n";
    echo "   2. Verify 2-Factor Authentication is enabled\n";
    echo "   3. Check internet connection\n";
    echo "   4. Review storage/logs/laravel.log for details\n\n";
    
    Log::error('Test email failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    exit(1);
}
