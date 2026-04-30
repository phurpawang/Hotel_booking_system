<?php
/**
 * Insert Guest Data Script
 * Run this via: php artisan tinker < insert_guests.php
 * Or copy commands manually into tinker
 */

use App\Models\Guest;

// Guest data from bookings
$guests = [
    [
        'name' => 'Sonam Choden',
        'email' => '11606003606@rim.edu.bt',
        'phone' => '77777777',
        'mobile' => null,
        'status' => 'active'
    ],
    [
        'name' => 'sangay dorji',
        'email' => 'sangay.dorji@example.com',
        'phone' => '77777777',
        'mobile' => null,
        'status' => 'active'
    ],
    [
        'name' => 'sangay yeshi',
        'email' => 'sangay.yeshi@example.com',
        'phone' => '77777777',
        'mobile' => null,
        'status' => 'active'
    ],
    [
        'name' => 'Jample Dechen',
        'email' => 'jample.dechen@example.com',
        'phone' => '17573956',
        'mobile' => null,
        'status' => 'active'
    ],
    [
        'name' => 'Sangay Penjor',
        'email' => 'sangay.penjor@example.com',
        'phone' => '77777777',
        'mobile' => null,
        'status' => 'active'
    ],
    [
        'name' => 'Sangay penjor',
        'email' => 'sangay.penjor.main@example.com',
        'phone' => '77777777',
        'mobile' => null,
        'status' => 'active'
    ]
];

// Insert guests
foreach ($guests as $guestData) {
    // Check if guest already exists by email
    $existing = Guest::where('email', $guestData['email'])->first();
    
    if (!$existing) {
        $guest = Guest::create($guestData);
        echo "✓ Created guest: {$guest->name} (ID: {$guest->id})\n";
    } else {
        echo "⊘ Guest already exists: {$existing->name} (ID: {$existing->id})\n";
    }
}

echo "\n✓ Guest insertion complete!\n";
