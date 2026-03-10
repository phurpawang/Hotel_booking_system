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
            // Add mobile field after phone
            $table->string('mobile')->nullable()->after('phone');
            
            // Add location fields
            $table->foreignId('dzongkhag_id')->nullable()->constrained('dzongkhags')->after('address');
            $table->string('map_latitude')->nullable()->after('dzongkhag_id');
            $table->string('map_longitude')->nullable()->after('map_latitude');
            
            // Add star rating
            $table->integer('star_rating')->nullable()->after('description');
            
            // Add property type
            $table->string('property_type')->default('Hotel')->after('name');
            
            // Add tourism registration details
            $table->string('tourism_license_number')->nullable()->after('star_rating');
            $table->string('issuing_authority')->nullable()->after('tourism_license_number');
            $table->date('license_issue_date')->nullable()->after('issuing_authority');
            $table->date('license_expiry_date')->nullable()->after('license_issue_date');
            
            // Add unique hotel ID
            $table->string('hotel_id')->unique()->nullable()->after('id');
            
            // Add rejection reason
            $table->text('rejection_reason')->nullable()->after('status');
            
            // Update status enum to include new statuses
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED', 'DEREGISTRATION_REQUESTED', 'DEREGISTERED', 'SUSPENDED', 'FORCE_DEREGISTERED'])->default('PENDING')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign(['dzongkhag_id']);
            $table->dropColumn([
                'mobile',
                'dzongkhag_id',
                'map_latitude',
                'map_longitude',
                'star_rating',
                'property_type',
                'tourism_license_number',
                'issuing_authority',
                'license_issue_date',
                'license_expiry_date',
                'hotel_id',
                'rejection_reason'
            ]);
        });
    }
};
