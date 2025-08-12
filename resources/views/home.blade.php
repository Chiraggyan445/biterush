<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

</head>
<style>
    body{
        margin: 0;
        font-family: 'Segeo UI', sans-serif;
        background-color: #fff;
        color: black;
    }

    header{
        text-align: center;
        padding: 2rem;
        background:radial-gradient(circle, #ff914d, #ff4e50);
        color: white;
        height: 32rem;
        clip-path: polygon(
    0 0, 
    100% 0, 
    100% 85%, 
    95% 87%, 
    90% 85%, 
    85% 87%, 
    80% 85%, 
    75% 87%, 
    70% 85%, 
    65% 87%, 
    60% 85%, 
    55% 87%, 
    50% 85%, 
    45% 87%, 
    40% 85%, 
    35% 87%, 
    30% 85%, 
    25% 87%, 
    20% 85%, 
    15% 87%, 
    10% 85%, 
    5% 87%, 
    0 85%
  );
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    position: relative;
    z-index: 1; 
    }

    header img{
        width: 15%;
        height: auto;
    }

    header h1{
        margin-top: -1rem;
        font-size: 1.5rem;
        font-weight: bold;
        font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        color: lightgray;
    }

    .user-controls{
      position: absolute;
      top: 10px;
      right: 100px;
    }
    
    .cart{
        margin-left: 95%;
        margin-top: -2%;
        color: black;
        font-size: 2rem;
    }

    .svg-images{
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left: 60px;
    }

    .svg-images img{
        width: 130px;
        margin-right: 70px;
         animation: waveMotion 2s ease-in-out infinite;
    }

    .svg-images img:nth-child(1) {
    animation-delay: 0s;
}
.svg-images img:nth-child(2) {
    animation-delay: 0.3s;
}
.svg-images img:nth-child(3) {
    animation-delay: 0.6s;
}
.svg-images img:nth-child(4) {
    animation-delay: 0.9s;
}

@keyframes waveMotion {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}

.highlights-bar {
    background: linear-gradient(90deg, #1f1f1f, #292929); 
    color: #fff;
    overflow: hidden;
    white-space: nowrap;
    padding: 0.8rem 0;
    font-size: 0.95rem;
    font-weight: 500;
    border-top: 2px solid #FF6B00;
    border-bottom: 2px solid #FF6B00;
    position: relative;
    z-index: 10;
    margin-top: 4%;
    margin-bottom: 2%;
}

.highlight-track {
    display: inline-block;
    padding-left: 100%;
    animation: scrollHighlights 25s linear infinite;
}

.highlight-item {
    display: inline-block;
    margin-right: 4rem;
    color: #f0f0f0;
    font-family: 'Segoe UI', sans-serif;
    letter-spacing: 0.2px;
}

.highlight-item i {
    margin-right: 0.4rem;
    color: #FF6B00; 
    font-size: 1.1rem;
    vertical-align: middle;
    margin-top: -5px;
}

@keyframes scrollHighlights {
    from {
        transform: translateX(0%);
    }
    to {
        transform: translateX(-100%);
    }
}


.highlights-bar:hover .highlight-track {
    animation-play-state: paused;
}

#searchResults {
    position: absolute;
    z-index: 10;
    max-height: 500px;
    overflow-y: auto;
    background-color: #ffffff;
}

#searchResults li:hover {
    background-color: #f8f9fa;
}

#page-loader {
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 99999;
    transition: opacity 0.5s ease;
}

