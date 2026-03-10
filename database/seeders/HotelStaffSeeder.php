<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class HotelStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Hotel Owner for Hotel Park (id = 1, hotel_id = HT001)
        User::firstOrCreate(
            ['email' => 'owner@hotelpark.com'],
            [
                'name' => 'Michael Park',
                'hotel_name' => 'Hotel Park',
                'password' => Hash::make('password'),
                'hotel_id' => 1, // Foreign key to hotels table
                'pin' => Hash::make('1234'), // PIN: 1234 (Login: HT001 + 1234)
                'role' => 'OWNER',
                'status' => 'ACTIVE',
            ]
        );

        // Create Hotel Manager for Hotel Park
        User::firstOrCreate(
            ['email' => 'manager@hotelpark.com'],
            [
                'name' => 'John Manager',
                'hotel_name' => 'Hotel Park',
                'password' => Hash::make('password'),
                'hotel_id' => 1,
                'pin' => Hash::make('5678'), // PIN: 5678 (Login: HT001 + 5678)
                'role' => 'MANAGER',
                'status' => 'ACTIVE',
            ]
        );

        // Create Reception Staff for Hotel Park
        User::firstOrCreate(
            ['email' => 'reception@hotelpark.com'],
            [
                'name' => 'Sarah Reception',
                'hotel_name' => 'Hotel Park',
                'password' => Hash::make('password'),
                'hotel_id' => 1,
                'pin' => Hash::make('9999'), // PIN: 9999 (Login: HT001 + 9999)
                'role' => 'RECEPTION',
                'status' => 'ACTIVE',
            ]
        );

        // Create Owner for Druk Hotel (id = 2, hotel_id = HT002)
        User::firstOrCreate(
            ['email' => 'owner@drukhotel.com'],
            [
                'name' => 'David Druk',
                'hotel_name' => 'Druk Hotel',
                'password' => Hash::make('password'),
                'hotel_id' => 2,
                'pin' => Hash::make('4321'), // PIN: 4321 (Login: HT002 + 4321)
                'role' => 'OWNER',
                'status' => 'ACTIVE',
            ]
        );

        // Create Manager for Druk Hotel
        User::firstOrCreate(
            ['email' => 'manager@drukhotel.com'],
            [
                'name' => 'Mike Manager',
                'hotel_name' => 'Druk Hotel',
                'password' => Hash::make('password'),
                'hotel_id' => 2,
                'pin' => Hash::make('8765'), // PIN: 8765 (Login: HT002 + 8765)
                'role' => 'MANAGER',
                'status' => 'ACTIVE',
            ]
        );

        // Create Reception for Druk Hotel
        User::firstOrCreate(
            ['email' => 'reception@drukhotel.com'],
            [
                'name' => 'Emma Reception',
                'hotel_name' => 'Druk Hotel',
                'password' => Hash::make('password'),
                'hotel_id' => 2,
                'pin' => Hash::make('1111'), // PIN: 1111 (Login: HT002 + 1111)
                'role' => 'RECEPTION',
                'status' => 'ACTIVE',
            ]
        );

        // Create Owner for Hotel ZhuSA (id = 3, hotel_id = HTL000003)
        User::firstOrCreate(
            ['email' => 'owner@hotelzhusa.com'],
            [
                'name' => 'Tashi Zhu',
                'hotel_name' => 'Hotel ZhuSA',
                'password' => Hash::make('password'),
                'hotel_id' => 3,
                'pin' => Hash::make('2222'), // PIN: 2222 (Login: HTL000003 + 2222)
                'role' => 'OWNER',
                'status' => 'ACTIVE',
            ]
        );

        // Create Manager for Hotel ZhuSA
        User::firstOrCreate(
            ['email' => 'manager@hotelzhusa.com'],
            [
                'name' => 'Karma Manager',
                'hotel_name' => 'Hotel ZhuSA',
                'password' => Hash::make('password'),
                'hotel_id' => 3,
                'pin' => Hash::make('3333'), // PIN: 3333 (Login: HTL000003 + 3333)
                'role' => 'MANAGER',
                'status' => 'ACTIVE',
            ]
        );

        // Create Reception for Hotel ZhuSA
        User::firstOrCreate(
            ['email' => 'reception@hotelzhusa.com'],
            [
                'name' => 'Sonam Reception',
                'hotel_name' => 'Hotel ZhuSA',
                'password' => Hash::make('password'),
                'hotel_id' => 3,
                'pin' => Hash::make('4444'), // PIN: 4444 (Login: HTL000003 + 4444)
                'role' => 'RECEPTION',
                'status' => 'ACTIVE',
            ]
        );
    }
}
