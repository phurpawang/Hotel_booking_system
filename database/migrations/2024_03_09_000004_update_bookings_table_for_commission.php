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
            // Check if columns exist before adding
            if (!Schema::hasColumn('bookings', 'base_price')) {
                $table->decimal('base_price', 10, 2)->after('total_price')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'commission_amount')) {
                $table->decimal('commission_amount', 10, 2)->after('base_price')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'payment_method_type')) {
                $table->enum('payment_method_type', ['pay_online', 'pay_at_hotel'])->after('payment_method')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = ['base_price', 'commission_amount', 'payment_method_type'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
