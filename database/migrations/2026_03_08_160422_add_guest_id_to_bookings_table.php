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
            if (!Schema::hasColumn('bookings', 'guest_id')) {
                $table->unsignedBigInteger('guest_id')->nullable()->after('user_id');
                $table->foreign('guest_id')->references('id')->on('guests')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'guest_id')) {
                $table->dropForeign(['guest_id']);
                $table->dropColumn('guest_id');
            }
        });
    }
};