#page-loader.hidden {
    opacity: 0;
    visibility: hidden;
}
.food-type-card {
  background: linear-gradient(160deg, #fff3e0 0%, #ffe082 100%);    
  padding: 20px;
  border-radius: 24px;
  transition: all 0.3s ease;
  box-shadow:  10px 12px 24px rgba(0, 0, 0, 0.08);
  text-align: center;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(0, 0, 0, 0.03);
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.food-type-card::before {
  content: '';
  position: absolute;
  top: -40%;
  left: -40%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
  transform: rotate(25deg);
  z-index: 0;
}

.food-type-card img {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 14px;
  box-shadow: 0 8px 16px rgba(0,0,0,0.12);
  transition: transform 0.3s ease-in-out;
  position: relative;
  z-index: 1;
}

.food-type-card h6 {
  margin: 0;
  font-size: 1.05rem;
  font-weight: 600;
  color: #333;
  z-index: 1;
  position: relative;
}

.food-type-card:hover {
  background: linear-gradient(160deg, #fff3e0 0%, #ffe082 100%);
  transform: translateY(-8px);
  box-shadow: 0 16px 30px rgba(0, 0, 0, 0.15);
  cursor: pointer;
}

.food-type-card:hover img {
  transform: scale(1.1);
}

#searchInput::placeholder {
  font-style: italic;
  color: #666;
  transition: all 0.3s ease-in-out;
}

.explore{
    font-family: 'Segoe UI', sans-serif;
}

/* ======= Carousel Section: Parallax Trending ======= */
.parallax-trending {
  position: relative;
  background: url('/images/dishes/food.jpeg') repeat;
  background-size: cover;
  background-attachment: fixed;
  background-position: center;
  overflow: hidden;
  color: white;
  padding: 60px 0;
}

.parallax-trending::before {
  content: "";
  position: absolute;
  inset: 0;
  background: url('/images/bg-food-overlay.jpg'); /* Optional: background image */
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  opacity: 0.3;
  filter: blur(2px) brightness(0.7);
  z-index: 0;
}

.parallax-trending .container {
  position: relative;
  z-index: 2;
}

.parallax-trending h2 {
  font-size: 2.2rem;
  font-weight: 700;
  color: #fff;
}

.parallax-trending p {
  font-size: 1rem;
  color: rgba(255, 255, 255, 0.85);
}


/* ======= Card Styling Inside Carousel ======= */
.trending-card {
  border: none;
  border-radius: 20px;
  overflow: hidden;
  background-color: #fff;
  color: #333;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.trending-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 30px rgba(0, 0, 0, 0.15);
}

.trending-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-bottom: 1px solid #eee;
}

.trending-card .card-body {
  padding: 16px 20px;
}

.trending-card h5 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 8px;
}

.trending-card p {
  font-size: 0.95rem;
  color: #666;
  margin-bottom: 0;
}


/* ======= Carousel Controls ======= */
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: rgba(0, 0, 0, 0.3);
  border-radius: 50%;
  padding: 15px;
  transition: background-color 0.3s ease;
}

.carousel-control-prev:hover .carousel-control-prev-icon,
.carousel-control-next:hover .carousel-control-next-icon {
  background-color: rgba(0, 0, 0, 0.6);
}


/* ======= Responsive Tweaks ======= */
@media (max-width: 768px) {
  .trending-card img {
    height: 160px;
  }
  
  .parallax-trending h2 {
    font-size: 1.75rem;
  }
}

/* Container background and spacing */
.testimonial-section {
  background-color: #fefefe;
  padding: 80px 0;
}

/* Headings */
.testimonial-section h2 {
  font-size: 2.4rem;
  font-weight: 700;
  margin-bottom: 3rem;
}

/* Testimonial card styling */
.testimonial-card {
  border: none;
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  min-height: 240px;
}

.testimonial-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

/* Testimonial text */
.testimonial-card p {
  font-size: 1.05rem;
  line-height: 1.7;
  color: #555;
}

