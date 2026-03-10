<?php

/**
 * Quick fix for room status enum - Add CLEANING status
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Adding CLEANING status to rooms table enum...\n";

try {
    DB::statement("ALTER TABLE rooms MODIFY COLUMN status ENUM('AVAILABLE', 'OCCUPIED', 'CLEANING', 'MAINTENANCE') DEFAULT 'AVAILABLE'");
    echo "✅ Successfully added CLEANING status to rooms.status enum\n";
    echo "\nAvailable statuses:\n";
    echo "  - AVAILABLE\n";
    echo "  - OCCUPIED\n";
    echo "  - CLEANING (NEW)\n";
    echo "  - MAINTENANCE\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
