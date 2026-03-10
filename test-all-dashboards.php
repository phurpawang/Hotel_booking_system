<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== Testing All Dashboard Access ===\n\n";

// Test cases
$testUsers = [
    ['email' => 'owner@test.com', 'expected' => 'Owner Dashboard'],
    ['email' => 'pwangchuk282@gmail.com', 'expected' => 'Owner Dashboard'],
    ['email' => '12002001841@rim.edu.bt', 'expected' => 'Owner Dashboard'],
    ['email' => 'owner@hotelpark.com', 'expected' => 'Owner Dashboard'],
    ['email' => 'manager@hotelpark.com', 'expected' => 'Manager Dashboard'],
    ['email' => 'jigme3573692@gmail.com', 'expected' => 'Manager Dashboard'],
    ['email' => 'reception@hotelpark.com', 'expected' => 'Reception Dashboard'],
    ['email' => 'phurpawangchuk17@gmail.com', 'expected' => 'Reception Dashboard'],
];

foreach ($testUsers as $test) {
    $user = App\Models\User::where('email', $test['email'])->first();
    
    if (!$user) {
        echo "❌ User not found: {$test['email']}\n\n";
        continue;
    }
    
    echo "Testing: {$test['email']}\n";
    echo "  Role: {$user->role}\n";
    echo "  hotel_id: " . ($user->hotel_id ?? 'NULL') . "\n";
    
    $passed = true;
    $errors = [];
    
    // Test based on role
    if (strtoupper($user->role) === 'OWNER') {
        // Test owner hotel access
        $hotelViaOwnership = App\Models\Hotel::where('owner_id', $user->id)->first();
        $hotelViaStaff = $user->hotel;
        $hotel = $hotelViaOwnership ?? $hotelViaStaff;
        
        if (!$hotel) {
            $passed = false;
            $errors[] = "No hotel found via either relationship";
        } else {
            echo "  Hotel: {$hotel->name} (ID: {$hotel->id})\n";
            echo "  Status: {$hotel->status}\n";
            
            if (strtoupper($hotel->status) !== 'APPROVED') {
                $passed = false;
                $errors[] = "Hotel not approved";
            }
            
            // Test dashboard data access
            try {
                $totalRooms = App\Models\Room::where('hotel_id', $hotel->id)->count();
                $totalBookings = App\Models\Booking::where('hotel_id', $hotel->id)->count();
                echo "  Dashboard: {$totalRooms} rooms, {$totalBookings} bookings\n";
            } catch (Exception $e) {
                $passed = false;
                $errors[] = "Failed to get dashboard data: " . $e->getMessage();
            }
        }
    } elseif (strtoupper($user->role) === 'MANAGER') {
        // Test manager hotel access
        $hotel = $user->hotel;
        
        if (!$hotel) {
            $passed = false;
            $errors[] = "No hotel found";
        } else {
            echo "  Hotel: {$hotel->name} (ID: {$hotel->id})\n";
            echo "  Status: {$hotel->status}\n";
            
            if (strtoupper($hotel->status) !== 'APPROVED') {
                $passed = false;
                $errors[] = "Hotel not approved";
            }
            
            // Test dashboard data access
            try {
                $totalRooms = App\Models\Room::where('hotel_id', $user->hotel_id)->count();
                $totalBookings = App\Models\Booking::where('hotel_id', $user->hotel_id)->count();
                echo "  Dashboard: {$totalRooms} rooms, {$totalBookings} bookings\n";
            } catch (Exception $e) {
                $passed = false;
                $errors[] = "Failed to get dashboard data: " . $e->getMessage();
            }
        }
    } elseif (in_array(strtoupper($user->role), ['RECEPTION', 'RECEPTIONIST'])) {
        // Test receptionist hotel access
        $hotel = $user->hotel;
        
        if (!$hotel) {
            $passed = false;
            $errors[] = "No hotel found";
        } else {
            echo "  Hotel: {$hotel->name} (ID: {$hotel->id})\n";
            echo "  Status: {$hotel->status}\n";
            
            if (strtoupper($hotel->status) !== 'APPROVED') {
                $passed = false;
                $errors[] = "Hotel not approved";
            }
            
            // Test dashboard data access
            try {
                $totalRooms = App\Models\Room::where('hotel_id', $user->hotel_id)->count();
                $todayCheckIns = App\Models\Booking::where('hotel_id', $user->hotel_id)
                                   ->whereDate('check_in_date', today())
                                   ->count();
                echo "  Dashboard: {$totalRooms} rooms, {$todayCheckIns} check-ins today\n";
            } catch (Exception $e) {
                $passed = false;
                $errors[] = "Failed to get dashboard data: " . $e->getMessage();
            }
        }
    }
    
    if ($passed) {
        echo "  ✅ {$test['expected']} - WORKING\n\n";
    } else {
        echo "  ❌ {$test['expected']} - FAILED\n";
        foreach ($errors as $error) {
            echo "     - {$error}\n";
        }
        echo "\n";
    }
}

echo "=== Test Complete ===\n";
