<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHotelPayoutsForPaymentMethods extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hotel_payouts', function (Blueprint $table) {
            // Add fields to track online vs offline payment commissions
            $table->decimal('online_payment_amount', 12, 2)->default(0)->after('pay_at_hotel_amount')->comment('Total amount from online payments');
            $table->decimal('offline_payment_amount', 12, 2)->default(0)->after('online_payment_amount')->comment('Total amount from offline payments');
            $table->decimal('online_commission_amount', 12, 2)->default(0)->after('offline_payment_amount')->comment('Commission deducted from online payments');
            $table->decimal('offline_commission_due', 12, 2)->default(0)->after('online_commission_amount')->comment('Commission due from hotel for offline payments');
            $table->decimal('online_payout_amount', 12, 2)->default(0)->after('offline_commission_due')->comment('Payout for online payments (after commission)');
            
            // Add status fields for tracking
            $table->enum('online_payout_status', ['pending', 'paid', 'cancelled'])->default('pending')->after('payout_status');
            $table->enum('offline_commission_status', ['pending', 'received', 'cancelled'])->default('pending')->after('online_payout_status');
            
            // Add dates for offline commission receipt
            $table->timestamp('offline_commission_received_at')->nullable()->after('offline_commission_status');
            
            // Add reference tracking
            $table->string('offline_commission_reference')->nullable()->after('payout_reference')->comment('Reference for offline commission payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_payouts', function (Blueprint $table) {
            $table->dropColumn([
                'online_payment_amount',
                'offline_payment_amount',
                'online_commission_amount',
                'offline_commission_due',
                'online_payout_amount',
                'online_payout_status',
                'offline_commission_status',
                'offline_commission_received_at',
                'offline_commission_reference',
            ]);
        });
    }
}
