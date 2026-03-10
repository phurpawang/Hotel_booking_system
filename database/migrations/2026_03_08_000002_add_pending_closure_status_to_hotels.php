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
        // Add PENDING_CLOSURE to the hotel status enum if database is MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE hotels MODIFY COLUMN status ENUM('PENDING', 'APPROVED', 'REJECTED', 'DEREGISTRATION_REQUESTED', 'DEREGISTERED', 'SUSPENDED', 'FORCE_DEREGISTERED', 'PENDING_CLOSURE') DEFAULT 'PENDING'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE hotels MODIFY COLUMN status ENUM('PENDING', 'APPROVED', 'REJECTED', 'DEREGISTRATION_REQUESTED', 'DEREGISTERED', 'SUSPENDED', 'FORCE_DEREGISTERED') DEFAULT 'PENDING'");
        }
    }
};
