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
        Schema::create('hotel_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->year('year');
            $table->tinyInteger('month'); // 1-12
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_guest_payments', 12, 2)->default(0); // Total amount guests paid
            $table->decimal('total_commission', 12, 2)->default(0); // Total commission owed to platform
            $table->decimal('hotel_payout_amount', 12, 2)->default(0); // Amount to be paid to hotel
            $table->decimal('pay_online_amount', 12, 2)->default(0); // Amount from online payments
            $table->decimal('pay_at_hotel_amount', 12, 2)->default(0); // Amount from pay at hotel
            $table->enum('payout_status', ['pending', 'processing', 'paid', 'cancelled'])->default('pending');
            $table->date('payout_date')->nullable(); // Date when payout was made
            $table->text('payout_notes')->nullable();
            $table->string('payout_reference')->nullable(); // Transaction reference
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // Unique constraint: one payout record per hotel per month
            $table->unique(['hotel_id', 'year', 'month']);
            $table->index(['year', 'month', 'payout_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_payouts');
    }
};
