<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dzongkhag;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@bhbs.com',
            'password' => Hash::make('admin123'),
            'role' => 'ADMIN',
            'status' => 'active',
            'mobile' => '17634567',
        ]);

        // Create dzongkhags (all 20 Bhutanese districts in alphabetical order)
        $dzongkhags = [
            'Bumthang',
            'Chhukha',
            'Dagana',
            'Gasa',
            'Haa',
            'Lhuentse',
            'Mongar',
            'Paro',
            'Pemagatshel',
            'Punakha',
            'Samdrup Jongkhar',
            'Samtse',
            'Sarpang',
            'Thimphu',
            'Trashigang',
            'Trashiyangtse',
            'Trongsa',
            'Tsirang',
            'Wangdue Phodrang',
            'Zhemgang',
        ];

        foreach ($dzongkhags as $dzongkhag) {
            Dzongkhag::firstOrCreate(['name' => $dzongkhag]);
        }
    }
}
