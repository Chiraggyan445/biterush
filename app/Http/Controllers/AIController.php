<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AIController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => [
                'required', 'integer', 'distinct', 'exists:menu_items,id',
            ],
            'items.*.quantity' => [
                'required', 'integer', 'min:1', 'max:50',
            ],
            'scheduled_time' => ['nullable', 'date', 'after:now'],
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $menuItems = MenuItem::whereIn(
                'id',
                collect($validated['items'])->pluck('menu_item_id')
            )->lockForUpdate()->get()->keyBy('id');

            $restaurantIds = $menuItems->pluck('restaurant_id')->unique();

            if (
                $menuItems->count() !== count($validated['items']) ||
                $restaurantIds->count() !== 1
            ) {
                throw ValidationException::withMessages([
                    'items' => 'Select valid items from one restaurant.',
                ]);
            }

            $total = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $menuItem = $menuItems->get($item['menu_item_id']);
                $quantity = (int) $item['quantity'];
                $price = (float) $menuItem->price;

                $total += $price * $quantity;

                $orderItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'restaurant_id' => $restaurantIds->first(),
                'delivery_address_id' => null,
                'status' => 'pending',
                'total_amount' => round($total, 2),
                'payment_status' => 'pending',
                'scheduled_time' => $validated['scheduled_time'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'order_id' => $order->id,
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
        ], 201);
    }
}
