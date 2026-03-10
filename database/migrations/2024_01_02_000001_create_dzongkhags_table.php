<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dzongkhags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Insert all 20 Dzongkhags of Bhutan
        DB::table('dzongkhags')->insert([
            ['name' => 'Bumthang'],
            ['name' => 'Chhukha'],
            ['name' => 'Dagana'],
            ['name' => 'Gasa'],
            ['name' => 'Haa'],
            ['name' => 'Lhuentse'],
            ['name' => 'Mongar'],
            ['name' => 'Paro'],
            ['name' => 'Pemagatshel'],
            ['name' => 'Punakha'],
            ['name' => 'Samdrup Jongkhar'],
            ['name' => 'Samtse'],
            ['name' => 'Sarpang'],
            ['name' => 'Thimphu'],
            ['name' => 'Trashigang'],
            ['name' => 'Trashiyangtse'],
            ['name' => 'Trongsa'],
            ['name' => 'Tsirang'],
            ['name' => 'Wangdue Phodrang'],
            ['name' => 'Zhemgang'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dzongkhags');
    }
};
