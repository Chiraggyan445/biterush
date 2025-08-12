<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recommended Restaurants</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(120deg, #ffe0b2, #ffccbc);
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      padding-top: 50px;
      padding-bottom: 60px;
    }

    .section-heading {
      text-align: center;
      font-size: 2.2rem;
      font-weight: bold;
      color: #d84315;
      margin-bottom: 40px;
    }

    .restaurant-card {
      background: linear-gradient(to top left, #fff8e1, #fff);
      border-radius: 18px;
      padding: 25px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease-in-out;
      height: 100%;
      position: relative;
    }

    .restaurant-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
    }

    .emoji-icon {
      font-size: 2.5rem;
      position: absolute;
      top: -20px;
      right: 20px;
    }

    .restaurant-name {
      font-size: 1.3rem;
      font-weight: 600;
      color: #ff6f00;
    }

    .restaurant-city {
      font-size: 0.9rem;
      color: #6c757d;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 4px;
    }

    .restaurant-address {
      font-size: 0.95rem;
      color: #444;
      margin: 15px 0;
    }

    .info-badges span {
      font-size: 0.75rem;
      padding: 6px 10px;
      border-radius: 20px;
    }

    .pagination {
      justify-content: center;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="section-heading fw-bold">
    🍽️ Recommended Restaurants for <span class="text-danger">{{ $mealname }}</span>
    <div class="text-center mt-2 mb-4">
      <span class="px-4 py-2 bg-white rounded-pill shadow-sm d-inline-flex align-items-center" style="font-weight: 500; font-size: 1rem;">
        <i class="bi bi-geo-alt-fill me-2 text-danger"></i>
        <span class="text-dark">Serving in</span>
        <span class="ms-1 text-success">{{ ucwords(session('selected_city') ?? 'your city') }}</span>
      </span>
    </div>
  </h2>

  {{-- 🔍 Live Search Bar --}}
  <div class="mb-4">
    <input type="text" id="search-bar" class="form-control" placeholder="Search restaurants by name...">
  </div>

  {{-- 🔁 Restaurants List --}}
  <div id="restaurant-list">
    @if ($restaurants->isEmpty())
      <div class="alert alert-warning text-center">
        😔 No restaurants found serving <strong>{{ $mealname }}</strong>.
      </div>
    @else
      <div class="row g-4">
        @foreach ($restaurants as $restaurant)
          <div class="col-md-6 col-lg-4 restaurant-box" data-name="{{ strtolower($restaurant->name) }}">
            <div class="restaurant-card position-relative cursor-pointer">
              <div class="emoji-icon">🍛</div>
              <div class="restaurant-name">{{ $restaurant->name }}</div>
              <div class="restaurant-city">{{ $restaurant->city ?? 'City N/A' }}</div>
              <p class="restaurant-address">{{ Str::limit($restaurant->address, 100) }}</p>
              <div class="d-flex justify-content-between info-badges mt-3">
                <span class="badge bg-success">✅ Fast Delivery</span>
                <span class="badge bg-dark">⭐ {{ number_format($restaurant->rating ?? 4.0, 1) }}</span>
              </div>
              <a href="{{ route('restaurant.show', ['id' => $restaurant->id]) }}?meal={{ \Str::slug($meal['name']) }}" class="btn btn-outline-success btn-sm mt-3">View Menu</a>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-5 d-flex justify-content-center">
        {{ $restaurants->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
</div>

{{-- 🔄 Live Search JS --}}
<script>
  const searchBar = document.getElementById('search-bar');
  const allRestaurants = document.querySelectorAll('.restaurant-box');

  searchBar.addEventListener('input', function () {
    const keyword = this.value.toLowerCase().trim();

    allRestaurants.forEach(box => {
      const name = box.dataset.name;

      if (name.includes(keyword)) {
        box.style.display = 'block';
      } else {
        box.style.display = 'none';
      }
    });
  });
</script>


</body>
</html>
