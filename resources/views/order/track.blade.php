@extends('layouts.app')

@section('title', 'Track Delivery')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        background: linear-gradient(to right, #dfe9f3, #ffffff);
        font-family: 'Segoe UI', sans-serif;
    }

    .tracking-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 25px;
        margin-bottom: 25px;
        animation: fadeInDown 0.8s;
    }

    .tracking-card h5 {
        font-weight: 700;
        color: #2d3436;
    }

    .tracking-card p {
        font-size: 16px;
    }

    .map-container {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 1s;
    }

    #map {
        height: 500px;
        width: 100%;
    }

    .status-bar {
        background: #00cec9;
        height: 8px;
        width: 0;
        border-radius: 5px;
        margin-top: 10px;
        transition: width 1.2s ease-in-out;
    }

    .otp-badge {
        display: inline-block;
        background: #2ecc71;
        color: white;
        padding: 6px 12px;
        border-radius: 12px;
        font-weight: bold;
        font-size: 14px;
    }

    .arrived-text {
        font-size: 18px;
        font-weight: bold;
        color: #27ae60;
        display: none;
    }
    .logout-reminder {
    position: fixed;
    bottom: 80px; /* Adjust based on your navbar height */
    left: 20px;
    background: #ffeaa7;
    color: #2d3436;
    padding: 12px 18px;
    border-radius: 8px;
    font-weight: 600;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    z-index: 9999;
    display: none;
}

.logout-reminder::before {
    content: "⬇️";
    position: absolute;
    top: -24px;
    left: 10px;
    font-size: 20px;
}

</style>

<div class="container py-5">
    <h2 class="text-center mb-4 animate__animated animate__fadeInDown">🛵 Live Order Tracking</h2>

    <div class="tracking-card text-center">
        <h5>{{ $agent['name'] }}</h5>
        <p><i class="bi bi-clock"></i> ETA: <strong>{{ $agent['eta'] }}</strong></p>
        <p class="text-success">
            OTP for order confirmation: <span class="otp-badge">{{ $agent['otp'] }}</span>
        </p>
        <div class="status-bar" id="progress-bar"></div>
        <p id="arrived-text" class="arrived-text mt-3">🎉 Your delivery has arrived!</p>
    </div>

    <div class="text-center mt-4 mb-2" id="cancel-section">
    <button class="btn btn-outline-danger btn-lg" onclick="cancelOrderTrack()">
        <i class="bi bi-x-circle"></i> Cancel Order
    </button>
</div>

<div class="text-center mt-3" id="refund-section" style="display: none;">
    <button class="btn btn-danger btn-lg" onclick="requestRefund()">
        <i class="bi bi-cash-stack"></i> Request Refund
    </button>
</div>

    <div id="logout-reminder" class="logout-reminder animate__animated animate__fadeInUp">
     You can now log out after order delivery!
    </div>


    <div class="map-container">
        <div id="map"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBpAQImIGyABHwWjCX8y8I0FkkkrPl0G0c"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const agent = @json($agent);

    function initMap() {
        const from = { lat: agent.from[0], lng: agent.from[1] };
        const to = { lat: agent.to[0], lng: agent.to[1] };

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14,
            center: from,
            mapId: "YOUR_MAP_ID_IF_ANY"
        });

        const marker = new google.maps.Marker({
            position: from,
            map: map,
            icon: {
                url: "{{ asset('images/scooter-icon.png') }}",
                scaledSize: new google.maps.Size(40, 40)
            },
            title: agent.name
        });

        const step = {
            lat: (to.lat - from.lat) / 100,
            lng: (to.lng - from.lng) / 100
        };

        let i = 0;
        const progressBar = document.getElementById('progress-bar');
        const arrivedText = document.getElementById('arrived-text');

        const interval = setInterval(() => {
            if (i >= 100) {
                clearInterval(interval);
                progressBar.style.width = "100%";
                arrivedText.style.display = "block";

                document.getElementById('logout-reminder').style.display = 'block';

                const audio = new Audio("{{ asset('sounds/delivery.mp3') }}");
                audio.play();

                 setTimeout(() => {
                    audio.pause();
                    audio.currentTime = 0;
                }, 60000);

                return;
            }

            const newPos = {
                lat: from.lat + step.lat * i,
                lng: from.lng + step.lng * i
            };

            marker.setPosition(newPos);
            progressBar.style.width = i + "%";
            i++;
        }, 100);
    }

    window.onload = initMap;
</script>

<script>
    function cancelOrderTrack() {
    Swal.fire({
        title: 'Cancel Order?',
        text: "Are you sure you want to cancel this order?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("{{ route('cart.cancel') }}", {
                _token: '{{ csrf_token() }}'
            }, function (res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Cancelled',
                        text: 'You may now request a refund.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Show refund button
                    document.getElementById('refund-section').style.display = 'block';
                    document.getElementById('cancel-section').style.display = 'none';
                }
            });
        }
    });
}

function requestRefund() {
    Swal.fire({
        title: 'Refund Request?',
        text: "Do you want to request a refund for this order?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, refund it',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("{{ route('order.refund') }}", {
                _token: '{{ csrf_token() }}'
            }, function (res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Refund Initiated',
                        text: res.message || 'Your refund is being processed.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Refund Failed',
                        text: res.message || 'An error occurred.'
                    });
                }
            });
        }
    });
}

</script>
@endpush
