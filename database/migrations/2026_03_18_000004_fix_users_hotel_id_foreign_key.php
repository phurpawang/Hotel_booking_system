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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Drop the foreign key constraint that references hotels.hotel_id
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['hotel_id']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        // Change hotel_id from string to unsigned big integer
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id')->nullable()->change();
        });

        // Add new foreign key that references hotels.id
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('hotel_id')
                  ->references('id')
                  ->on('hotels')
                  ->onDelete('cascade');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // Drop the new foreign key
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['hotel_id']);
            });
        } catch (\Exception $e) {
            // Foreign key doesn't exist, continue
        }

        // Change hotel_id back to string
        Schema::table('users', function (Blueprint $table) {
            $table->string('hotel_id', 20)->nullable()->change();
        });

        // Recreate the old foreign key
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('hotel_id')
                  ->references('hotel_id')
                  ->on('hotels')
                  ->onDelete('cascade');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
