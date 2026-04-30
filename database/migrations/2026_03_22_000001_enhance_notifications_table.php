<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Add target_role column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'target_role')) {
                $table->enum('target_role', ['owner', 'manager', 'reception', 'admin'])
                    ->default('owner')
                    ->after('type');
            }

            // Add link column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'link')) {
                $table->string('link')
                    ->nullable()
                    ->after('message');
            }

            // Add booking_id column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'booking_id')) {
                $table->foreignId('booking_id')
                    ->nullable()
                    ->constrained('bookings')
                    ->onDelete('cascade')
                    ->after('hotel_id');
            }

            // Add review_id column if it doesn't exist
            if (!Schema::hasColumn('notifications', 'review_id')) {
                $table->foreignId('review_id')
                    ->nullable()
                    ->constrained('reviews')
                    ->onDelete('cascade')
                    ->after('booking_id');
            }

            // Rename is_read to status if it exists (with data migration)
            if (Schema::hasColumn('notifications', 'is_read') && !Schema::hasColumn('notifications', 'status')) {
                // We'll just add a status column and keep is_read for now
                // The service layer will handle the transition
                $table->string('status')->default('unread')->after('data');
            }

            // Add soft deletes if not already there
            if (!Schema::hasColumn('notifications', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop columns in reverse order
            if (Schema::hasColumn('notifications', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
            if (Schema::hasColumn('notifications', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('notifications', 'review_id')) {
                $table->dropForeign(['review_id']);
                $table->dropColumn('review_id');
            }
            if (Schema::hasColumn('notifications', 'booking_id')) {
                $table->dropForeign(['booking_id']);
                $table->dropColumn('booking_id');
            }
            if (Schema::hasColumn('notifications', 'link')) {
                $table->dropColumn('link');
            }
            if (Schema::hasColumn('notifications', 'target_role')) {
                $table->dropColumn('target_role');
            }
        });
    }
};
