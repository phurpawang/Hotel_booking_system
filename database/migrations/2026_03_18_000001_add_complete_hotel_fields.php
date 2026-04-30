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
            // Add name column if it doesn't exist (was replaced with hotel_name)
            if (!Schema::hasColumn('hotels', 'name')) {
                $table->string('name')->nullable()->after('hotel_id');
            }

            // Add property type
            if (!Schema::hasColumn('hotels', 'property_type')) {
                $table->string('property_type')->nullable()->after('name');
            }

            // Add address
            if (!Schema::hasColumn('hotels', 'address')) {
                $table->text('address')->nullable()->after('property_type');
            }

            // Add dzongkhag_id
            if (!Schema::hasColumn('hotels', 'dzongkhag_id')) {
                $table->unsignedBigInteger('dzongkhag_id')->nullable()->after('dzongkhag');
            }

            // Add pin_location
            if (!Schema::hasColumn('hotels', 'pin_location')) {
                $table->text('pin_location')->nullable()->after('dzongkhag_id');
            }

            // Add phone
            if (!Schema::hasColumn('hotels', 'phone')) {
                $table->string('phone')->nullable()->after('pin_location');
            }

            // Add mobile
            if (!Schema::hasColumn('hotels', 'mobile')) {
                $table->string('mobile')->nullable()->after('phone');
            }

            // Add description
            if (!Schema::hasColumn('hotels', 'description')) {
                $table->text('description')->nullable()->after('mobile');
            }

            // Add property_image
            if (!Schema::hasColumn('hotels', 'property_image')) {
                $table->string('property_image')->nullable()->after('description');
            }

            // Add star_rating
            if (!Schema::hasColumn('hotels', 'star_rating')) {
                $table->integer('star_rating')->nullable()->after('property_image');
            }

            // Add tourism license fields
            if (!Schema::hasColumn('hotels', 'tourism_license_number')) {
                $table->string('tourism_license_number')->nullable()->unique()->after('star_rating');
            }

            if (!Schema::hasColumn('hotels', 'issuing_authority')) {
                $table->string('issuing_authority')->nullable()->after('tourism_license_number');
            }

            if (!Schema::hasColumn('hotels', 'license_issue_date')) {
                $table->date('license_issue_date')->nullable()->after('issuing_authority');
            }

            if (!Schema::hasColumn('hotels', 'license_expiry_date')) {
                $table->date('license_expiry_date')->nullable()->after('license_issue_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'property_type',
                'address',
                'dzongkhag_id',
                'pin_location',
                'phone',
                'mobile',
                'description',
                'property_image',
                'star_rating',
                'tourism_license_number',
                'issuing_authority',
                'license_issue_date',
                'license_expiry_date'
            ]);
        });
    }
};
