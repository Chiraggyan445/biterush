@extends('layouts.app')

@section('title', 'Place Order')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .checkout-container {
        max-width: 900px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
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

    .animate-bounce {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    #wheel-pointer {
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        width: 0;
        height: 0;
        border-left: 25px solid transparent;
        border-right: 25px solid transparent;
        border-bottom: 40px solid #e74c3c;
    }
</style>

<div class="container py-5 animate__animated animate__fadeIn">
    <div class="checkout-container">
        <h2 class="text-center mb-4">
            <i class="bi bi-bag-check-fill text-success"></i> Place Your Order
        </h2>

        <!-- Payment Success Message -->
        <div class="payment-success" id="payment-success">
            <img src="{{ asset('images/coin.gif') }}" alt="Success">
            <h5 class="text-success">Payment Successful!</h5>
        </div>


        <!-- Order Form -->
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

            <div class="mb-4">
                <label>📦 Schedule Order (Optional)</label>
                <input type="datetime-local" name="scheduled_time" id="scheduled_time" class="form-control shadow-sm rounded-lg border border-secondary" />
                <small class="text-muted">Leave blank to prepare immediately.</small>
            </div>

            <!-- Order Summary -->
            @php
                $total = 0;
                $discount = session('discount', 0);
                $deliveryFee = 40;
            @endphp

            <div class="summary-box mb-4">
                <h5 class="mb-3">Your Order Summary</h5>
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

                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($total, 2) }}</span>
                </div>

                @if ($discount > 0)
                    <div class="d-flex justify-content-between text-success mt-2">
                        <span>Coupon Discount</span>
                        <span>- ₹{{ number_format($discount, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between text-primary fw-bold">
                        <span>Total</span>
                        <span>₹{{ number_format($total - $discount + $deliveryFee, 2) }}</span>
                    </div>
                @else
                    <hr>
                    <div class="d-flex justify-content-between text-primary fw-bold">
                        <span>Grand Total</span>
                        <span>₹{{ number_format($total + $deliveryFee, 2) }}</span>
                    </div>
                @endif
            </div>

            <!-- Coupon + Spin -->
            <div class="mb-4">
                <label>Coupon Code</label>
                <div class="input-group">
                    <input type="text" id="coupon-code" class="form-control" placeholder="e.g., BITE20">
                    <button type="button" class="btn btn-dark" onclick="applyCoupon()">Apply</button>
                    <button type="button" onclick="showSpinWheel()" class="ms-2 btn btn-warning animate__pulse" title="Spin the Wheel" data-bs-toggle="modal" data-bs-target="#spinWheelModal">🎡</button>
                </div>
                <small id="coupon-feedback" class="text-success coupon-feedback"></small>
                <div class="text-muted mt-1 animate__animated animate__pulse animate__infinite">✨ Spin the wheel for an offer!</div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-4 text-end">
                <button type="button" onclick="validateAndPay()" class="btn btn-place">
                    <i class="bi bi-currency-rupee"></i> Pay & Place Order
                </button>
            </div>
            <div class="text-end">
                <button type="button" onclick="cancelOrder()" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle"></i> Cancel Order
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Spin Wheel Modal -->
<div class="modal fade" id="spinWheelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-xl shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">🎯 Spin to Win a Discount!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div class="position-relative mx-auto" style="width: 300px; height: 300px;">
            <canvas id="wheel-canvas" width="300" height="300" class="rounded-circle border border-secondary"></canvas>
            <button id="spin-btn" class="btn btn-danger position-absolute top-50 start-50 translate-middle fw-semibold shadow">Spin Now!</button>
        </div>
      </div>
    </div>
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
const deliveryFee = 40;

function applyCoupon() {
    const code = $('#coupon-code').val().trim();
    if (!code) return $('#coupon-feedback').text('Please enter a coupon code.').addClass('text-danger').removeClass('text-success');

    $.post("{{ route('coupon.apply') }}", {
        _token: '{{ csrf_token() }}', code
    }).done(res => {
        const fb = $('#coupon-feedback');
        if (res.success) {
            fb.text(`✅ ₹${res.discount} discount applied`).removeClass('text-danger').addClass('text-success');
            setTimeout(() => location.reload(), 1000);
        } else {
            fb.text(res.message).removeClass('text-success').addClass('text-danger');
        }
    }).fail(() => $('#coupon-feedback').text("Something went wrong.").addClass('text-danger'));
}

function redirectToMap() {
    const address = encodeURIComponent($('#map-address').val());
    if (address) window.open(`https://www.google.com/maps/search/?api=1&query=${address}`, '_blank');
}

function validateAndPay() {
    const name = $('#order-name').val().trim(), address = $('#map-address').val().trim();
    if (!name || !address) {
        return Swal.fire("Incomplete", "Please enter your name and address.", "warning");
    }
    startPayment();
}

function startPayment() {
    const name = $('#order-name').val(),
          address = $('#map-address').val(),
          scheduled_time = $('#scheduled_time').val() || null;

    new Razorpay({
        key: "{{ config('services.razorpay.key') }}",
        amount: (total - discount + deliveryFee) * 100,
        currency: "INR",
        name: "BiteRush",
        description: "Order Payment",
        handler: function (response) {
            $('#payment-success').addClass('show');

            // Send order data to backend
            $.post("{{ route('order.submit') }}", {
                _token: '{{ csrf_token() }}',
                name, address, scheduled_time,
                razorpay_payment_id: response.razorpay_payment_id
            }).done(res => {
                if (res.success && res.order_id) {
                    // ✅ Show SweetAlert popup
                  Swal.fire({
    title: '🍽️ Preparing Your Order...',
    html: `
        <div class="mb-3">
            <img src="{{ asset('images/food-delivery-unscreen.gif') }}" alt="Preparing" style="width: 150px;">
        </div>
        <p class="text-muted">Hang tight! We’ll redirect you once it’s ready.</p>
        <small class="text-secondary">Checking every few seconds...</small>
    `,
    allowOutsideClick: false,
    showConfirmButton: false,
    backdrop: true,
    didOpen: () => {
        const pollStatus = () => {
            fetch(`/order/check-status/${res.order_id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'ready') {
                        Swal.close();
                        window.location.href = `/order/track/${res.order_id}`;
                    } else {
                        setTimeout(pollStatus, 5000);
                    }
                })
                .catch(() => setTimeout(pollStatus, 7000));
        };

        setTimeout(pollStatus, 3000);
    }
});


                } else {
                    Swal.fire("Error", res.message || "Order failed to save.", "error");
                }
            }).fail(() => {
                Swal.fire("Server Error", "Please try again.", "error");
            });
        }
    }).open();
}


function cancelOrder() {
    Swal.fire({
        title: "Cancel Order?",
        text: "Your cart will be cleared.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
    }).then(result => {
        if (result.isConfirmed) window.location.href = "{{ route('all-meals') }}";
    });
}

const offers = [
    { label: "10% OFF", code: "CHIRAG10", color: "#f44336" },
    { label: "20% OFF", code: "BITE20", color: "#e91e63" },
    { label: "Sorry!", code: null, color: "#9c27b0" },
    { label: "30% OFF", code: "SAVE30", color: "#3f51b5" },
    { label: "Sorry!", code: null, color: "#009688" },
    { label: "50% OFF", code: "MEGA50", color: "#4caf50" },
];

const canvas = document.getElementById('wheel-canvas');
const ctx = canvas.getContext('2d');
const numSegments = offers.length;
const anglePerSegment = (2 * Math.PI) / numSegments;

function drawWheel() {
    for (let i = 0; i < numSegments; i++) {
        const start = i * anglePerSegment, end = start + anglePerSegment;
        ctx.beginPath();
        ctx.moveTo(150, 150);
        ctx.arc(150, 150, 150, start, end);
        ctx.fillStyle = offers[i].color;
        ctx.fill();
        ctx.save();
        ctx.translate(150, 150);
        ctx.rotate(start + anglePerSegment / 2);
        ctx.fillStyle = "white";
        ctx.font = "bold 14px sans-serif";
        ctx.fillText(offers[i].label, 60, 5);
        ctx.restore();
    }
}

document.getElementById("spin-btn").addEventListener("click", function () {
    if (canvas.classList.contains('spinning')) return;
    canvas.classList.add('spinning');

    const spins = Math.floor(Math.random() * 5) + 5;
    const finalAngle = Math.random() * 2 * Math.PI;
    const totalRotation = spins * 2 * Math.PI + finalAngle;

    canvas.style.transition = "transform 4s ease-out";
    canvas.style.transform = `rotate(${totalRotation}rad)`;

    setTimeout(() => {
        const rotation = totalRotation % (2 * Math.PI);
        const index = Math.floor((2 * Math.PI - rotation) / anglePerSegment) % numSegments;
        const result = offers[index];

        if (result.code) {
            Swal.fire("🎉 Congrats!", `You won <strong>${result.label}</strong><br>Coupon applied!`, "success");
            $('#coupon-code').val(result.code);
            applyCoupon();
        } else {
            Swal.fire("😢 Try Again", "No discount this time.", "info");
        }

        canvas.classList.remove('spinning');
        bootstrap.Modal.getInstance(document.getElementById('spinWheelModal')).hide();
    }, 4200);
});

document.addEventListener("DOMContentLoaded", drawWheel);
</script>
@endpush
