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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->foreignId('guest_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('guest_name');
            $table->string('guest_email');
            
            // Individual ratings (1-10 scale)
            $table->integer('overall_rating');
            $table->integer('cleanliness_rating');
            $table->integer('staff_rating');
            $table->integer('comfort_rating');
            $table->integer('facilities_rating');
            $table->integer('value_for_money_rating');
            $table->integer('location_rating');
            
            // Guest comment
            $table->longText('comment')->nullable();
            $table->date('review_date');
            
            // Manager reply
            $table->longText('manager_reply')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('reply_date')->nullable();
            
            // Review status
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('APPROVED');
            
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate reviews per booking
            $table->unique(['booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
