<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create hotel owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@test.com'],
            [
                'name' => 'Hotel Owner',
                'password' => Hash::make('password'),
                'role' => 'OWNER',
                'mobile' => '17123456',
                'pin' => Hash::make('1234')
            ]
        );

        // Create Hotel Park in Thimphu
        $hotelPark = Hotel::firstOrCreate(
            ['hotel_id' => 'HT001'],
            [
                'name' => 'Hotel Park',
                'owner_id' => $owner->id,
                'dzongkhag_id' => 14, // Thimphu
                'address' => 'babesa high way',
                'description' => 'Comfortable hotel in Thimphu with modern amenities',
                'phone' => '02-123456',
                'email' => 'park@hotel.bt',
                'status' => 'APPROVED',
                'star_rating' => 3,
                'property_type' => 'Hotel'
            ]
        );

        // Add rooms to Hotel Park
        Room::firstOrCreate(
            ['hotel_id' => $hotelPark->id, 'room_type' => 'Deluxe King'],
            [
                'room_number' => '101',
                'description' => 'Spacious room with king bed and beautiful views',
                'price_per_night' => 1500.00,
                'quantity' => 3,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Room Service']),
                'status' => 'AVAILABLE'
            ]
        );

        Room::firstOrCreate(
            ['hotel_id' => $hotelPark->id, 'room_type' => 'Standard Double'],
            [
                'room_number' => '201',
                'description' => 'Comfortable double room with essential amenities',
                'price_per_night' => 1000.00,
                'quantity' => 5,
                'capacity' => 2,
                'amenities' => json_encode(['WiFi', 'TV', 'Heater']),
                'status' => 'AVAILABLE'
            ]
        );

        // Create another hotel in Paro
        $hotelDruk = Hotel::firstOrCreate(
            ['hotel_id' => 'HT002'],
            [
                'name' => 'Druk Hotel',
                'owner_id' => $owner->id,
                'dzongkhag_id' => 8, // Paro
                'address' => 'Paro Town',
                'description' => 'Traditional Bhutanese hotel near the airport',
                'phone' => '08-271234',
                'email' => 'druk@hotel.bt',
                'status' => 'APPROVED',
                'star_rating' => 4,
                'property_type' => 'Hotel'
            ]
        );

        Room::firstOrCreate(
            ['hotel_id' => $hotelDruk->id, 'room_type' => 'Suite'],
            [
                'room_number' => '301',
                'description' => 'Luxury suite with traditional Bhutanese decor',
                'price_per_night' => 2500.00,
                'quantity' => 2,
                'capacity' => 3,
                'amenities' => json_encode(['WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Balcony', 'Room Service', 'Bathtub']),
                'status' => 'AVAILABLE'
            ]
        );

        echo "Test hotels and rooms created successfully!\n";
        echo "Hotel Park (Thimphu) - 2 room types\n";
        echo "Druk Hotel (Paro) - 1 room type\n";
    }
}
