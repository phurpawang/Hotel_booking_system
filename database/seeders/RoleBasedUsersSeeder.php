<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class RoleBasedUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create ADMIN user
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@bhbs.bt',
            'password' => Hash::make('password'),
            'role' => 'ADMIN'
        ]);

        // Create OWNER user
        $owner = User::create([
            'name' => 'Hotel Owner',
            'email' => 'owner@bhbs.bt',
            'password' => Hash::make('password'),
            'role' => 'OWNER'
        ]);

        // Create MANAGER user
        $manager = User::create([
            'name' => 'Hotel Manager',
            'email' => 'manager@bhbs.bt',
            'password' => Hash::make('password'),
            'role' => 'MANAGER'
        ]);

        // Create RECEPTION user
        $reception = User::create([
            'name' => 'Reception Staff',
            'email' => 'reception@bhbs.bt',
            'password' => Hash::make('password'),
            'role' => 'RECEPTION'
        ]);

        // Create a sample hotel for the owner
        $hotel = Hotel::create([
            'owner_id' => $owner->id,
            'name' => 'Dragon Palace Hotel',
            'address' => 'Thimphu, Bhutan',
            'phone' => '+975-2-123456',
            'email' => 'info@dragonpalace.bt',
            'description' => 'Luxury hotel in the heart of Thimphu',
            'status' => 'APPROVED'
        ]);

        // Create sample rooms
        $roomTypes = [
            ['room_type' => 'Single', 'capacity' => 1, 'price' => 3000],
            ['room_type' => 'Double', 'capacity' => 2, 'price' => 5000],
            ['room_type' => 'Suite', 'capacity' => 3, 'price' => 8000],
            ['room_type' => 'Deluxe', 'capacity' => 4, 'price' => 12000],
        ];

        foreach ($roomTypes as $index => $type) {
            for ($i = 1; $i <= 5; $i++) {
                Room::create([
                    'hotel_id' => $hotel->id,
                    'room_number' => (($index + 1) * 100) + $i,
                    'room_type' => $type['room_type'],
                    'capacity' => $type['capacity'],
                    'price_per_night' => $type['price'],
                    'description' => 'Comfortable ' . $type['room_type'] . ' room with modern amenities',
                    'status' => 'AVAILABLE'
                ]);
            }
        }

        // Create sample bookings
        $rooms = Room::all();
        $today = now();

        // Today's arrivals
        Booking::create([
            'room_id' => $rooms[0]->id,
            'guest_name' => 'Tashi Dorji',
            'guest_email' => 'tashi@example.bt',
            'guest_phone' => '+975-17-123456',
            'check_in_date' => $today,
            'check_out_date' => $today->copy()->addDays(3),
            'num_guests' => 2,
            'total_price' => 15000,
            'status' => 'CONFIRMED'
        ]);

        // Checked in guest
        Booking::create([
            'room_id' => $rooms[1]->id,
            'guest_name' => 'Karma Wangchuk',
            'guest_email' => 'karma@example.bt',
            'guest_phone' => '+975-17-234567',
            'check_in_date' => $today->copy()->subDays(2),
            'check_out_date' => $today,
            'num_guests' => 2,
            'total_price' => 10000,
            'status' => 'CHECKED_IN'
        ]);

        // Future booking
        Booking::create([
            'room_id' => $rooms[2]->id,
            'guest_name' => 'Pema Tshering',
            'guest_email' => 'pema@example.bt',
            'guest_phone' => '+975-17-345678',
            'check_in_date' => $today->copy()->addDays(5),
            'check_out_date' => $today->copy()->addDays(7),
            'num_guests' => 3,
            'total_price' => 16000,
            'status' => 'CONFIRMED'
        ]);

        // Pending hotel for admin approval
        $owner2 = User::create([
            'name' => 'Another Owner',
            'email' => 'owner2@bhbs.bt',
            'password' => Hash::make('password'),
            'role' => 'OWNER'
        ]);

        Hotel::create([
            'owner_id' => $owner2->id,
            'name' => 'Paro Paradise Resort',
            'address' => 'Paro Valley, Bhutan',
            'phone' => '+975-8-654321',
            'email' => 'info@paroparadise.bt',
            'description' => 'Beautiful resort with mountain views',
            'status' => 'PENDING'
        ]);

        echo "Sample users created:\n";
        echo "Admin: admin@bhbs.bt / password\n";
        echo "Owner: owner@bhbs.bt / password\n";
        echo "Manager: manager@bhbs.bt / password\n";
        echo "Reception: reception@bhbs.bt / password\n";
    }
}
