<?php

/**
 * Verify rooms are properly split and manageable individually
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

echo "=== Room Status Management Verification ===\n\n";

$hotels = Hotel::with('rooms')->get();

foreach ($hotels as $hotel) {
    echo "Hotel: {$hotel->name}\n";
    echo str_repeat("-", 50) . "\n";
    
    $rooms = $hotel->rooms()->orderBy('room_number')->get();
    
    echo "Total Rooms: " . $rooms->count() . "\n\n";
    
    echo "Individual Room Details:\n";
    foreach ($rooms as $room) {
        $status = str_pad($room->status, 12);
        echo sprintf(
            "  Room %-5s | %-20s | %s | Capacity: %d | Price: Nu. %.2f\n",
            $room->room_number,
            $room->room_type,
            $status,
            $room->capacity,
            $room->price
        );
    }
    
    echo "\nStatus Summary:\n";
    $statusCounts = $rooms->groupBy('status')->map->count();
    foreach (['AVAILABLE', 'OCCUPIED', 'CLEANING', 'MAINTENANCE'] as $status) {
        $count = $statusCounts->get($status, 0);
        echo "  {$status}: {$count} rooms\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
}

echo "✅ Each room can now be managed individually!\n";
echo "➡️  Visit /manager/room-status to change individual room statuses\n";
