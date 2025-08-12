<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\View;
use App\Mail\OrderReadyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
                      ->where('id', '>', $order->id)
                      ->orderBy('id')
                      ->first();

    return view('admin.orders.show', compact('order', 'nextOrder'));
}


public function markReady($id, Request $request)
{
    try {
        $order = Order::with('user')->findOrFail($id);
        $order->status = 'ready';
        $order->save();

        // Send Order Ready Email
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderReadyMail($order));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('order.track', ['id' => $order->id])
            ]);
        }

        return redirect()->route('admin.orders.show', $id)
                         ->with('success', 'Order marked as ready and email sent.');
    } catch (\Exception $e) {
        Log::error('Failed to mark order as ready: ' . $e->getMessage());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark order as ready. ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Failed to mark order as ready. ' . $e->getMessage());
    }
}




}
