@php
    $userName = optional($order->user)->name ?? 'Valued Customer';
@endphp

@component('mail::message')
# 🍽️ Thanks for your order, {{ $userName }}!

Your order **#{{ $order->id ?? 'N/A' }}** has been placed successfully.

@component('mail::panel')
**Items Ordered:**  
@forelse ($order->items as $item)
- {{ optional($item->meal)->name ?? 'Unknown Meal' }} x{{ $item->quantity ?? 1 }}
@empty
No items found in your order.
@endforelse

**Total:** ₹{{ $order->total ?? '0.00' }}
@endcomponent

We'll notify you when it's out for delivery!

@component('mail::button', ['url' => route('order.track', ['id' => $order->id ?? 0])])
Track Your Order
@endcomponent

Thanks,  
Team BiteRush
@endcomponent