/* Profile image */
.testimonial-card img {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Profile name */
.testimonial-card h6 {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
}

/* Profile location */
.testimonial-card small {
  color: #888;
  font-size: 0.9rem;
}

.popup-suggestions {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 300px;
    max-height: 400px;
    background-color: #fffefc;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 20px;
    z-index: 9999;
    animation: slideInUp 0.4s ease;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.popup-suggestions .popup-header {
    font-weight: bold;
    font-size: 1.1rem;
    color: #333;
}

.popup-suggestions .popup-body a {
    color: #ff4e50;
}

.popup-suggestions .popup-body a:hover {
    text-decoration: underline;
}

@keyframes slideInUp {
    from {
        transform: translateY(100px);
        opacity: 0;
    }
    to {
        transform: translateY(0px);
        opacity: 1;
    }
}


</style>
<body>

@extends('layouts.app')

@section('content')
<div id="page-loader">
    <div class="loader-inner">
        <img src="{{ asset('images/food-delivery-unscreen.gif') }}" alt="Loading..." />
        <p style="color: #ff4e50; font-weight: 500; margin-left: 50px;">Loading BiteRush...</p>
    </div>
</div>

    <header>

        <div class="cart">
            <i class="bi bi-cart4" style="cursor:pointer;"></i>
        </div>

        <img src="{{ asset('images/biterush.png') }}">
        <h1>Bringing flavors to your doorstep!</h1>


<div class="user-controls">
    @if(Auth::check() && Auth::user()->role === 'customer')
        @if(session('google_avatar'))
            <img src="{{ session('google_avatar') }}" alt="Profile" class="profile-img">
        @else
            <div class="profile-initial">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        @endif

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @else
        <button id="login-button" type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#loginModal">
            Get In
        </button>
    @endif
</div>





    <div class="search-container position-relative mx-auto mt-4" style="max-width: 600px; z-index: 100; position: relative;">
        <div class="input-group shadow-sm">
        <input type="text" id="searchInput" class="form-control border-end-0" placeholder="Search for meals or dishes..."  autocomplete="off" autocorrect="off" spellcheck="false" name="search_{{ uniqid() }}">
        <span class="input-group-text bg-white">
            <i class="bi bi-search"></i> 
        </span>
    </div>

    <ul id="searchResults" class="list-group position-absolute w-100 mt-1 shadow-sm" style="z-index: 1050;"></ul>
</div>

<div id="fadeOverlay" style="position:fixed;
            top:0; left:0; width:100%; height:100%;
            background:#fff;
            z-index:9999;
            opacity:0;
            pointer-events: none;
            transition: opacity 0.4s;">
            <img src="{{ asset('images/food-delivery-unscreen.gif') }}"
         alt="Loading..."
         width="120"
         style="animation: bounce 1.2s infinite ease-in-out;">
    
    <p style="margin-top: 1rem; color: #ff4e50; font-weight: 500;">
        Preparing your delicious search...
    </p>    
        </div>

  


</header>

    <style>
        #mic-icon {
    width: 50px;
    height: 50px;
    background: #dc3545;
    color: white;
    font-size: 24px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: grab;
    position: fixed;
    top: 20px;
    left: 30px; /* 🟢 changed from right: 30px to left: 30px */
    z-index: 99999;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transition: transform 0.2s ease;
}

#drop-zone {
    width: 140px;
    height: 140px;
    background: rgba(255, 0, 0, 0.05);
    border: 2px dashed #f44336;
    border-radius: 10px;
    display: none;
    justify-content: center;
    align-items: center;
    position: fixed;
    bottom: 100px;
    right: 100px;
    z-index: 99998;
    font-size: 16px;
    color: #222;
}

#assistant-ui {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

#assistant-response-box {
    background: white;
    color: #333;
    padding: 12px 16px;
    border-radius: 12px;
    max-width: 250px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    display: none;
    border: 1px solid #eee;
    font-size: 0.9rem;
}

#assistant-mic {
    background: #ef4444;
    color: white;
    padding: 16px;
    border-radius: 50%;
    box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    border: none;
}

#assistant-mic:hover {
    transform: scale(1.1);
    background: #dc2626;
}


    </style>
</head>
<body>





<script>
window.addEventListener('load', function () {
    const loader = document.getElementById('page-loader');
    if (loader) {
        loader.classList.add('hidden');
    }
});
</script>

    <section>
        <div class="svg-images" data-aos="fade-up" data-aos-duration="1000">
            <img src="{{ asset('images/burger.png') }}">
            <img src="{{ asset('images/pizza.png') }}">
            <img src="{{ asset('images/noodles.png') }}">
            <img src="{{ asset('images/cake.png') }}">
        </div>
    </section>
