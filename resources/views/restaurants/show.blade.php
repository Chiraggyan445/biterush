@extends('layouts.app')

@section('title', $restaurant->name . ' | Menu')

@section('content')
<style>
    body {
        background-color: #f4f6f9;
    }

    .restaurant-header h1 {
        font-size: 2.8rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .restaurant-header p {
        font-size: 1rem;
        color: #7f8c8d;
    }

    .menu-heading {
        font-size: 1.6rem;
        font-weight: 600;
        color: #e17055;
        border-left: 5px solid #e17055;
        padding-left: 12px;
    }

    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background-color: #fff;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .card-body {
        padding: 1rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3436;
    }

    .card-text {
        font-size: 0.9rem;
        color: #636e72;
        min-height: 45px;
    }

    .price-badge {
        background-color: #00b894;
        color: white;
        padding: 6px 12px;
        border-radius: 25px;
        font-weight: bold;
        font-size: 0.95rem;
    }

    .order-btn {
        border-radius: 25px;
        padding: 6px 14px;
    }

    .no-results {
        background-color: #ffeaa7;
        color: #2d3436;
        border-left: 5px solid #fdcb6e;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5 restaurant-header">
        <h1>{{ $restaurant->name }}</h1>
        <p>{{ $restaurant->address }}</p>
    </div>

    <h2 class="mb-4 menu-heading">Menu Items for "{{ $mealName }}"</h2>

    @if($menuItems->isEmpty())
        <div class="no-results">
            😔 Sorry! No menu items found for this meal.
        </div>
    @else
        <div class="row g-4">
            @foreach($menuItems as $item)
                <div class="col-md-4">
                    <div class="card h-100">
                        @php
                            $slug = \Illuminate\Support\Str::slug($item->meal_name);
                            $imagePath = public_path('images/dishes/' . $slug . '.jpeg');
                        @endphp

                        @if (file_exists($imagePath))
                            <img src="{{ asset('images/dishes/' . $slug . '.jpeg') }}" class="card-img-top" alt="{{ $item->meal_name }}">
                        @else
                            <img src="{{ asset('images/meals/default.jpg') }}" class="card-img-top" alt="Default Dish Image">
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $item->meal_name }}</h5>
                            <p class="card-text">
                                {{ $item->description ?? 'Freshly made and delicious!' }}
                            </p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="price-badge">₹{{ number_format($item->price, 2) }}</span>

                                    <form action="{{ route('cart.add') }}" method="POST" class="d-inline order-form">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-outline-primary btn-sm order-btn">
                                        <i class="bi bi-cart"></i> <span class="btn-text">Order Now</span>
                                        </button>
                                    </form>

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
