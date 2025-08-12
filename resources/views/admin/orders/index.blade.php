@extends('admin.layouts.admin') 

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
                    <button class="btn btn-sm btn-primary view-order-btn" 
        data-order-id="{{ $order->id }}">
    View
</button>

                </td>
            </tr>
        @empty
            <tr><td colspan="7">No orders found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="orderModalBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // View order button logic (already present)
    const modal = new bootstrap.Modal(document.getElementById('orderModal'));

    document.querySelectorAll('.view-order-btn').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.dataset.orderId;
            const modalBody = document.getElementById('orderModalBody');

            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading...</p>
                </div>
            `;

            modal.show();

            fetch(`/admin/orders/${orderId}?modal=1`)
                .then(res => res.text())
                .then(html => {
                    modalBody.innerHTML = html;

                    // Attach AJAX form listener AFTER content is loaded
                    const readyForm = modalBody.querySelector('.mark-ready-form');
                    if (readyForm) {
                        readyForm.addEventListener('submit', function (e) {
                            e.preventDefault();

                            fetch(this.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': this.querySelector('input[name=_token]').value,
                                    'Accept': 'application/json',
                                }
                            })
                            .then(res => res.ok ? res : Promise.reject(res))
                            .then(() => {
                                // Reload modal content after marking as ready
                                fetch(`/admin/orders/${orderId}?modal=1`)
                                    .then(res => res.text())
                                    .then(html => {
                                        modalBody.innerHTML = html;
                                    });
                            })
                            .catch(err => {
                                alert("Failed to mark order as ready.");
                                console.error(err);
                            });
                        });
                    }
                });
        });
    });
});
</script>
@endpush
