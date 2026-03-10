<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reviews table
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
                $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
                $table->string('guest_name');
                $table->string('guest_email');
                $table->integer('rating'); // 1-5
                $table->text('comment')->nullable();
                $table->text('reply')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->timestamps();
            });
        }

        // Amenities table
        if (!Schema::hasTable('amenities')) {
            Schema::create('amenities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
                $table->string('name');
                $table->string('icon')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Promotions table
        if (!Schema::hasTable('promotions')) {
            Schema::create('promotions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('discount_percentage', 5, 2);
                $table->date('start_date');
                $table->date('end_date');
                $table->json('applicable_room_types')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Notifications table
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('type'); // new_booking, booking_cancelled, new_review, payment_received
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }

        // Payments table - extend bookings payment info
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
                $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('payment_method'); // cash, card, bank_transfer, online
                $table->enum('status', ['PENDING', 'PAID', 'REFUNDED', 'FAILED'])->default('PENDING');
                $table->string('transaction_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('reviews');
    }
};