@php
    use Carbon\Carbon;

    $highlightSets = [ 
        [
            ['icon' => 'bi-fire', 'text' => 'Spicy Paneer Pizza trending from Pizza Hub - 20% off!'],
            ['icon' => 'bi-award-fill', 'text' => 'Rajdhani Thali is today\'s most loved meal!'],
            ['icon' => 'bi-truck', 'text' => 'Curry Express delivered 153 orders today!'],
            ['icon' => 'bi-gift-fill', 'text' => 'Order above ₹299 and get free dessert!'],
        ],
        [
            ['icon' => 'bi-cup-hot-fill', 'text' => 'Try our new filter coffee from Brew Town!'],
            ['icon' => 'bi-clock-history', 'text' => 'Fastest delivery today: 18 mins!'],
            ['icon' => 'bi-egg-fried', 'text' => 'Egg Rice Bowl is trending in your area!'],
            ['icon' => 'bi-lightning-fill', 'text' => 'Flash deal: 30% off for first 100 orders today!'],
        ],
        [
            ['icon' => 'bi-shop-window', 'text' => 'Biryani Bazaar just opened near you - check it out!'],
            ['icon' => 'bi-stars', 'text' => 'User favorite: Paneer Butter Masala from Delhi Kitchen!'],
            ['icon' => 'bi-bag-heart', 'text' => 'Get ₹100 cashback on your 3rd order this week!'],
            ['icon' => 'bi-emoji-smile', 'text' => 'Over 50K happy foodies love BiteRush!'],
        ]
    ];

   $count = count($highlightSets);

    $daysDiff = Carbon::now()->diffInDays(Carbon::create(2025, 1, 1), false); // false = allow negative

    $index = $count > 0 ? abs(floor($daysDiff / 2) % $count) : 0;

    $highlights = $highlightSets[$index] ?? [];
@endphp


<section class="highlights-bar">
    <div class="highlight-track">
        @foreach($highlights as $item)
            <div class="highlight-item">
                <i class="bi {{ $item['icon'] }}"></i> {{ $item['text'] }}
            </div>
        @endforeach
    </div>
</section>

@include('modals.login')
@include('modals.register')

<!-- 🍱 Explore by Food Type -->

