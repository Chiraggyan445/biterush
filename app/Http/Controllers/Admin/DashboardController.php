<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\MenuItem;

class DashboardController extends Controller
{
    public function index()
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Access denied');
    }

    $totalOrders = Order::where('payment_status', 'paid')->count();
    $totalUsers = User::where('role', 'customer')->count();

    $csvPath = storage_path('app/data/data1.csv');
    $totalRestaurants = file_exists($csvPath) ? count(file($csvPath)) - 1 : 0;

    $totalMeals = count(json_decode(file_get_contents(public_path('js/allMeals.json')), true));

    $recentOrders = Order::with('user')
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', compact(
        'totalOrders',
        'totalUsers',
        'totalRestaurants',
        'totalMeals',
        'recentOrders'
    ));
}


}
