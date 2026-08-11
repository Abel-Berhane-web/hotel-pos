<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\BusinessSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Categories
        $categories = [
            ['name_en' => 'Beer', 'name_am' => 'ቢራ', 'type' => 'drink', 'sort_order' => 1],
            ['name_en' => 'Wine', 'name_am' => 'ወይን', 'type' => 'drink', 'sort_order' => 2],
            ['name_en' => 'Whisky', 'name_am' => 'ዊስኪ', 'type' => 'drink', 'sort_order' => 3],
            ['name_en' => 'Soft Drink', 'name_am' => 'ለስላሳ', 'type' => 'drink', 'sort_order' => 4],
            ['name_en' => 'Water', 'name_am' => 'ውሃ', 'type' => 'drink', 'sort_order' => 5],
            ['name_en' => 'Juice', 'name_am' => 'ጭማቂ', 'type' => 'drink', 'sort_order' => 6],
            ['name_en' => 'Hot Drink', 'name_am' => 'ሞቅ መጠጥ', 'type' => 'drink', 'sort_order' => 7],
            ['name_en' => 'Food', 'name_am' => 'ምግብ', 'type' => 'food', 'sort_order' => 8],
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Business settings
        $settings = [
            'business_name_en' => 'Hotel',
            'business_name_am' => 'ሆቴል',
            'business_phone' => '',
            'currency' => 'ETB',
            'tax_rate' => '0',
            'low_stock_threshold' => '5',
        ];
        foreach ($settings as $key => $value) {
            BusinessSetting::set($key, $value);
        }
    }
}
