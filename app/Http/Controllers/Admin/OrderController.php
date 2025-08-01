<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'restaurant', 'items.menuItem', 'address'])->findOrFail($id);

        
        $nextOrder = Order::where('status', '!=', 'ready')
                          ->where('id', '>', $id)
                          ->orderBy('id')
                          ->first();

        return view('admin.orders.show', compact('order', 'nextOrder'));
    }

    public function markReady($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'ready';
        $order->save();

        return redirect()->route('admin.orders.show', $id)->with('success', 'Order marked as ready.');
    }
}
