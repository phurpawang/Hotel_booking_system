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
        Schema::table('users', function (Blueprint $table) {
            // Add phone field (separate from mobile)
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            // Add address field for guests
            if (!Schema::hasColumn('users', 'address')) {
                if (Schema::hasColumn('users', 'mobile')) {
                    $table->text('address')->nullable()->after('mobile');
                } else {
                    $table->text('address')->nullable()->after('phone');
                }
            }
            
            // Add profile photo field
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'profile_photo']);
        });
    }
};
