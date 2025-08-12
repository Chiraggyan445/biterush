<?php

namespace App\Http\Controllers;

use App\Mail\PlaceOrderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

    // Available coupons with their discount percentages
    $availableCoupons = [
        'CHIRAG10' => 10,
        'BITE20'   => 20,
        'SAVE30'   => 30,
        'MEGA50'   => 50,
    ];

    if (!array_key_exists($code, $availableCoupons)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired coupon code.'
        ]);
    }

    // Calculate discount from current cart session
    $cart = Session::get('cart', []);
    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Your cart is empty.'
        ]);
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $discountPercent = $availableCoupons[$code];
    $discountAmount = round(($discountPercent / 100) * $total);

    // Store in session
    Session::put('discount', $discountAmount);
    Session::put('coupon_code', $code);
    Session::put('coupon_used', true);

    return response()->json([
        'success' => true,
        'discount' => $discountAmount,
        'message' => "$discountPercent% coupon applied successfully!"
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
    $request->validate([
        'scheduled_time' => 'nullable|date|after:now',
    ]);

    $scheduledTime = $request->input('scheduled_time');
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
            'restaurant_id' => $cart[0]['restaurant_id'] ?? 1,
            'scheduled_time' => $scheduledTime,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget(['cart', 'discount']);

        $order->load('user'); // make sure user relation is loaded
        Mail::to($order->user->email)->send(new PlaceOrderMail($order));

        return response()->json([
            'success' => true,
            'order_id' => $order->id 
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Order save failed: ' . $e->getMessage(),
        ]);
    }
}


public function scheduled($id)
{
    $order = Order::findOrFail($id);
    return view('order.schedule', compact('order'));
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

public function checkStatus($id)
{
    $order = Order::find($id);

    if (!$order) {
        return response()->json(['status' => 'not_found']);
    }

    return response()->json(['status' => $order->status]);
}


}
