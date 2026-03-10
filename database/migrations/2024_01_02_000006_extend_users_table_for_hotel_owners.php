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
        Schema::table('users', function (Blueprint $table) {
            // Add mobile number
            $table->string('mobile')->nullable()->after('email');
            
            // Add PIN for hotel login (hashed)
            $table->string('pin')->nullable()->after('password');
            
            // Add hotel reference (for hotel staff)
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropColumn(['mobile', 'pin', 'hotel_id']);
        });
    }
};
