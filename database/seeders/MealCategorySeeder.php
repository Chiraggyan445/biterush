<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MealCategorySeeder extends Seeder
{
    public function run(): void
    {
        $path = public_path('js/allMeals.json');
        $meals = json_decode(file_get_contents($path), true);

        foreach ($meals as &$meal) {
            if (!isset($meal['category']) || strtolower($meal['category']) === 'uncategorized') {
                $name = strtolower($meal['name'] ?? '');

                if (Str::contains($name, ['paneer', 'biryani', 'masala', 'dal'])) {
                    $meal['category'] = 'Main Course';
                } elseif (Str::contains($name, ['chaat', 'momos', 'samosa', 'pakora'])) {
                    $meal['category'] = 'Snacks';
                } elseif (Str::contains($name, ['cake', 'ice cream', 'halwa', 'sweet'])) {
                    $meal['category'] = 'Desserts';
                } elseif (Str::contains($name, ['juice', 'shake', 'lemonade', 'lassi'])) {
                    $meal['category'] = 'Drinks & Beverages';
                } elseif (Str::contains($name, ['thali', 'platter'])) {
                    $meal['category'] = 'Indian Thali';
                } else {
                    $meal['category'] = 'Others';
                }
            }
        }

        file_put_contents($path, json_encode($meals, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "✅ Updated 'Uncategorized' meals successfully.\n";
    }
}