<style>
  .view-all-btn {
    background: linear-gradient(to right, #ff9800, #ff5722);
    color: white;
    border: none;
    border-radius: 50px;
    box-shadow: 0 4px 12px rgba(255, 87, 34, 0.3);
    transition: all 0.3s ease;
  }

  .view-all-btn:hover {
    background: linear-gradient(to right, #ff7043, #ff8a65);
    box-shadow: 0 6px 20px rgba(255, 87, 34, 0.5);
    transform: translateY(-2px);
    color: #fff;
    text-decoration: none;
  }
</style>

<div class="container py-5">
  <div class="row g-4 justify-content-center">

    @php
      $categories = [
        ['name' => 'Main Course', 'image' => '/images/main-course.jpg'],
        ['name' => 'Snacks', 'image' => '/images/snacks.jpg'],
        ['name' => 'Desserts', 'image' => '/images/desserts.jpg'],
        ['name' => 'Drinks & Beverages', 'image' => '/images/beverages.jpg'],
        ['name' => 'Biryani & Rice', 'image' => '/images/biryani.jpg'],
        ['name' => 'Fresh Juices', 'image' => '/images/juice.jpg'],
        ['name' => 'Milkshakes', 'image' => '/images/milkshake.jpg'],
        // Add more as needed
      ];
    @endphp

    @foreach ($categories as $category)
      <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="100">
        <a href="{{ route('category.main', ['slug' => Str::slug($category['name'])]) }}" class="text-decoration-none">
          <div class="food-type-card text-center p-3 rounded-4 shadow bg-white hover-shadow">
            <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="img-fluid rounded mb-2" />
            <h6 class="fw-bold text-dark">{{ $category['name'] }}</h6>
          </div>
        </a>
      </div>
    @endforeach

  </div>
</div>


<section class="parallax-trending py-5 text-center text-white" data-aos="fade-up">
  <div class="container">
    <h2 class="mb-3" style="color: white; font-weight: 1000;">Discover What's Trending</h2>
    <p class="mb-3" style="color: gray;">Explore the meals people can't stop ordering</p>

    <div id="trendingCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="row justify-content-center g-4">
            <div class="col-md-4">
              <div class="card trending-card">
                <img src="/images/dishes/biryani.jpeg" class="card-img-top" alt="Biryani">
                <div class="card-body">
                  <h5 class="card-title">Hyderabadi Biryani</h5>
                  <p class="card-text text-muted">Spicy, fragrant, and full of flavor.</p>
                </div>
              </div>
            </div>
            <div class="col-md-4 d-none d-md-block">
              <div class="card trending-card">
                <img src="/images/dishes/cheese-pizza.jpeg" class="card-img-top" alt="Pizza">
                <div class="card-body">
                  <h5 class="card-title">Cheesy Paneer Pizza</h5>
                  <p class="card-text text-muted">Loaded with cheese and desi toppings.</p>
                </div>
              </div>
            </div>
            <div class="col-md-4 d-none d-lg-block">
              <div class="card trending-card">
                <img src="/images/dishes/cake.jpg" class="card-img-top" alt="Cake">
                <div class="card-body">
                  <h5 class="card-title">Choco Lava Cake</h5>
                  <p class="card-text text-muted">A rich dessert that melts in your mouth.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
          <div class="row justify-content-center g-4">
            <div class="col-md-4">
              <div class="card trending-card">
                <img src="/images/dishes/indian-thali.jpeg" class="card-img-top" alt="Thali">
                <div class="card-body">
                  <h5 class="card-title">Royal Indian Thali</h5>
                  <p class="card-text text-muted">A full meal served with love.</p>
                </div>
              </div>
            </div>
            <div class="col-md-4 d-none d-md-block">
              <div class="card trending-card">
                <img src="/images/dishes/noodles.jpg" class="card-img-top" alt="Noodles">
                <div class="card-body">
                  <h5 class="card-title">Schezwan Noodles</h5>
                  <p class="card-text text-muted">Fiery and satisfying Chinese delight.</p>
                </div>
              </div>
            </div>
            <div class="col-md-4 d-none d-lg-block">
              <div class="card trending-card">
                <img src="/images/dishes/juice.jpg" class="card-img-top" alt="Juice">
                <div class="card-body">
                  <h5 class="card-title">Fresh Fruit Juice</h5>
                  <p class="card-text text-muted">Cool, refreshing, and healthy.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#trendingCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" style="margin-right: 250px;" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#trendingCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" style="margin-left: 250px;" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</section>

  <!-- Testimonials -->
<section class="bg-light py-5">
  <div class="container">
    <h2 class="text-center mb-5" style="font-weight: 1000;">What Our Foodies Say</h2>
    <div class="row g-4 justify-content-center">

      <!-- Card 1 -->
      <div class="col-md-4" data-aos="flip-left" data-aos-delay="150">
        <div class="card border-0 shadow-lg rounded-4 p-4 h-100">
          <p class="text-muted">“Biterush is my go-to food app! Delivery is fast, and the UI is super smooth.”</p>
          <div class="d-flex align-items-center mb-3">
            <div class="text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
            <small class="text-muted ms-2">(4.5/5)</small>
          </div>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <img src="/images/users/anurag.png" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
              <h6 class="mb-0 fw-semibold">Anurag Pillay</h6>
              <small class="text-muted">Hyderabad</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4" data-aos="flip-left" data-aos-delay="150">
        <div class="card border-0 shadow-lg rounded-4 p-4 h-100">
          <p class="text-muted">“I discovered local gems I never knew existed. Highly recommend!”</p>
          <div class="d-flex align-items-center mb-3">
            <div class="text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star"></i>
            </div>
            <small class="text-muted ms-2">(4/5)</small>
          </div>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <img src="/images/users/prerna.png" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
              <h6 class="mb-0 fw-semibold">Prerna Jagdale</h6>
              <small class="text-muted">Pune</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4" data-aos="flip-left" data-aos-delay="150">
        <div class="card border-0 shadow-lg rounded-4 p-4 h-100">
          <p class="text-muted">“Their thalis are top-notch. I order every weekend without fail!”</p>
          <div class="d-flex align-items-center mb-3">
            <div class="text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <small class="text-muted ms-2">(5/5)</small>
          </div>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <img src="/images/users/pranoti.png" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
              <h6 class="mb-0 fw-semibold">Pranoti Lachake</h6>
              <small class="text-muted">Mumbai</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-md-4" data-aos="flip-left" data-aos-delay="150">
        <div class="card border-0 shadow-lg rounded-4 p-4 h-100">
          <p class="text-muted">“Superb packaging and delicious food! Even the juices stay fresh.”</p>
          <div class="d-flex align-items-center mb-3">
            <div class="text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
              <i class="bi bi-star"></i>
            </div>
            <small class="text-muted ms-2">(3.5/5)</small>
          </div>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <img src="/images/users/ankita.png" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
              <h6 class="mb-0 fw-semibold">Ankita Shelar</h6>
              <small class="text-muted">Bangalore</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="col-md-4" data-aos="flip-left" data-aos-delay="150">
        <div class="card border-0 shadow-lg rounded-4 p-4 h-100">
          <p class="text-muted">“Milkshakes and biryani combos are worth every penny. Love it!”</p>
          <div class="d-flex align-items-center mb-3">
            <div class="text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star"></i>
              <i class="bi bi-star"></i>
            </div>
            <small class="text-muted ms-2">(3/5)</small>
          </div>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <img src="/images/users/riana.png" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
              <h6 class="mb-0 fw-semibold">Riana Dsouza</h6>
              <small class="text-muted">Lonavala</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 6 -->
      <div class="col-md-4" data-aos="flip-left" data-aos-delay="150" >
        <div class="card border-0 shadow-lg rounded-4 p-4 h-100">
          <p class="text-muted">“Impressive delivery tracking and friendly support. Great experience!”</p>
          <div class="d-flex align-items-center mb-3">
            <div class="text-warning">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star"></i>
            </div>
            <small class="text-muted ms-2">(4/5)</small>
          </div>
          <div class="d-flex align-items-center gap-3 mt-auto">
            <img src="/images/users/saad.png" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
              <h6 class="mb-0 fw-semibold">Saad Shaikh</h6>
              <small class="text-muted">Mumbai</small>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<style>
  footer {
    background: linear-gradient(to right, #1c1c1c, #2b2b2b);
    color: #f0f0f0;
    font-family: 'Segoe UI', sans-serif;
  }

  footer h4, footer h6 {
    color: #ffffff;
  }

  footer a {
    color: #bbb;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  footer a:hover {
    color: #ffb74d;
    text-decoration: underline;
  }

  .footer-icons a {
    color: #ffb74d;
    background: rgba(255, 183, 77, 0.1);
    border-radius: 50%;
    padding: 10px;
    display: inline-flex;
    transition: transform 0.3s ease;
  }

  .footer-icons a:hover {
    transform: scale(1.15);
    background: rgba(255, 183, 77, 0.2);
  }

  .footer-divider {
    border-top: 1px solid #444;
  }

  .footer-bottom {
    font-size: 0.9rem;
    color: #999;
    padding-top: 20px;
  }

  @media (max-width: 768px) {
    footer .col-md-4, footer .col-md-2 {
      text-align: center;
    }

    .footer-icons {
      justify-content: center;
    }
  }
</style>

<footer class="pt-5 pb-4">
  <div class="container">
    <div class="row g-4">

      <!-- BiteRush Info -->
      <div class="col-md-4">
        <h4 class="fw-bold mb-3">🍴 BiteRush</h4>
        <p class="text-white-50">Delivering happiness, one bite at a time. Discover local flavors, trending dishes & hidden gems.</p>
        <div class="d-flex gap-3 mt-3 footer-icons">
          <a href="#"><i class="bi bi-facebook fs-5"></i></a>
          <a href="#"><i class="bi bi-instagram fs-5"></i></a>
          <a href="#"><i class="bi bi-twitter-x fs-5"></i></a>
          <a href="#"><i class="bi bi-youtube fs-5"></i></a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-md-2">
        <h6 class="fw-semibold mb-3">Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="#">Home</a></li>
          <li><a href="#">Menu</a></li>
          <li><a href="#">Restaurants</a></li>
          <li><a href="#">Offers</a></li>
        </ul>
      </div>

      <!-- Company -->
      <div class="col-md-2">
        <h6 class="fw-semibold mb-3">Company</h6>
        <ul class="list-unstyled">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>

      <!-- Contact Info -->
      <div class="col-md-4">
        <h6 class="fw-semibold mb-3">Get in Touch</h6>
        <p class="text-white-50 mb-1"><i class="bi bi-envelope me-2"></i>support@biterush.in</p>
        <p class="text-white-50 mb-1"><i class="bi bi-telephone me-2"></i>+91 9325146732</p>
        <p class="text-white-50"><i class="bi bi-geo-alt me-2"></i>Pune, Maharashtra, India</p>
      </div>

    </div>

    <hr class="footer-divider mt-5">

    <div class="text-center footer-bottom">
      &copy; 2025 <strong>BiteRush</strong>. All rights reserved.
    </div>
  </div>
</footer>

<div id="smartSuggestionsPopup" class="popup-suggestions shadow-lg">
    <div class="popup-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🍽️ Smart Meal Suggestions</h5>
        <button class="btn-close" aria-label="Close" onclick="closePopup()"></button>
    </div>
    <div class="popup-body mt-2">
        @if (!empty($suggestedMeals) && count($suggestedMeals) > 0)
            <ul class="list-unstyled mb-0">
                @foreach($suggestedMeals as $meal)
                    <li class="mb-2">
                        <i class="bi bi-star-fill text-warning"></i>
                        <a href="{{ route('all-meals') }}" class="text-decoration-none">
                            {{ $meal['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No suggestions available right now.</p>
        @endif
    </div>
</div>










  <!-- Script Section -->

<script>
const searchPlaceholders = [
  "Search for meals or dishes...",
  "Try 'Paneer Butter Masala'",
  "Looking for Chinese noodles?",
  "Craving juicy burgers?",
  "What about desserts today?",
  "Pizza, Pasta or Paratha?"
];

let pIndex = 0;
let charIndex = 0;
let isDeleting = false;
const searchInput = document.getElementById("searchInput");

function animatePlaceholder() {
  const text = searchPlaceholders[pIndex];
  const current = isDeleting
    ? text.substring(0, charIndex--)
    : text.substring(0, charIndex++);

  searchInput.setAttribute("placeholder", current);

  let speed = isDeleting ? 40 : 70;

  if (!isDeleting && charIndex === text.length) {
    speed = 2000;
    isDeleting = true;
  } else if (isDeleting && charIndex === 0) {
    isDeleting = false;
    pIndex = (pIndex + 1) % searchPlaceholders.length;
    speed = 600;
  }

  setTimeout(animatePlaceholder, speed);
}

document.addEventListener("DOMContentLoaded", animatePlaceholder);
</script>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if ($errors->any() || session('error'))
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            let loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        });
    </script>
@endif

@if (session('form') === 'register' && $errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
        registerModal.show();
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('fadeOverlay');

    if (overlay) {
        overlay.style.opacity = 0;
        overlay.style.pointerEvents = 'none';
    }

    const input = document.getElementById('searchInput');
    if (!input) return;

    input.addEventListener('focus', function () {
        overlay.style.pointerEvents = 'auto';
        overlay.style.opacity = 1;

        setTimeout(() => {
            window.location.href = "/search";
        }, 400);
    });
});

window.addEventListener('pageshow', function (event) {
    const overlay = document.getElementById('fadeOverlay');
    if (overlay) {
        overlay.style.opacity = 0;
        overlay.style.pointerEvents = 'none';
    }
});
</script>


<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000, 
    once: false,    
    offset: 60,     
    easing: 'ease-in-out'
  });
</script>

<script>
function closePopup() {
    const popup = document.getElementById("smartSuggestionsPopup");
    popup.style.display = "none";
}
</script>

@if(session('not_admin'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Access Denied',
            text: "{{ session('not_admin') }}",
            confirmButtonColor: '#d33'
        });
    </script>
@endif







</body>
</html>
@endsection