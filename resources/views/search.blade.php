@php
  $selectedCity = session('selected_city');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Select City | Biterush</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      background: linear-gradient(to right, #fff8e1, #ffe0b2);
      font-family: 'Segoe UI', sans-serif;
      padding-bottom: 60px;
      color: #333;
    }

    .card-wrapper {
      max-width: 500px;
      margin: auto;
    }

    .city-card {
      background: #fff9ec;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .input-group-text {
      border-radius: 30px 0 0 30px;
      background: #fff;
      border: none;
      font-size: 1.3rem;
      color: #d32f2f;
      padding: 0 20px;
    }

    .form-select {
      border-radius: 0 30px 30px 0;
      border: none;
      padding: 14px;
    }

    #bgVideo {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      z-index: -2;
    }

    .video-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
    }

    #loader {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(255, 255, 255, 0.85);
      z-index: 9999;
      justify-content: center;
      align-items: center;
    }

    #loader img {
      width: 100px;
      height: 100px;
    }
  </style>
</head>
<body>

<video autoplay muted loop playsinline id="bgVideo">
  <source src="/videos/all.mp4" type="video/mp4" />
</video>
<div class="video-overlay"></div>

<div id="loader">
  <img src="{{ asset('images/food-delivery-unscreen.gif') }}" alt="Loading...">
</div>

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card-wrapper">
    <div class="city-card text-center">
      <h4 class="mb-3">🌆 <strong>Where should we deliver?</strong></h4>
      <p class="text-muted mb-4">Select your city to explore meals & restaurants</p>

      <form method="POST" action="{{ route('search.set.city') }}">
        @csrf
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-geo-alt-fill text-danger"></i></span>
          <select name="city" class="form-select" onchange="this.form.submit()" required>
            <option value="" disabled selected>Choose your city...</option>
            @foreach ($cities as $city)
              <option value="{{ $city }}">{{ $city }}</option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>