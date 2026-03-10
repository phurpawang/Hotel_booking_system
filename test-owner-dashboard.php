<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== Testing Owner Dashboard Access ===\n\n";

// Find owner user
$owner = App\Models\User::where('email', 'owner@test.com')->first();

if (!$owner) {
    echo "❌ Owner user not found\n";
    exit(1);
}

echo "✅ Testing with Owner: {$owner->name} ({$owner->email})\n\n";

// Test OwnerMiddleware logic
echo "1. Checking user role...\n";
if (strtoupper($owner->role) === 'OWNER') {
    echo "   ✅ User is OWNER\n\n";
} else {
    echo "   ❌ User role is: {$owner->role}\n\n";
}

// Test hotel relationship (what OwnerMiddleware now checks)
echo "2. Checking ownedHotel relationship...\n";
$hotel = $owner->ownedHotel;
if ($hotel) {
    echo "   ✅ Hotel found: {$hotel->name}\n";
    echo "   - Hotel ID: {$hotel->id}\n";
    echo "   - Status: {$hotel->status}\n";
    
    if (strtoupper($hotel->status) === 'APPROVED') {
        echo "   ✅ Hotel is APPROVED\n\n";
    } else {
        echo "   ❌ Hotel status is: {$hotel->status}\n\n";
    }
} else {
    echo "   ❌ No hotel found via ownedHotel()\n\n";
}

// Test OwnerDashboardController logic
echo "3. Checking OwnerDashboardController logic...\n";
$hotelForDashboard = App\Models\Hotel::where('owner_id', $owner->id)->first();
if ($hotelForDashboard) {
    echo "   ✅ Hotel found for dashboard: {$hotelForDashboard->name}\n";
    
    // Try to get dashboard data
    $totalRooms = App\Models\Room::where('hotel_id', $hotelForDashboard->id)->count();
    $totalBookings = App\Models\Booking::where('hotel_id', $hotelForDashboard->id)->count();
    $totalStaff = App\Models\User::where('hotel_id', $hotelForDashboard->id)
                         ->whereIn(DB::raw('UPPER(role)'), ['MANAGER', 'RECEPTION'])
                         ->count();
    
    echo "   - Total Rooms: {$totalRooms}\n";
    echo "   - Total Bookings: {$totalBookings}\n";
    echo "   - Total Staff: {$totalStaff}\n";
    echo "   ✅ Dashboard data retrieved successfully\n\n";
} else {
    echo "   ❌ No hotel found for dashboard\n\n";
}

echo "=== CONCLUSION ===\n";
if ($hotel && strtoupper($hotel->status) === 'APPROVED' && $hotelForDashboard) {
    echo "✅ Owner dashboard should now work!\n";
    echo "   URL: http://127.0.0.1:8000/owner/dashboard\n";
    echo "   Login with: owner@test.com\n";
} else {
    echo "❌ There are still issues that need to be fixed\n";
}
