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
        Schema::table('hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('hotels', 'dzongkhag')) {
                if (Schema::hasColumn('hotels', 'hotel_name')) {
                    $table->string('dzongkhag')->nullable()->after('hotel_name');
                } else if (Schema::hasColumn('hotels', 'name')) {
                    $table->string('dzongkhag')->nullable()->after('name');
                } else {
                    $table->string('dzongkhag')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('dzongkhag');
        });
    }
};
