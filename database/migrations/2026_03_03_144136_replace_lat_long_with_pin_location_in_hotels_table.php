<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Drop latitude and longitude columns
            $table->dropColumn(['map_latitude', 'map_longitude']);
            
            // Add pin_location column
            $table->text('pin_location')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Restore latitude and longitude columns
            $table->string('map_latitude')->nullable()->after('address');
            $table->string('map_longitude')->nullable()->after('map_latitude');
            
            // Drop pin_location column
            $table->dropColumn('pin_location');
        });
    }
};
