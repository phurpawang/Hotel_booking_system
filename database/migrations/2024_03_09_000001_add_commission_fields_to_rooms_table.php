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
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('base_price', 10, 2)->after('price_per_night')->nullable();
            $table->decimal('commission_rate', 5, 2)->after('base_price')->default(10.00);
            $table->decimal('commission_amount', 10, 2)->after('commission_rate')->nullable();
            $table->decimal('final_price', 10, 2)->after('commission_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['base_price', 'commission_rate', 'commission_amount', 'final_price']);
        });
    }
};
