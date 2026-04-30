<?php

/**
 * Update existing hotels with dzongkhag names
 * This script updates all hotels that have a dzongkhag_id but no dzongkhag name
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hotel;
use App\Models\Dzongkhag;

echo "Starting dzongkhag update for existing hotels...\n\n";

// Get all hotels that have dzongkhag_id but no dzongkhag name
$hotels = Hotel::whereNotNull('dzongkhag_id')
    ->where(function($query) {
        $query->whereNull('dzongkhag')
              ->orWhere('dzongkhag', '');
    })
    ->get();

if ($hotels->isEmpty()) {
    echo "No hotels need updating. All hotels already have dzongkhag names.\n";
    exit(0);
}

echo "Found " . $hotels->count() . " hotel(s) to update:\n\n";

$updated = 0;
$failed = 0;

foreach ($hotels as $hotel) {
    try {
        $dzongkhag = Dzongkhag::find($hotel->dzongkhag_id);
        
        if ($dzongkhag) {
            $hotel->dzongkhag = $dzongkhag->name;
            $hotel->save();
            
            echo "✓ Updated Hotel #{$hotel->id}: {$hotel->name} -> {$dzongkhag->name}\n";
            $updated++;
        } else {
            echo "✗ Failed Hotel #{$hotel->id}: {$hotel->name} - Dzongkhag ID {$hotel->dzongkhag_id} not found\n";
            $failed++;
        }
    } catch (Exception $e) {
        echo "✗ Error updating Hotel #{$hotel->id}: {$e->getMessage()}\n";
        $failed++;
    }
}

echo "\n";
echo "=================================\n";
echo "Update Summary:\n";
echo "  Successfully updated: $updated\n";
echo "  Failed: $failed\n";
echo "  Total processed: " . ($updated + $failed) . "\n";
echo "=================================\n";

if ($updated > 0) {
    echo "\n✓ Dzongkhag names have been successfully added to hotels!\n";
}

if ($failed > 0) {
    echo "\n⚠ Some hotels failed to update. Please check the output above.\n";
}
