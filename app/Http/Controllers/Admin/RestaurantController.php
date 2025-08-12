<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $restaurants = $query->latest()->paginate(10);

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'status' => 'required|in:pending,approved,suspended',
        ]);

        Restaurant::create($request->only('name', 'address', 'city', 'status'));

        return back()->with('success', 'Restaurant added successfully.');
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'status' => 'required|in:pending,approved,suspended',
        ]);

        $restaurant->update($request->only('name', 'address', 'city', 'status'));

        return back()->with('success', 'Restaurant updated successfully.');
    }

    public function destroy($id)
    {
        Restaurant::findOrFail($id)->delete();

        return back()->with('success', 'Restaurant deleted successfully.');
    }
}
