<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RestaurantSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
    ['email' => 'test@example.com'],
    [
        'name' => 'Test User',
        'password' => bcrypt('password')
    ]
);

        RestaurantSeeder::$userId = $user->id;

        $this->call([
            RestaurantSeeder::class,
            MenuCategorySeeder::class,
            MenuItemSeeder::class,
            MealCategorySeeder::class,
            AdminSeeder::class,
        ]);
    }
}
