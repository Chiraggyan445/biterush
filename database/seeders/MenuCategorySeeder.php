<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
{
    $restaurant = Restaurant::first(); // gets any available restaurant

    if (!$restaurant) {
        echo "❌ No restaurants found. Skipping menu category seeding.\n";
        return;
    }

    DB::table('menu_categories')->insert([
        'name' => 'Default',
        'restaurant_id' => $restaurant->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
}
