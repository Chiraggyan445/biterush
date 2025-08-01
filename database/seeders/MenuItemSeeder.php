<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Restaurant;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        echo "🚀 MenuItemSeeder started...\n";

        // 1. Load restaurants
        $restaurants = Restaurant::all();
        echo "✅ Loaded {$restaurants->count()} restaurants.\n";

        // 2. Load meals from JSON
        $jsonPath = public_path('js/allMeals.json');
        if (!file_exists($jsonPath)) {
            echo "❌ meals.json not found at $jsonPath\n";
            return;
        }

        $meals = json_decode(File::get($jsonPath), true);
        if (!$meals || empty($meals)) {
            echo "❌ meals.json is empty or invalid.\n";
            return;
        }

        echo "✅ Loaded " . count($meals) . " meals from JSON.\n";

        $batch = [];
        $totalInserted = 0;

        foreach ($restaurants as $restaurant) {
            $randomMeals = collect($meals)->random(min(5, count($meals)));

            foreach ($randomMeals as $meal) {
                $mealName = $meal['name'] ?? 'Unnamed Meal';
                $image = $meal['image'] ?? null;
                $description = $meal['description'] ?? null;

                // 🛡️ Generate 100% unique slug
                do {
                    $slug = Str::slug($mealName . '-' . Str::random(6));
                } while (DB::table('menu_items')->where('slug', $slug)->exists());

                $batch[] = [
                    'restaurant_id' => $restaurant->getKey(),
                    'category_id'   => null, // Or assign if needed
                    'meal_name'     => $mealName,
                    'image'         => $image,
                    'slug'          => $slug,
                    'description'   => $description,
                    'available'     => true,
                    'price'         => rand(100, 500),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                echo "🧾 Seeding '{$mealName}' for Restaurant ID: {$restaurant->getKey()}\n";

                // Insert in batches of 1000
                if (count($batch) >= 1000) {
                    try {
                        DB::table('menu_items')->insert($batch);
                        $totalInserted += count($batch);
                        echo "✅ Inserted batch of " . count($batch) . " items. Total: $totalInserted\n\n";
                        $batch = [];
                    } catch (\Exception $e) {
                        echo "❌ Insert error: " . $e->getMessage() . "\n";
                    }
                }
            }
        }

        // Insert any remaining items
        if (!empty($batch)) {
            try {
                DB::table('menu_items')->insert($batch);
                $totalInserted += count($batch);
                echo "✅ Inserted final batch of " . count($batch) . " items. Total: $totalInserted\n";
            } catch (\Exception $e) {
                echo "❌ Final insert error: " . $e->getMessage() . "\n";
            }
        }

        echo "🎉 Seeding complete! Total menu items inserted: $totalInserted\n";
    }
}
