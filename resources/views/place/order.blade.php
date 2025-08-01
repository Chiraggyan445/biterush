@extends('layouts.app')

@section('title', 'Place Order')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
        font-family: 'Segoe UI', sans-serif;
    }

    .checkout-container {
        max-width: 900px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    }

    .checkout-title {
        font-weight: 700;
        font-size: 32px;
        margin-bottom: 25px;
        color: #2d3436;
    }

    .summary-box {
        background: #fdfdfd;
        border: 2px dashed #ced4da;
        border-radius: 15px;
        padding: 25px;
    }

    .total-row {
        font-weight: bold;
        font-size: 18px;
        border-top: 1px solid #dee2e6;
        padding-top: 10px;
        margin-top: 10px;
    }

    .btn-place {
        background: #00b894;
        border: none;
        color: white;
        padding: 14px 40px;
        font-size: 18px;
        font-weight: 600;
        border-radius: 12px;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
    }

    .btn-place:hover {
        background: #019170;
    }

    .payment-success {
        display: none;
        text-align: center;
        margin-bottom: 20px;
    }

    .payment-success.show {
        display: block;
        animation: bounceIn 1s;
    }

    .payment-success img {
        width: 80px;
        margin-bottom: 10px;
    }
</style>

<div class="container py-5 animate__animated animate__fadeIn">
    <div class="checkout-container">
        <h2 class="checkout-title text-center"><i class="bi bi-bag-check-fill text-success"></i> Place Your Order</h2>

        <div class="payment-success" id="payment-success">
            <img src="{{ asset('images/coin.gif') }}" alt="Success">
            <h5 class="text-success">Payment Successful!</h5>
        </div>

        <form id="order-form">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" id="order-name" required class="form-control">
            </div>

            <div class="mb-3">
                <label>Delivery Address</label>
                <input type="text" name="address" id="map-address" required class="form-control">
                <button type="button" onclick="redirectToMap()" class="btn btn-outline-primary mt-2">
                    <i class="bi bi-geo-alt-fill"></i> View on Google Maps
                </button>
            </div>

            <div class="summary-box mb-4">
                <h5>Your Order Summary</h5>
                @php $total = 0; $discount = session('discount', 0); @endphp
                @php
                    $deliveryFee = 40;
                    $finalTotal = $total - $discount + $deliveryFee;
                @endphp
                @foreach ($cart as $item)
                    @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item['meal_name'] }} x {{ $item['quantity'] }}</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                @endforeach
                <div class="d-flex justify-content-between mt-2">
                    <span>Delivery Fee</span>
                    <span>₹{{ number_format($deliveryFee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between total-row">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                @php $discount = session('discount', 0); @endphp
                @if ($discount > 0)
                    <div class="d-flex justify-content-between text-success mt-2">
                        <span>Coupon Discount</span>
                        <span>- ₹{{ number_format($discount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between total-row text-primary">
                        <span>Total</span>
                        <span>₹{{ number_format($total - $discount, 2) }}</span>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label>Coupon Code</label>
                <div class="input-group">
                    <input type="text" id="coupon-code" class="form-control" placeholder="e.g., CHIRAG100">
                    <button type="button" class="btn btn-dark" onclick="applyCoupon()">Apply</button>
                </div>
                <small id="coupon-feedback" class="text-success coupon-feedback"></small>
            </div>

            <div class="text-end">
                <button type="button" onclick="validateAndPay()" class="btn btn-place">
                    <i class="bi bi-currency-rupee"></i> Pay & Place Order
                </button>
            </div>
            <div class="text-end mt-3">
                <button type="button" onclick="cancelOrder()" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle"></i> Cancel Order
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    const total = {{ json_encode($total) }};
    const discount = {{ json_encode($discount ?? 0) }};
     const cartBackup = @json(session('cart'));

    function applyCoupon() {
        const code = $('#coupon-code').val();
        $.post("{{ route('coupon.apply') }}", {
            _token: '{{ csrf_token() }}',
            code: code
        }, function (res) {
            const fb = $('#coupon-feedback');
            if (res.success) {
                fb.text('₹' + res.discount + ' discount applied').removeClass('text-danger').addClass('text-success');
                setTimeout(() => location.reload(), 1000);
            } else {
                fb.text(res.message).removeClass('text-success').addClass('text-danger');
            }
        });
    }

    function redirectToMap() {
        const address = encodeURIComponent(document.getElementById('map-address').value);
        if (address.trim() !== '') {
            window.open(`https://www.google.com/maps/search/?api=1&query=${address}`, '_blank');
        }
    }

   function startPayment() {
    const name = document.getElementById('order-name').value;
    const address = document.getElementById('map-address').value;

    if (!name || !address) {
        Swal.fire("Missing Info", "Please enter name and address.", "warning");
        return;
    }

    const options = {
        key: "{{ config('services.razorpay.key') }}",
        amount: (total - discount + {{ $deliveryFee }}) * 100,
        currency: "INR",
        name: "BiteRush",
        description: "Order Payment",
        handler: function (response) {
            // Show success animation
            document.getElementById("payment-success").classList.add("show");

            // Submit order to backend
            $.ajax({
                url: "{{ route('order.submit') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    address: address,
                    razorpay_payment_id: response.razorpay_payment_id
                },
                success: function (res) {
                    if (res.success) {
                        setTimeout(() => {
                            window.location.href = "{{ route('order.track') }}";
                        }, 2000);
                    } else {
                        Swal.fire("Error", res.message || "Order failed to save.", "error");
                    }
                },
                error: function (err) {
                    console.error("Order Submit Error:", err);
                    Swal.fire("Server Error", "Please try again.", "error");
                }
            });
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}


    function validateAndPay() {
    const name = document.getElementById('order-name').value.trim();
    const address = document.getElementById('map-address').value.trim();

    if (name === '' || address === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Details',
            text: 'Please fill in your name and delivery address before placing the order.'
        });
        return;
    }

    startPayment();
}


</script>
@endpush
