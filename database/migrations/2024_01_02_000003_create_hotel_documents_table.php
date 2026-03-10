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
        Schema::create('hotel_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->enum('document_type', ['TOURISM_LICENSE', 'PROPERTY_OWNERSHIP', 'LEASE_AGREEMENT', 'OTHER']);
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // pdf, jpg, png, etc.
            $table->integer('file_size')->nullable(); // in bytes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_documents');
    }
};
