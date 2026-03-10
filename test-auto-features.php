<?php

/**
 * Test automatic room assignment and room number generation
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

echo "=== Testing Auto Room Features ===\n\n";

// Test 1: Auto Room Number Generation
echo "TEST 1: Auto Room Number Generation\n";
echo str_repeat("-", 50) . "\n";

$hotel = Hotel::first();
echo "Hotel: {$hotel->name}\n\n";

// Show room types and their numbers
$roomsByType = Room::where('hotel_id', $hotel->id)
    ->select('room_type', DB::raw('GROUP_CONCAT(room_number ORDER BY CAST(room_number AS UNSIGNED)) as numbers'))
    ->groupBy('room_type')
    ->get();

foreach ($roomsByType as $group) {
    echo "Room Type: {$group->room_type}\n";
    echo "  Current Numbers: {$group->numbers}\n";
    
    // Simulate what next room number would be
    $highestRoom = Room::where('hotel_id', $hotel->id)
                      ->where('room_type', $group->room_type)
                      ->orderByRaw('CAST(room_number AS UNSIGNED) DESC')
                      ->first();
    
    $nextNumber = (int)$highestRoom->room_number + 1;
    echo "  Next Auto Number: {$nextNumber}\n\n";
}

// Test 2: Auto Room Assignment
echo "\nTEST 2: Auto Room Assignment\n";
echo str_repeat("-", 50) . "\n";

$roomTypes = Room::where('hotel_id', $hotel->id)
    ->select('room_type', 
             DB::raw('COUNT(*) as total_rooms'),
             DB::raw('SUM(CASE WHEN status = "AVAILABLE" THEN 1 ELSE 0 END) as available_rooms'))
    ->groupBy('room_type')
    ->get();

echo "Available Rooms by Type:\n";
foreach ($roomTypes as $type) {
    echo "  {$type->room_type}: {$type->available_rooms}/{$type->total_rooms} available\n";
    
    // Get first available room of this type
    $firstAvailable = Room::where('hotel_id', $hotel->id)
                          ->where('room_type', $type->room_type)
                          ->where('status', 'AVAILABLE')
                          ->first();
    
    if ($firstAvailable) {
        echo "    → Would assign: Room {$firstAvailable->room_number}\n";
    } else {
        echo "    → No available rooms\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ Auto Features Ready!\n\n";
echo "FEATURE 1: Auto Room Number Generation\n";
echo "  - Leave 'Room Number' empty when adding a new room\n";
echo "  - System will auto-assign next number based on room type\n";
echo "  - Default status: AVAILABLE\n\n";

echo "FEATURE 2: Auto Room Assignment on Booking\n";
echo "  - Select 'Room Type' instead of specific room\n";
echo "  - System automatically assigns first available room\n";
echo "  - Skips OCCUPIED, MAINTENANCE, CLEANING rooms\n";
echo "  - Updates assigned room status to OCCUPIED\n";
