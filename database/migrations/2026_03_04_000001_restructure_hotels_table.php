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
        // Drop existing hotels table if exists
        Schema::dropIfExists('hotels');
        
        // Create new hotels table with restructured schema
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_id', 20)->unique(); // HTL001, HTL002, etc.
            $table->string('hotel_name');
            $table->string('email')->unique();
            $table->string('license_document')->nullable(); // file path for license
            $table->string('ownership_document')->nullable(); // file path for ownership document
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
