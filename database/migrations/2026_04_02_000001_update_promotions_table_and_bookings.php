<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update promotions table to support room-specific promotions and fixed discounts
        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                // Add room_id for room-specific promotions (nullable - null means applies to entire hotel)
                if (!Schema::hasColumn('promotions', 'room_id')) {
                    $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('cascade')->after('hotel_id');
                }
                
                // Update discount to support both percentage and fixed amounts
                if (!Schema::hasColumn('promotions', 'discount_type')) {
                    $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage')->after('description');
                }
            });

            // Handle discount_percentage to discount_value migration
            if (Schema::hasColumn('promotions', 'discount_percentage') && !Schema::hasColumn('promotions', 'discount_value')) {
                // Copy data from discount_percentage to discount_value
                DB::statement('ALTER TABLE promotions ADD COLUMN discount_value DECIMAL(5, 2) AFTER discount_type');
                DB::statement('UPDATE promotions SET discount_value = discount_percentage WHERE discount_percentage IS NOT NULL');
                DB::statement('ALTER TABLE promotions DROP COLUMN discount_percentage');
            }
        }

        // Update bookings table to store promotion details
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                // Store promotion details
                if (!Schema::hasColumn('bookings', 'promotion_id')) {
                    $table->foreignId('promotion_id')->nullable()->constrained('promotions')->onDelete('set null')->after('total_price');
                }
                
                if (!Schema::hasColumn('bookings', 'original_price')) {
                    $table->decimal('original_price', 10, 2)->nullable()->after('total_price');
                }
                
                if (!Schema::hasColumn('bookings', 'discount_applied')) {
                    $table->decimal('discount_applied', 10, 2)->default(0)->after('original_price');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'discount_applied')) {
                    $table->dropColumn('discount_applied');
                }
                if (Schema::hasColumn('bookings', 'original_price')) {
                    $table->dropColumn('original_price');
                }
                if (Schema::hasColumn('bookings', 'promotion_id')) {
                    $table->dropForeignKeyIfExists(['promotion_id']);
                    $table->dropColumn('promotion_id');
                }
            });
        }

        if (Schema::hasTable('promotions')) {
            Schema::table('promotions', function (Blueprint $table) {
                if (Schema::hasColumn('promotions', 'discount_type')) {
                    $table->dropColumn('discount_type');
                }
                if (Schema::hasColumn('promotions', 'discount_value')) {
                    $table->dropColumn('discount_value');
                    // Add back discount_percentage for backward compatibility if needed
                    $table->decimal('discount_percentage', 5, 2)->after('description')->nullable();
                }
                if (Schema::hasColumn('promotions', 'room_id')) {
                    $table->dropForeignKeyIfExists(['room_id']);
                    $table->dropColumn('room_id');
                }
            });
        }
    }
};
