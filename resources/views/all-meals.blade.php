@php use Illuminate\Support\Str; @endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Explore All Meals | Biterush</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #fffdf7;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero {
      background: linear-gradient(to right, #fff3e0, #ffe0b2);
      padding: 60px 0 30px;
      text-align: center;
    }

    .delivery-info {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 1rem;
      flex-wrap: wrap;
      animation: fadeIn 1s ease-in-out;
    }

    .badge-city {
      background: #43a047;
      color: white;
      padding: 10px 20px;
      border-radius: 30px;
      font-weight: 600;
      font-size: 1rem;
      display: inline-block;
      animation: pop 0.4s ease-in-out;
    }

    .change-city-btn {
      border: none;
      background: #f44336;
      color: white;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
    }

    .change-city-btn:hover {
      background: #d32f2f;
    }

    .search-wrapper {
      max-width: 400px;
      margin: 30px auto 20px;
      animation: fadeInUp 1s ease-in-out;
    }

    .input-group input {
      border-radius: 0.5rem 0 0 0.5rem;
    }

    .input-group-text {
      border-radius: 0 0.5rem 0.5rem 0;
    }

    .meal-card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.07);
      transition: all 0.3s ease-in-out;
    }

    .meal-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .meal-card img {
      height: 200px;
      object-fit: cover;
    }

    .restaurant-list {
      font-size: 0.9rem;
      color: #555;
      margin-top: 10px;
    }

    .restaurant-list span {
      display: inline-block;
      margin-right: 6px;
      background: #f1f1f1;
      padding: 4px 10px;
      border-radius: 20px;
    }

    @keyframes pop {
      0% { transform: scale(0.8); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

@extends('layouts.app')

@section('content')


@if (!session('selected_city'))
  <section class="py-5">
    <div class="container text-center">
      <h4 class="mb-3">🌆 Please select your city to continue</h4>
      <form method="POST" action="{{ route('search.set.city') }}" class="d-inline-block">
        @csrf
        <select name="city" class="form-select w-auto d-inline-block" onchange="this.form.submit()" required>
          <option disabled selected>Select your city</option>
          @foreach($cities as $city)
            <option value="{{ $city }}">{{ $city }}</option>
          @endforeach
        </select>
      </form>
    </div>
  </section>
@else

<section class="hero">
  <div class="container">
    <h1 style="font-weight: 1000;">Explore All Our Delicious Meals 🍽️</h1>
    <p class="lead">From thalis to street food, find it all right here!</p>

    <div class="delivery-info mt-3">
      <span class="badge-city">🚚 Delivering to: {{ session('selected_city') }}</span>
      <a href="{{ route('search.clear.city') }}" class="change-city-btn">Change</a>
    </div>

    <div class="search-wrapper">
      <div class="input-group shadow">
        <label for="mealSearch" class="visually-hidden">Search Meals</label>
        <input type="text" id="mealSearch" class="form-control border-end-0" placeholder="Search meals...">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row g-4" id="mealsGrid">
      @foreach($meals as $meal)
        <div class="col-md-4 meal-item">
          <div class="card meal-card h-100">
            <img src="{{ $meal['image'] ?? asset('images/placeholder-meal.jpg') }}" class="card-img-top" alt="{{ $meal['name'] }}">
            <div class="card-body">
              <h5 class="card-title">{{ $meal['name'] }}</h5>
              @if(!empty($meal['restaurants']))
                <p class="text-muted mb-1">Available at {{ count($meal['restaurants']) }} restaurants</p>
                <div class="restaurant-list">
                  @foreach(array_slice($meal['restaurants'], 0, 3) as $restaurant)
                    <span class="badge bg-light text-dark">{{ $restaurant['name'] ?? 'Unnamed' }}</span>
                  @endforeach
                </div>
              @endif
              <a href="{{ route('meals.restaurants', ['slug' => Str::slug($meal['name'])]) }}" class="btn btn-outline-primary btn-sm mt-3">View Restaurants</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endif

<script>
  const searchInput = document.getElementById('mealSearch');
  const cards = document.querySelectorAll('.meal-item');

  searchInput.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();
    let anyVisible = false;

    cards.forEach(card => {
      const title = card.querySelector('.card-title').textContent.toLowerCase();
      if (title.startsWith(query) || title.includes(" " + query)) {
        card.style.display = 'block';
        anyVisible = true;
      } else {
        card.style.display = 'none';
      }
    });

    const existingMsg = document.getElementById('noResultsMsg');
    if (!anyVisible) {
      if (!existingMsg) {
        const msg = document.createElement('div');
        msg.id = 'noResultsMsg';
        msg.className = 'text-center text-muted mt-4';
        msg.textContent = `😔 No meals found for "${query}"`;
        document.getElementById('mealsGrid').appendChild(msg);
      }
    } else if (existingMsg) {
      existingMsg.remove();
    }
  });
</script>

</body>
</html>

@endsection