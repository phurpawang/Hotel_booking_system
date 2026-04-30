<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert all uppercase status values to lowercase
        DB::table('hotels')->where('status', 'PENDING')->update(['status' => 'pending']);
        DB::table('hotels')->where('status', 'APPROVED')->update(['status' => 'approved']);
        DB::table('hotels')->where('status', 'REJECTED')->update(['status' => 'rejected']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to uppercase if needed
        DB::table('hotels')->where('status', 'pending')->update(['status' => 'PENDING']);
        DB::table('hotels')->where('status', 'approved')->update(['status' => 'APPROVED']);
        DB::table('hotels')->where('status', 'rejected')->update(['status' => 'REJECTED']);
    }
};
