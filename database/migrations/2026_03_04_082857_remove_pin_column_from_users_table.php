<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update passwords for specific hotels before removing PIN
        // Hotel ID 1 (HT001) - owner user_id = 4
        DB::table('users')
            ->where('hotel_id', 1)
            ->where('role', 'OWNER')
            ->update(['password' => Hash::make('Tedday@123')]);
        
        // Hotel ID 2 (HT002) - owner user_id = 7
        DB::table('users')
            ->where('hotel_id', 2)
            ->where('role', 'OWNER')
            ->update(['password' => Hash::make('Tedday@345')]);
        
        // Hotel ID 3 (HTL000003) - owner user_id = 3
        DB::table('users')
            ->where('hotel_id', 3)
            ->where('role', 'OWNER')
            ->update(['password' => Hash::make('Tedday@678')]);
        
        // Remove PIN column if it exists
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pin')) {
                $table->dropColumn('pin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add PIN column back
        Schema::table('users', function (Blueprint $table) {
            $table->string('pin')->nullable()->after('password');
        });
    }
};
