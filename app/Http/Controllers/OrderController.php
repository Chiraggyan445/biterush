<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show()
    {
        $cart = session()->get('cart', []);
        return view('place.order', compact('cart'));
    }

    public function applyCoupon(Request $request)
{
    $code = strtoupper($request->input('code'));

    if (Session::has('coupon_code')) {
        return response()->json([
            'success' => false,
            'message' => 'Coupon already applied.'
        ]);
    }

    if ($code === 'CHIRAG100' && !Session::has('coupon_used')) {
        Session::put('discount', 100);
        Session::put('coupon_code', $code);
        Session::put('coupon_used', true); 

        return response()->json([
            'success' => true,
            'discount' => 100,
            'message' => 'Coupon applied successfully.'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid or already used coupon code.'
    ]);
}

private function calculateCartTotal()
{
    $cart = session('cart', []);
    $total = 0;

    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $discount = session('discount', 0);

    return max($total - $discount, 0);
}


public function submit(Request $request)
{
    $cart = session('cart', []);
    if (empty($cart)) {
        return response()->json(['success' => false, 'message' => 'Cart is empty.']);
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $deliveryFee = 40;
    $discount = session('discount', 0);
    $finalAmount = $total - $discount + $deliveryFee;

    try {
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'placed',
            'payment_status' => 'paid',
            'total_amount' => $finalAmount,
            'delivery_address_id' => null,
            'restaurant_id' => $cart[0]['restaurant_id'] ?? 1, // fallback if needed
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget(['cart', 'discount']);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Order save failed: ' . $e->getMessage()
        ]);
    }
}


    public function confirmMultipleDeliveries(Request $request)
{
    $agents = [
        [
            'name' => 'Ravi Kumar',
            'eta' => '25 mins',
            'otp' => rand(1000, 9999),
            'from' => [28.6139, 77.2090],
            'to' => [28.6245, 77.2180]
        ],
        [
            'name' => 'Sneha Mehta',
            'eta' => '18 mins',
            'otp' => rand(1000, 9999),
            'from' => [28.6350, 77.2200],
            'to' => [28.6485, 77.2340]
        ],
        [
            'name' => 'Amit Verma',
            'eta' => '12 mins',
            'otp' => rand(1000, 9999),
            'from' => [28.5950, 77.2000],
            'to' => [28.6040, 77.2150]
        ]
    ];

    $deliveryAddress = $request->input('address');
    
    $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
        'address' => $deliveryAddress,
        'key' => config('services.google_maps.key') 
    ]);

    $geoData = $response->json();

    if (!empty($geoData['results'][0]['geometry']['location'])) {
        $destination = $geoData['results'][0]['geometry']['location'];
    } else {
        $destination = ['lat' => 28.6245, 'lng' => 77.2180]; 
    }

    $selectedAgent = $agents[array_rand($agents)];
    $selectedAgent['to'] = [$destination['lat'], $destination['lng']];

    return view('order.track', ['agent' => $selectedAgent]);
}

public function refund(Request $request)
{
    return response()->json([
        'success' => true,
        'message' => 'Refund has been initiated successfully!'
    ]);
}

}
