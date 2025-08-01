<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
  public function show($id)
{
    if (!Auth::check()) {
        return redirect('/home')->with('login_required', true);
    }

    $restaurant = Restaurant::findOrFail($id);
    $meal = request()->query('meal'); // e.g., onion-bhaji

    $mealSearch = $meal ? trim(str_replace('-', ' ', strtolower($meal))) : null;

    $menuItems = collect();
    $baseQuery = MenuItem::where('restaurant_id', $restaurant->id);

    if ($mealSearch) {
        // Step 1: exact match (normalized)
        $exact = (clone $baseQuery)
            ->whereRaw("REPLACE(LOWER(meal_name), '-', ' ') = ?", [$mealSearch])
            ->get();

        // Step 2: keyword-based related match
        $keywords = array_filter(explode(' ', $mealSearch)); // e.g., ["onion", "bhaji"]

        $related = (clone $baseQuery)
            ->where(function ($query) use ($keywords, $mealSearch) {
                foreach ($keywords as $word) {
                    $query->orWhereRaw('LOWER(meal_name) LIKE ?', ['%' . $word . '%']);
                }
            })
            ->when(!$exact->isEmpty(), function ($query) use ($mealSearch) {
                $query->whereRaw("REPLACE(LOWER(meal_name), '-', ' ') != ?", [$mealSearch]);
            })
            ->get();

        // Step 3: exclude exact + related meals
        $excluded = $exact->pluck('meal_name')
            ->merge($related->pluck('meal_name'))
            ->map(fn($n) => strtolower($n))
            ->unique()
            ->values()
            ->all();

        $other = empty($excluded) ? collect() : (clone $baseQuery)
            ->whereRaw('LOWER(meal_name) NOT IN (' . implode(',', array_fill(0, count($excluded), '?')) . ')', $excluded)
            ->get();

        // Final results: exact + related + others
        $menuItems = $menuItems->merge($exact)->merge($related)->merge($other);
    } else {
        $menuItems = $baseQuery->get();
    }

    return view('restaurants.show', [
        'restaurant' => $restaurant,
        'menuItems' => $menuItems,
        'mealName' => $mealSearch ? ucwords($mealSearch) : 'All Dishes',
    ]);
}


}
