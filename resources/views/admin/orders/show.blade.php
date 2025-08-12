<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Order #{{ $order->id }} Details</h2>
        @if($order->status !== 'ready')
            <form class="mark-ready-form" action="{{ route('admin.orders.markReady', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Mark as Ready
                </button>
            </form>
        @else
            <span class="badge bg-success fs-6">Order is Ready</span>
        @endif
    </div>
    <hr>

    <p><strong>User:</strong> {{ $order->user->name }}</p>
    <p><strong>Restaurant:</strong> {{ optional($order->restaurant)->name ?? 'N/A' }}</p>
    <p><strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>
    <p><strong>Status:</strong> 
        <span class="badge bg-{{ $order->status === 'ready' ? 'success' : 'warning' }}">
            {{ ucfirst($order->status) ?: 'Pending' }}
        </span>
    </p>
    <p><strong>Payment Status:</strong> 
        <span class="badge bg-primary">{{ ucfirst($order->payment_status) }}</span>
    </p>

    @if($order->address)
        <p><strong>Address:</strong> {{ $order->address->full_address }}</p>
    @endif

    <h4 class="mt-4">Order Items</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Meal</th>
                <th>Qty</th>
                <th>Price (₹)</th>
                <th>Total (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->menuItem->meal_name ?? 'Deleted item' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->price * $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')

@push('scripts')
<script>
document.getElementById('.mark-ready-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const orderId = this.dataset.orderId;

    fetch(`/admin/orders/${orderId}/mark-ready`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.redirect) {
            window.location.href = data.redirect;
        } else {
            alert('Failed to mark as ready.');
        }
    })
    .catch(() => alert('Something went wrong.'));
});
</script>
@endpush

@endpush
