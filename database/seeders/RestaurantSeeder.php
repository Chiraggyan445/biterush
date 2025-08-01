<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\Csv\Reader;
use App\Models\User;

class RestaurantSeeder extends Seeder
{
    public static $userId;

    public function run(): void
    {
        // ✅ Ensure a default user exists for foreign key constraint
        $user = User::firstOrCreate(
            ['email' => 'admin@biterush.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        self::$userId = $user->getKey();

        DB::disableQueryLog();
        $batchSize = 250;

        $csv = Reader::createFromPath(storage_path('app/data/data1.csv'), 'r');
        $csv->setHeaderOffset(0);

        $existingSlugs = DB::table('restaurants')->pluck('slug')->toArray();
        $slugMap = array_flip($existingSlugs);

        $batch = [];
        $inserted = 0;

        foreach ($csv->getRecords() as $row) {
            $name = trim($row['name']);
            $baseSlug = Str::slug($name);
            $slug = $baseSlug;
            $i = 1;

            while (isset($slugMap[$slug])) {
                $slug = $baseSlug . '-' . $i++;
            }
            $slugMap[$slug] = true;

            $cost = preg_replace('/[^0-9]/', '', $row['cost'] ?? '');

            $menuField = $row['menu'] ?? '';
            $menuPath = public_path($menuField);

            if (
                !empty($menuField) &&
                !str_ends_with($menuField, '/') &&
                !File::exists($menuPath) &&
                str_contains($menuField, '.')
            ) {
                $fakeItems = [
                    ['name' => 'Sample Dish 1'],
                    ['name' => 'Sample Dish 2'],
                    ['name' => 'Sample Dish 3'],
                ];

                try {
                    File::ensureDirectoryExists(dirname($menuPath));
                    File::put($menuPath, json_encode($fakeItems, JSON_PRETTY_PRINT));
                    echo "📝 Created dummy menu: {$menuField}\n";
                } catch (\Exception $e) {
                    echo "❌ Error creating file {$menuField}: " . $e->getMessage() . "\n";
                }
            }

            $batch[] = [
                'name'       => $name,
                'slug'       => $slug,
                'address'    => $row['address'] ?? '',
                'city'       => $row['city'] ?? '',
                'cityname'   => $row['cityname'] ?? '',
                'menu'       => $menuField,
                'image'      => '/images/default-restaurant.jpg',
                'link'       => $row['link'] ?? null,
                'cuisine'    => $row['cuisine'] ?? null,
                'cost'       => is_numeric($cost) ? intval($cost) : null,
                'rating'     => is_numeric($row['rating']) ? floatval($row['rating']) : 0,
                'user_id'    => self::$userId,
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                DB::table('restaurants')->insert($batch);
                echo "🍽️  Inserted " . count($batch) . " restaurants...\n";
                $inserted += count($batch);
                $batch = [];
            }

            unset($row, $name, $slug, $baseSlug, $cost, $menuField, $menuPath);
        }

        if (!empty($batch)) {
            DB::table('restaurants')->insert($batch);
            $inserted += count($batch);
        }

        echo "\n✅ Total restaurants inserted: {$inserted}\n";
    }
}
