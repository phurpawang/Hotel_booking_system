<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== Checking Owner User and Hotel Relationship ===\n\n";

// Find an owner user
$owner = App\Models\User::where('role', 'OWNER')->first();

if (!$owner) {
    echo "❌ No OWNER user found in database\n";
    exit(1);
}

echo "✅ Owner User Found:\n";
echo "   - ID: {$owner->id}\n";
echo "   - Name: {$owner->name}\n";
echo "   - Email: {$owner->email}\n";
echo "   - Role: {$owner->role}\n";
echo "   - hotel_id: " . ($owner->hotel_id ?? 'NULL') . "\n\n";

// Check hotel via hotel_id (belongs to)
echo "Checking hotel via hotel() relationship (user's hotel_id):\n";
$hotelViaId = $owner->hotel;
if ($hotelViaId) {
    echo "✅ Hotel found via hotel():\n";
    echo "   - ID: {$hotelViaId->id}\n";
    echo "   - Name: {$hotelViaId->name}\n";
    echo "   - Status: {$hotelViaId->status}\n";
    echo "   - owner_id: {$hotelViaId->owner_id}\n\n";
} else {
    echo "❌ No hotel found via hotel() relationship\n\n";
}

// Check hotel via owner_id (owns)
echo "Checking hotel via ownedHotel() relationship (hotel's owner_id):\n";
$ownedHotel = $owner->ownedHotel;
if ($ownedHotel) {
    echo "✅ Hotel found via ownedHotel():\n";
    echo "   - ID: {$ownedHotel->id}\n";
    echo "   - Name: {$ownedHotel->name}\n";
    echo "   - Status: {$ownedHotel->status}\n";
    echo "   - owner_id: {$ownedHotel->owner_id}\n\n";
} else {
    echo "❌ No hotel found via ownedHotel() relationship\n\n";
}

// Check what OwnerDashboardController is looking for
echo "What OwnerDashboardController@index is looking for:\n";
$hotelForDashboard = App\Models\Hotel::where('owner_id', $owner->id)->first();
if ($hotelForDashboard) {
    echo "✅ Hotel found using where('owner_id', {$owner->id}):\n";
    echo "   - ID: {$hotelForDashboard->id}\n";
    echo "   - Name: {$hotelForDashboard->name}\n";
    echo "   - Status: {$hotelForDashboard->status}\n\n";
} else {
    echo "❌ No hotel found using where('owner_id', {$owner->id})\n";
    echo "   This is the problem! The controller can't find the hotel.\n\n";
}

echo "=== All Hotels in Database ===\n";
$hotels = App\Models\Hotel::all();
foreach ($hotels as $hotel) {
    echo "Hotel ID: {$hotel->id}, owner_id: {$hotel->owner_id}, Name: {$hotel->name}, Status: {$hotel->status}\n";
}

echo "\n=== All Users ===\n";
$users = App\Models\User::all();
foreach ($users as $user) {
    echo "User ID: {$user->id}, hotel_id: " . ($user->hotel_id ?? 'NULL') . ", Role: {$user->role}, Email: {$user->email}\n";
}
