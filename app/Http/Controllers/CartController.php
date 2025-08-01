<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class CartController extends Controller
{

    public function index(Request $request)
{
    if ($request->ajax()) {
        return view('cart.partials.cart-items')->render();
    }

    return view('cart.show');
}

public function add(Request $request)
{
    $itemId = $request->input('item_id');
    $cart = session()->get('cart', []);

    $item = MenuItem::find($itemId);
    if (!$item) {
        return redirect()->back()->with('error', 'Item not found.');
    }

    if (isset($cart[$itemId])) {
        $cart[$itemId]['quantity']++;
    } else {
        $cart[$itemId] = [
            'menu_item_id'  => $item->id, // ✅ required for saving orders
            'meal_name'     => $item->meal_name,
            'price'         => $item->price,
            'quantity'      => 1,
            'restaurant_id' => $item->restaurant_id, // ✅ helps save the order correctly
        ];
    }

    session()->put('cart', $cart);
    return redirect()->back()->with('success', 'Item added to cart!');
}



public function update(Request $request, $id)
{
    $cart = session()->get('cart', []);
    $action = $request->input('action');

    if (isset($cart[$id])) {
        if ($action === 'increase') {
            $cart[$id]['quantity']++;
        } elseif ($action === 'decrease') {
            $cart[$id]['quantity']--;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
        }
    }

    session()->put('cart', $cart);

    if ($request->ajax()) {
        return view('cart.partials.cart-items')->render();
    }

    return redirect()->back()->with('success', 'Cart updated.');
}

public function remove(Request $request, $id)
{
    $cart = session()->get('cart', []);
    unset($cart[$id]);
    session()->put('cart', $cart);

    if ($request->ajax()) {
        return view('cart.partials.cart-items')->render();
    }

    return redirect()->back()->with('success', 'Item removed from cart.');
}



    public function count()
    {
        $cart = session()->get('cart', []);
        $totalQty = array_sum(array_column($cart, 'quantity'));

        return response()->json(['count' => $totalQty]);
    }

    public function checkout()
    {
        return view('cart.checkout');
    }

    public function cartFragment()
    {
        return view('cart.partials.cart-items');
    }

    public function cancel(){
        session()->forget('cart');
        session()->forget('discount');
        return response()->json(['success' => true]);
    }

    public function restore(Request $request)
{
    $cart = $request->input('cart');

    if (is_array($cart)) {
        session(['cart' => $cart]);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Invalid cart data']);
}




}
