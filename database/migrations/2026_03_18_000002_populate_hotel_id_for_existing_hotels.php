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
        // Make hotel_id nullable if it isn't already
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('hotel_id', 20)->nullable()->change();
        });

        // Generate hotel_id for any existing hotels that don't have one
        $hotels = DB::table('hotels')->whereNull('hotel_id')->get();
        
        foreach ($hotels as $hotel) {
            // Find the next available hotel ID
            $lastHotel = DB::table('hotels')->whereNotNull('hotel_id')->orderBy('id', 'desc')->first();
            
            if (!$lastHotel) {
                $newHotelId = 'HTL001';
            } else {
                $lastNumber = (int) substr($lastHotel->hotel_id, 3);
                $newNumber = $lastNumber + 1;
                $newHotelId = 'HTL' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }
            
            DB::table('hotels')->where('id', $hotel->id)->update(['hotel_id' => $newHotelId]);
        }

        // Make hotel_id NOT nullable again
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('hotel_id', 20)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Make hotel_id nullable for rollback
        Schema::table('hotels', function (Blueprint $table) {
            $table->string('hotel_id', 20)->nullable()->change();
        });
    }
};
