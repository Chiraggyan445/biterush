<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function showCategory($slug)
    {

        $allMeals = json_decode(file_get_contents(public_path('js/allMeals.json')), true);

        $mealsWithSlugs = collect($allMeals)->map(function ($meal) {
            if (!isset($meal['slug']) && isset($meal['name'])) {
                $meal['slug'] = Str::slug($meal['name']);
            }
            return $meal;
        });

        $filteredMeals = $mealsWithSlugs->filter(function ($meal) use ($slug) {
            return isset($meal['category']) && Str::slug($meal['category']) === $slug;
        })->values(); 

        return view('category.main', [
            'meals' => $filteredMeals,
            'category' => ucwords(str_replace('-', ' ', $slug))
        ]);
    }

    public function redirectToFirstMealInCategory($slug){
        
        $selectedCity = session('selected_city');

    if (!$selectedCity) {
        
        session(['pending_category' => $slug]);
        return redirect()->route('search')->with('please_select_city', true);
    }

    $allMeals = json_decode(file_get_contents(public_path('js/allMeals.json')), true);

    $meals = collect($allMeals)->filter(function ($meal) use ($slug) {
        return isset($meal['category']) && Str::slug($meal['category']) === $slug;
    });

    if ($meals->isEmpty()) {
        return back()->with('error', 'No meals found for this category.');
    }

    $firstMeal = $meals->first();
    return redirect()->route('restaurants.by.meal', ['slug' => $firstMeal['slug']]);
}

}
