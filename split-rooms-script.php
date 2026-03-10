<?php

/**
 * Script to split rooms with quantity > 1 into individual room records
 * This allows managing each physical room's status independently
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Room;
use Illuminate\Support\Facades\DB;

echo "Starting room splitting process...\n";

try {
    DB::beginTransaction();
    
    // Get all rooms with quantity > 1
    $roomsToSplit = Room::where('quantity', '>', 1)->get();
    
    echo "Found " . $roomsToSplit->count() . " room types to split\n\n";
    
    foreach ($roomsToSplit as $room) {
        $baseRoomNumber = $room->room_number;
        $quantity = $room->quantity;
        $hotelId = $room->hotel_id;
        
        echo "Processing Room {$baseRoomNumber} ({$room->room_type}) - Quantity: {$quantity}\n";
        
        // Keep the first room record, update others
        $room->quantity = 1;
        $room->save();
        echo "  → Updated original: Room {$baseRoomNumber}\n";
        
        // Create additional room records for quantity - 1
        for ($i = 1; $i < $quantity; $i++) {
            // Generate unique room number (e.g., 101 becomes 102, 103, etc.)
            $newRoomNumber = (int)$baseRoomNumber + $i;
            
            // Check if room number already exists
            while (Room::where('hotel_id', $hotelId)->where('room_number', $newRoomNumber)->exists()) {
                $newRoomNumber += 10; // Skip to next number if exists
            }
            
            $newRoom = $room->replicate();
            $newRoom->room_number = (string)$newRoomNumber;
            $newRoom->quantity = 1;
            $newRoom->save();
            
            echo "  → Created: Room {$newRoomNumber}\n";
        }
        
        echo "\n";
    }
    
    DB::commit();
    
    echo "\n✅ Room splitting complete!\n";
    echo "Total rooms after split: " . Room::count() . "\n";
    
    // Show summary by hotel
    echo "\nSummary by Hotel:\n";
    $hotels = DB::table('rooms')
        ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
        ->select('hotels.name', DB::raw('COUNT(rooms.id) as room_count'))
        ->groupBy('hotels.name')
        ->get();
    
    foreach ($hotels as $hotel) {
        echo "  {$hotel->name}: {$hotel->room_count} rooms\n";
    }
    
} catch (Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "Rolling back changes...\n";
}
