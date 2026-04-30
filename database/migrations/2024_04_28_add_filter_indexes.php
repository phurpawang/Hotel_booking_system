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
        // Add indexes to hotels table
        Schema::table('hotels', function (Blueprint $table) {
            // Check if columns exist before indexing
            $columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'hotels'");
            $columnNames = array_map(fn($col) => $col->COLUMN_NAME, $columns);
            
            if (in_array('dzongkhag_id', $columnNames)) {
                if (!$this->indexExists('hotels', 'hotels_dzongkhag_id_index')) {
                    $table->index('dzongkhag_id');
                }
            }
            
            if (in_array('status', $columnNames)) {
                if (!$this->indexExists('hotels', 'hotels_status_index')) {
                    $table->index('status');
                }
            }
            
            if (in_array('star_rating', $columnNames)) {
                if (!$this->indexExists('hotels', 'hotels_star_rating_index')) {
                    $table->index('star_rating');
                }
            }
            
            if (in_array('property_type', $columnNames)) {
                if (!$this->indexExists('hotels', 'hotels_property_type_index')) {
                    $table->index('property_type');
                }
            }
        });

        // Add indexes to reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'reviews'");
            $columnNames = array_map(fn($col) => $col->COLUMN_NAME, $columns);
            
            if (in_array('hotel_id', $columnNames)) {
                if (!$this->indexExists('reviews', 'reviews_hotel_id_index')) {
                    $table->index('hotel_id');
                }
            }
            
            if (in_array('status', $columnNames)) {
                if (!$this->indexExists('reviews', 'reviews_status_index')) {
                    $table->index('status');
                }
            }
        });

        // Add indexes to rooms table
        Schema::table('rooms', function (Blueprint $table) {
            $columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'rooms'");
            $columnNames = array_map(fn($col) => $col->COLUMN_NAME, $columns);
            
            if (in_array('hotel_id', $columnNames)) {
                if (!$this->indexExists('rooms', 'rooms_hotel_id_index')) {
                    $table->index('hotel_id');
                }
            }
        });

        // Add indexes to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $columns = DB::select("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'bookings'");
            $columnNames = array_map(fn($col) => $col->COLUMN_NAME, $columns);
            
            if (in_array('hotel_id', $columnNames)) {
                if (!$this->indexExists('bookings', 'bookings_hotel_id_index')) {
                    $table->index('hotel_id');
                }
            }
            
            if (in_array('room_id', $columnNames)) {
                if (!$this->indexExists('bookings', 'bookings_room_id_index')) {
                    $table->index('room_id');
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
            $this->dropIndexIfExists('hotels', 'hotels_dzongkhag_id_index');
            $this->dropIndexIfExists('hotels', 'hotels_status_index');
            $this->dropIndexIfExists('hotels', 'hotels_star_rating_index');
            $this->dropIndexIfExists('hotels', 'hotels_property_type_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $this->dropIndexIfExists('reviews', 'reviews_hotel_id_index');
            $this->dropIndexIfExists('reviews', 'reviews_status_index');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $this->dropIndexIfExists('rooms', 'rooms_hotel_id_index');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $this->dropIndexIfExists('bookings', 'bookings_hotel_id_index');
            $this->dropIndexIfExists('bookings', 'bookings_room_id_index');
        });
    }

    /**
     * Check if an index exists
     */
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM $table");
        return array_filter($indexes, fn($idx) => $idx->Key_name === $indexName) ? true : false;
    }

    /**
     * Drop index if it exists
     */
    private function dropIndexIfExists($table, $indexName)
    {
        try {
            DB::statement("ALTER TABLE $table DROP INDEX $indexName");
        } catch (\Exception $e) {
            // Index doesn't exist, continue
        }
    }
};
