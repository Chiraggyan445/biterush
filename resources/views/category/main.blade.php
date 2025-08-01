@extends('layouts.app')

@section('content')
<!-- AOS animation and custom styling -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
<style>
    body {
        background: linear-gradient(to bottom right, #fff1eb, #ace0f9);
        font-family: 'Segoe UI', sans-serif;
    }

    .category-header {
        text-align: center;
        margin-bottom: 40px;
        color: #333;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .meal-card {
        background: #fff;
        border-radius: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        border: none;
    }

    .meal-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .meal-img {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .meal-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .meal-desc {
        font-size: 0.9rem;
        color: #777;
    }

    .container {
        padding-top: 60px;
        padding-bottom: 60px;
    }
</style>

<div class="container">
    <h2 class="category-header" data-aos="fade-down">Explore {{ ucwords(str_replace('-', ' ', $category)) }} Meals</h2>

    @if($meals->isEmpty())
        <div class="text-center text-muted">😕 No meals found in this category.</div>
    @endif

    <div class="row g-4 justify-content-center">
        @foreach($meals as $meal)
            <div class="col-md-3 col-sm-6" data-aos="fade-up">
                <div class="card meal-card h-100 text-center">
                    <a href="{{ route('meals.restaurants', ['slug' => $meal['slug']]) }}" class="text-decoration-none">
                        <img src="{{ $meal['image'] ?? '/images/placeholder.jpg' }}" class="meal-img w-100" alt="{{ $meal['name'] }}">
                        <div class="card-body">
                            <h5 class="meal-title">{{ $meal['name'] }}</h5>
                            <p class="meal-desc">{{ $meal['description'] ?? 'A delicious choice just for you!' }}</p>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
@endsection
