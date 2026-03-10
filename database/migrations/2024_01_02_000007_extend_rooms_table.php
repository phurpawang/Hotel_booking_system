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
        Schema::table('rooms', function (Blueprint $table) {
            // Add amenities
            $table->json('amenities')->nullable()->after('description');
            
            // Add photos
            $table->json('photos')->nullable()->after('amenities');
            
            // Add cancellation policy
            $table->text('cancellation_policy')->nullable()->after('photos');
            
            // Add available quantity
            $table->integer('quantity')->default(1)->after('room_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['amenities', 'photos', 'cancellation_policy', 'quantity']);
        });
    }
};
