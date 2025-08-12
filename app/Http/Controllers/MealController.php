<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Restaurant;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
class MealController extends Controller
{
   public function allMealsWithRestaurants(Request $request)
{

    if (!Auth::check()) {
    return redirect()->back()->with('login_required', 'Please log in to continue.');
}


    ini_set('max_execution_time', 60); 

    $selectedCity = session('selected_city');
    if (!$selectedCity) {
        return redirect()->route('search')->with('error', 'Please select a city first.');
    }

    $meals = collect(json_decode(file_get_contents(public_path('js/allMeals.json')), true));

    $processedMeals = $meals->map(function ($meal) {
        $meal['slug'] = Str::slug($meal['name']);
        $meal['restaurants'] = []; 
        return $meal;
    });

    return view('all-meals', [
        'meals' => $processedMeals,
        'selectedCity' => $selectedCity,
        'cities' => Restaurant::pluck('city')->unique(),
    ]);
}

public function showRestaurants($slug)
{

    if (!Auth::check()) {
    return redirect()->back()->with('login_required', 'Please log in to continue.');
}


    $selectedCity = session('selected_city');
    if (!$selectedCity) {
        return redirect()->route('search')->with('error', 'Please select a city first.');
    }

    $meals = collect(json_decode(file_get_contents(public_path('js/allMeals.json')), true))
        ->map(function ($meal) {
            $meal['slug'] = Str::slug($meal['name']);
            return $meal;
        });

    $meal = $meals->firstWhere('slug', $slug);
    if (!$meal) {
        return redirect()->route('all-meals')->with('error', 'Meal not found.');
    }

    $mealName = strtolower($meal['name']);
    $keywords = array_filter(explode(' ', $mealName));
    $searchQuery = strtolower(request('search'));

    $query = Restaurant::select('restaurants.*')
        ->join('menu_items', 'menu_items.restaurant_id', '=', 'restaurants.id')
        ->where('restaurants.status', 'active')
        ->whereRaw('LOWER(restaurants.cityname) = ?', [strtolower($selectedCity)])
        ->where(function ($q) use ($mealName, $keywords) {
            $q->whereRaw('LOWER(menu_items.meal_name) LIKE ?', ["%$mealName%"]);
            foreach ($keywords as $word) {
                $q->orWhereRaw('LOWER(menu_items.meal_name) LIKE ?', ["%$word%"]);
            }
        });

    if (!empty($searchQuery)) {
        $query->whereRaw('LOWER(restaurants.name) LIKE ?', ["%$searchQuery%"]);
    }

    $restaurants = $query->distinct()->orderByDesc('restaurants.rating')->get();

    $perPage = 12;
    $page = request()->get('page', 1);
    $paginated = new LengthAwarePaginator(
        $restaurants->forPage($page, $perPage),
        $restaurants->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    if (request()->ajax()) {
        return view('meals.restaurants', [
            'restaurants' => $paginated,
            'meal' => $meal,
        ])->render();
    }

    return view('meals.restaurants', [
        'meal' => $meal,
        'restaurants' => $paginated,
        'selectedCity' => $selectedCity,
        'mealname' => $meal['name'],
        'searchQuery' => $searchQuery,
    ]);
}

}

