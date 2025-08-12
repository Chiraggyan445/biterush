<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SmartSuggestionController extends Controller
{
    public function index()
    {
        // Redirect admins away from /home
        // if (Auth::check() && Auth::user()->role === 'admin') {
        //     session()->flash('not_admin', 'Admins are not allowed here.');
        //     return redirect('/admin'); // Or redirect anywhere appropriate
        // }

        // Load meals
        $meals = collect(json_decode(file_get_contents(public_path('js/allMeals.json')), true));

        $suggestedMeals = $meals->shuffle()->take(5)->map(function ($meal) {
            return [
                'name' => $meal['name'],
                'slug' => $meal['slug'] ?? Str::slug($meal['name']),
            ];
        });

        return view('home', compact('suggestedMeals'));
    }
}
