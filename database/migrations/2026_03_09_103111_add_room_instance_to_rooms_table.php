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
        // Add room_instance column to distinguish individual rooms
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('room_instance', 10)->default('A')->after('room_number');
            $table->json('instance_statuses')->nullable()->after('status');
        });

        // For existing rooms with quantity > 1, we'll track per-instance status in JSON
        // Example: {"A": "AVAILABLE", "B": "OCCUPIED", "C": "CLEANING"}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['room_instance', 'instance_statuses']);
        });
    }
};
