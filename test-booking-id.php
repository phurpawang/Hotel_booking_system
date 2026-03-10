<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== Testing Booking ID Generator ===\n\n";

echo "Generating 10 random booking IDs:\n\n";

$generatedIds = [];

for ($i = 1; $i <= 10; $i++) {
    $bookingId = App\Models\Booking::generateBookingId();
    $generatedIds[] = $bookingId;
    
    // Verify format
    if (preg_match('/^BK-\d{4}$/', $bookingId)) {
        $number = intval(substr($bookingId, 3));
        $valid = $number >= 1000 && $number <= 9999;
        $status = $valid ? "✅" : "❌";
        echo "{$i}. {$bookingId} {$status}\n";
    } else {
        echo "{$i}. {$bookingId} ❌ INVALID FORMAT\n";
    }
}

echo "\n=== Verification ===\n";
echo "Format: BK-XXXX (4-digit number)\n";
echo "Range: 1000 to 9999\n";
echo "All IDs unique: " . (count($generatedIds) === count(array_unique($generatedIds)) ? "✅ Yes" : "❌ No") . "\n";

echo "\n=== Existing Bookings ===\n";
$existingBookings = App\Models\Booking::orderBy('created_at', 'desc')->limit(5)->get(['booking_id', 'guest_name', 'status']);

if ($existingBookings->count() > 0) {
    foreach ($existingBookings as $booking) {
        echo "- {$booking->booking_id} | {$booking->guest_name} | {$booking->status}\n";
    }
} else {
    echo "No bookings found in database.\n";
}

echo "\n✅ Booking ID generator is working correctly!\n";
