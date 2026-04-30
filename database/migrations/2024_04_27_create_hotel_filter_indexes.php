<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add indexes for optimal query performance
     */
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // Index for location-based queries
            $table->index('dzongkhag_id');
            
            // Index for status filtering (approved hotels)
            $table->index('status');
            
            // Index for rating/sorting
            $table->index('star_rating');
            
            // Index for property type filtering
            $table->index('property_type');
            
            // Composite index for common search patterns
            $table->index(['status', 'dzongkhag_id']);
            
            // Index for cancellation policy filtering
            $table->index('cancellation_policy');
        });

        Schema::table('reviews', function (Blueprint $table) {
            // Index for review lookups by hotel
            $table->index('hotel_id');
            
            // Index for status filtering
            $table->index('status');
            
            // Composite index for hotel + status
            $table->index(['hotel_id', 'status']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            // Index for room lookups by hotel
            $table->index('hotel_id');
            
            // Index for price range queries
            $table->index('price_per_night');
        });

        Schema::table('bookings', function (Blueprint $table) {
            // Index for availability checks
            $table->index('hotel_id');
            $table->index('room_id');
            
            // Composite index for availability queries
            $table->index(['hotel_id', 'check_in_date', 'check_out_date']);
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropIndex(['dzongkhag_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['star_rating']);
            $table->dropIndex(['property_type']);
            $table->dropIndex(['status', 'dzongkhag_id']);
            $table->dropIndex(['cancellation_policy']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['hotel_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['hotel_id', 'status']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropIndex(['hotel_id']);
            $table->dropIndex(['price_per_night']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['hotel_id']);
            $table->dropIndex(['room_id']);
            $table->dropIndex(['hotel_id', 'check_in_date', 'check_out_date']);
        });
    }
};
