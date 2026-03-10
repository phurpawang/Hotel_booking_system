<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== Checking Hotel 3 Owner Issue ===\n\n";

$hotel3 = App\Models\Hotel::find(3);
if ($hotel3) {
    echo "Hotel 3: {$hotel3->name}\n";
    echo "  - owner_id: {$hotel3->owner_id}\n";
    echo "  - Status: {$hotel3->status}\n\n";
}

echo "All OWNER users:\n";
$owners = App\Models\User::where('role', 'OWNER')->get();
foreach ($owners as $owner) {
    echo "\nUser ID: {$owner->id}\n";
    echo "  - Email: {$owner->email}\n";
    echo "  - hotel_id: " . ($owner->hotel_id ?? 'NULL') . "\n";
    echo "  - Role: {$owner->role}\n";
    
    // Check ownedHotel (hotels where owner_id = user.id)
    $ownedHotel = $owner->ownedHotel;
    if ($ownedHotel) {
        echo "  - ownedHotel(): {$ownedHotel->name} (ID: {$ownedHotel->id})\n";
    } else {
        echo "  - ownedHotel(): NONE\n";
    }
    
    // Check hotel (user works at this hotel)
    $hotel = $owner->hotel;
    if ($hotel) {
        echo "  - hotel(): {$hotel->name} (ID: {$hotel->id})\n";
    } else {
        echo "  - hotel(): NONE\n";
    }
}

echo "\n\n=== Managers ===\n";
$managers = App\Models\User::where('role', 'MANAGER')->get();
foreach ($managers as $manager) {
    echo "\nUser ID: {$manager->id}\n";
    echo "  - Email: {$manager->email}\n";
    echo "  - hotel_id: " . ($manager->hotel_id ?? 'NULL') . "\n";
    
    $hotel = $manager->hotel;
    if ($hotel) {
        echo "  - hotel(): {$hotel->name} (ID: {$hotel->id}, Status: {$hotel->status})\n";
    } else {
        echo "  - hotel(): NONE\n";
    }
}
