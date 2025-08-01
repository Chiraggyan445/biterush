@php $total = 0; @endphp

@foreach (session('cart') as $id => $item)
    @php
        $slug = \Illuminate\Support\Str::slug($item['meal_name']);
        $imagePath = file_exists(public_path("images/dishes/$slug.jpeg"))
            ? asset("images/dishes/$slug.jpeg")
            : asset("images/meals/default.jpg");
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
    @endphp

    <div class="cart-item d-flex mb-3">
        <img src="{{ $imagePath }}" alt="{{ $item['meal_name'] }}" class="me-3" width="80">
        <div class="item-info">
            <h5 class="mb-1">{{ $item['meal_name'] }}</h5>
            <p class="mb-1">₹{{ number_format($item['price'], 2) }}</p>
            <div class="quantity d-flex align-items-center gap-2">
                <button class="btn btn-primary btn-sm btn-decrease" data-id="{{ $id }}">−</button>
                <span class="fw-bold">{{ $item['quantity'] }}</span>
                <button class="btn btn-primary btn-sm btn-increase" data-id="{{ $id }}">+</button>
                <button class="btn btn-danger btn-sm btn-delete ms-3" data-id="{{ $id }}"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    </div>
@endforeach

@if($total > 0)
    <div class="cart-summary mt-4 border-top pt-3">
        <h5>Total: ₹{{ number_format($total, 2) }}</h5>
        <form action="{{ route('place.order') }}" method="GET">
            @csrf
            <button type="submit"
                        class="btn btn-success btn-lg w-100 shadow-sm d-flex align-items-center justify-content-center gap-2"
                        style="border-radius: 30px; font-size: 1.1rem; transition: 0.3s;">
                    <i class="bi bi-bag-check-fill fs-5"></i>
                    Place Order
                </button>
        </form>
    </div>
@endif
