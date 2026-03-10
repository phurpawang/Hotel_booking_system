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
        Schema::create('booking_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->decimal('base_amount', 10, 2); // Base price for the booking
            $table->decimal('commission_rate', 5, 2); // Commission rate at time of booking
            $table->decimal('commission_amount', 10, 2); // Commission amount calculated
            $table->decimal('final_amount', 10, 2); // Final amount guest paid
            $table->enum('payment_method', ['pay_online', 'pay_at_hotel']);
            $table->enum('commission_status', ['pending', 'paid'])->default('pending');
            $table->date('booking_date'); // Date when booking was made
            $table->date('check_in_date'); // Check-in date
            $table->date('check_out_date'); // Check-out date
            $table->timestamps();
            
            // Indexes for reporting
            $table->index('hotel_id');
            $table->index('booking_date');
            $table->index('commission_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_commissions');
    }
};
