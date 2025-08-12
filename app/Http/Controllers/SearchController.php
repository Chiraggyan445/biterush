<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{


    
    public function showSearchPage(Request $request)
    {

          if (!Auth::check()) {
        return redirect('/home')->with('login_required', 'Please log in to continue.');
    }


        $datasetCities = Restaurant::pluck('cityname')
            ->map(fn($city) => ucwords(trim($city)))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $manualCities = collect([
            'Ahmedabad', 'Bangalore', 'Chennai', 'Delhi', 'Hyderabad',
            'Indore', 'Jaipur', 'Kolkata', 'Lucknow', 'Mumbai', 'Pune', 'Surat',
            'Thane', 'Noida', 'Gurgaon', 'Bhopal', 'Nagpur', 'Rajkot', 'Vadodara',
            'Patna', 'Ranchi', 'Coimbatore', 'Vijayawada', 'Vizag', 'Ludhiana'
        ]);

        $cities = $datasetCities->merge($manualCities)
            ->unique()
            ->sort()
            ->values();

        $selectedCity = Session::get('selected_city');

        return view('search', compact('cities', 'selectedCity'));
    }

public function storeCity(Request $request)
{
    

    $request->validate(['city' => 'required|string']);
    Session::put('selected_city', $request->input('city'));

    if (session()->has('pending_category')) {
        $slug = session()->pull('pending_category');
        return redirect()->route('category.redirect', ['slug' => $slug]);
    }

    return redirect()->route('all-meals');
}


    // ❌ Clear selected city from session
    public function clearCity()
    {
        Session::forget('selected_city');
        return redirect()->back();
    }

    // 🔍 AJAX Search Meals
    public function searchMeals(Request $request)
    {
        $query = strtolower($request->input('q'));

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $path = public_path('js/allMeals.json');

        if (!file_exists($path)) {
            return response()->json(['error' => 'Meal dataset not found.'], 404);
        }

        $meals = collect(json_decode(file_get_contents($path), true));

        $results = $meals->filter(function ($meal) use ($query) {
            $name = strtolower($meal['name'] ?? '');
            return str_starts_with($name, $query) || str_contains($name, " $query");
        })->take(20)->values();

        return response()->json($results);
    }
}
