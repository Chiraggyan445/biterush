@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(to right, #fdfbfb, #ebedee);
        font-family: 'Segoe UI', sans-serif;
    }

    .cart-container {
        max-width: 960px;
        margin: auto;
        background: white;
        border-radius: 18px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
    }

    .cart-item {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.8rem;
        align-items: center;
        border-bottom: 1px dashed #ddd;
        padding-bottom: 1rem;
    }

    .cart-item img {
        width: 120px;
        height: 90px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .item-info {
        flex: 1;
    }

    .item-info h5 {
        font-weight: 600;
        color: #2d3436;
    }

    .item-info p {
        color: #636e72;
        margin: 0.4rem 0;
    }

    .quantity {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .quantity form button {
        background: #0984e3;
        color: white;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: bold;
        transition: all 0.2s ease;
    }

    .quantity .delete-btn {
        background: #d63031;
    }

    .summary-total {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .btn-checkout {
        background: #00b894;
        color: white;
        font-weight: bold;
        padding: 12px 26px;
        border-radius: 10px;
        border: none;
        float: right;
    }

    .empty-cart {
        text-align: center;
        color: #999;
        animation: fadeIn 0.8s;
    }

    @media (max-width: 768px) {
        .cart-item {
            flex-direction: column;
            text-align: center;
        }

        .quantity {
            justify-content: center;
        }
    }
</style>

<div class="container py-5">
    <div class="cart-container animate__animated animate__fadeIn">
        <h2 class="mb-4 text-center fw-bold"><i class="bi bi-cart4 text-primary"></i> Your Cart</h2>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success animate__fadeInDown">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger animate__fadeInDown">{{ session('error') }}</div>
        @endif

        @if (empty(session('cart')) || count(session('cart')) === 0)
            <div class="empty-cart mt-4">
                <i class="bi bi-emoji-frown fs-1"></i>
                <p class="mt-2">Oops! Your cart is empty.</p>
                <a href="{{ url('/all-meals') }}" class="btn btn-outline-primary">Browse Meals</a>
            </div>
        @else
            @php $total = 0; @endphp

            <div id="cart-items-section">
                @include('cart.partials.cart-items')
            </div>
        @endif
    </div>
</div>


<div id="cart-status-msg" style="display: none;position: fixed;bottom: 30px;left: 50%;transform: translateX(-50%);background: #00b894;color: white;padding: 12px 20px;border-radius: 8px;font-weight: bold;z-index: 9999;">
    Updating cart...
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Show a temporary status message
    function showStatusMessage(message = 'Updating cart...') {
        const $msg = $('#cart-status-msg');
        $msg.text(message).fadeIn(200);

        // Auto-hide after 1.5 seconds
        setTimeout(() => $msg.fadeOut(300), 1500);
    }

    // Refresh cart content via AJAX
    function refreshCart() {
        $.ajax({
            url: "{{ route('cart.show') }}",
            method: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function (data) {
                $('#cart-items-section').html(data);
            },
            error: function () {
                alert('Error refreshing cart.');
            }
        });
    }

    // Quantity increase
    $(document).on('click', '.btn-increase', function () {
        const id = $(this).data('id');
        showStatusMessage("Increasing item...");
        $.post(`/cart/update/${id}`, {
            _token: '{{ csrf_token() }}',
            action: 'increase'
        }).done(refreshCart);
    });

    // Quantity decrease
    $(document).on('click', '.btn-decrease', function () {
        const id = $(this).data('id');
        showStatusMessage("Decreasing item...");
        $.post(`/cart/update/${id}`, {
            _token: '{{ csrf_token() }}',
            action: 'decrease'
        }).done(refreshCart);
    });

    // Remove item
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        showStatusMessage("Removing item...");
        $.post(`/cart/remove/${id}`, {
            _token: '{{ csrf_token() }}'
        }).done(refreshCart);
    });
</script>
@endpush


