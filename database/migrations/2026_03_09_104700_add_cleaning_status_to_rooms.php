<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL doesn't allow modifying enum directly with alter, so we use raw SQL
        DB::statement("ALTER TABLE rooms MODIFY COLUMN status ENUM('AVAILABLE', 'OCCUPIED', 'CLEANING', 'MAINTENANCE') DEFAULT 'AVAILABLE'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum without CLEANING
        DB::statement("ALTER TABLE rooms MODIFY COLUMN status ENUM('AVAILABLE', 'OCCUPIED', 'MAINTENANCE') DEFAULT 'AVAILABLE'");
    }
};
