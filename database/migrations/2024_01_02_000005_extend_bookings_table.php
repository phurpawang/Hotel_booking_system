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
        Schema::table('bookings', function (Blueprint $table) {
            // Add unique booking ID
            $table->string('booking_id')->unique()->after('id');
            
            // Add hotel reference
            $table->foreignId('hotel_id')->nullable()->constrained('hotels')->after('booking_id');
            
            // Add number of rooms
            $table->integer('num_rooms')->default(1)->after('num_guests');
            
            // Add payment status
            $table->enum('payment_status', ['PENDING', 'PAID', 'REFUNDED', 'FAILED'])->default('PENDING')->after('total_price');
            
            // Add payment method
            $table->string('payment_method')->nullable()->after('payment_status'); // 'ONLINE' or 'PAY_AT_HOTEL'
            
            // Add cancellation fields
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            $table->decimal('refund_amount', 10, 2)->nullable()->after('cancellation_reason');
            
            // Update status enum
            $table->enum('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN', 'CHECKED_OUT', 'CANCELLED'])->default('PENDING')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['hotel_id']);
            $table->dropColumn([
                'booking_id',
                'hotel_id',
                'num_rooms',
                'payment_status',
                'payment_method',
                'cancelled_at',
                'cancellation_reason',
                'refund_amount'
            ]);
        });
    }
};
