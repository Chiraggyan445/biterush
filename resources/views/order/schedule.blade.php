@extends('layouts.app')

@section('title', 'Order Scheduled')

@section('content')
<div class="container text-center mt-5">
    <img src="{{ asset('images/schedule.png') }}" style="max-width: 200px;" class="mb-4" />
    <h2 class="text-success">🎉 Your order has been scheduled!</h2>
    <p class="lead mt-3">
        We will begin preparing your meal around <strong>{{ \Carbon\Carbon::parse($order->scheduled_time)->subMinutes(30)->format('h:i A') }}</strong>,
        so it's ready by <strong>{{ \Carbon\Carbon::parse($order->scheduled_time)->format('h:i A, d M Y') }}</strong>.
    </p>

    <div class="text-start mt-4 p-4 border rounded shadow-sm bg-light">
        <h5>🧾 Order Summary:</h5>
        <p><strong>Name:</strong> {{ $order->user->name }}</p>
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        <p><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>
    </div>

    <a href="{{ route('home') }}" class="btn btn-outline-primary mt-4">Back to Home</a>
</div>
@endsection
