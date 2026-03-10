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
        // Drop existing users table
        Schema::dropIfExists('users');
        
        // Create new users table with restructured schema
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_id', 20)->nullable(); // References hotels.hotel_id
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password'); // bcrypt hashed password
            $table->enum('role', ['owner', 'manager', 'receptionist'])->default('owner');
            $table->unsignedBigInteger('created_by')->nullable(); // user who created this user
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('hotel_id')
                  ->references('hotel_id')
                  ->on('hotels')
                  ->onDelete('cascade');
                  
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
