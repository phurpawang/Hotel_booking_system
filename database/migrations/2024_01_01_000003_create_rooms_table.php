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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->string('room_number');
            $table->string('room_type'); // Single, Double, Suite, Deluxe
            $table->integer('capacity');
            $table->decimal('price_per_night', 10, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['AVAILABLE', 'OCCUPIED', 'CLEANING', 'MAINTENANCE'])->default('AVAILABLE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
