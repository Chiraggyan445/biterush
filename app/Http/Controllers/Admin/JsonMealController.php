<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class JsonMealController extends Controller
{
    protected $jsonPath = 'js/allMeals.json';

    private function getMeals(): array
    {
        $path = public_path($this->jsonPath);
        if (!File::exists($path)) {
            return [];
        }

        $meals = json_decode(File::get($path), true) ?? [];

        // Add slug to old meals if missing
        foreach ($meals as &$meal) {
            if (!isset($meal['slug']) && isset($meal['name'])) {
                $meal['slug'] = Str::slug($meal['name']);
            }
        }

        $this->saveMeals($meals);
        return $meals;
    }

    private function saveMeals(array $meals): void
    {
        File::put(public_path($this->jsonPath), json_encode($meals, JSON_PRETTY_PRINT));
    }

    public function index()
    {
        $meals = $this->getMeals();
        return view('admin.meals.index', compact('meals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image',
        ]);

        $meals = $this->getMeals();

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('images/dishes'), $imageName);

        $newMeal = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => 'images/dishes/' . $imageName,
        ];

        $meals[] = $newMeal;
        $this->saveMeals($meals);

        return redirect()->route('admin.meals.index')->with('success', 'Meal added!');
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image',
        ]);

        $meals = $this->getMeals();

        foreach ($meals as &$meal) {
            if ($meal['slug'] === $slug) {
                $meal['name'] = $request->name;
                $meal['slug'] = Str::slug($request->name);
                $meal['description'] = $request->description;

                if ($request->hasFile('image')) {
                    $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
                    $request->file('image')->move(public_path('images/dishes'), $imageName);
                    $meal['image'] = 'images/dishes/' . $imageName;
                }

                break;
            }
        }

        $this->saveMeals($meals);

        return redirect()->route('admin.meals.index')->with('success', 'Meal updated!');
    }

    public function destroy($slug)
    {
        $meals = $this->getMeals();
        $meals = array_filter($meals, fn($m) => $m['slug'] !== $slug);
        $this->saveMeals(array_values($meals));

        return redirect()->route('admin.meals.index')->with('success', 'Meal deleted!');
    }
}