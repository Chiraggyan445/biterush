@extends('admin.layouts.admin') {{-- Update this if your layout path differs --}}

@section('title', 'All Orders')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">All Orders</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Placed At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ ucfirst($order->payment_status) }}</td>
                <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                        View
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No orders found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
