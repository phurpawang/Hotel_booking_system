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
        Schema::table('hotel_inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('hotel_inquiries', 'answer')) {
                $table->longText('answer')->nullable()->after('question');
            }
            if (!Schema::hasColumn('hotel_inquiries', 'answered_at')) {
                $table->timestamp('answered_at')->nullable()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_inquiries', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_inquiries', 'answer')) {
                $table->dropColumn('answer');
            }
            if (Schema::hasColumn('hotel_inquiries', 'answered_at')) {
                $table->dropColumn('answered_at');
            }
        });
    }
};
