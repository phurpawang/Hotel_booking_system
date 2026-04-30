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
        // Add missing columns to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('special_requests')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('bookings', 'actual_check_in')) {
                $table->timestamp('actual_check_in')->nullable()->after('check_out_date');
            }
            if (!Schema::hasColumn('bookings', 'actual_check_out')) {
                $table->timestamp('actual_check_out')->nullable()->after('actual_check_in');
            }
        });

        // Add missing columns to rooms table if needed
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'capacity')) {
                $table->integer('capacity')->default(2)->after('room_type');
            }
            if (!Schema::hasColumn('rooms', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('status');
            }
        });

        // Add logo column to hotels table if not exists
        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'logo')) {
                if (Schema::hasColumn('hotels', 'property_image')) {
                    $table->string('logo')->nullable()->after('property_image');
                } else {
                    $table->string('logo')->nullable();
                }
            }
            if (!Schema::hasColumn('hotels', 'owner_id')) {
                $table->foreignId('owner_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
        });

        // Ensure users table has created_by column
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('role')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['created_by', 'actual_check_in', 'actual_check_out']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'is_available']);
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
