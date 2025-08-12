<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar { height: 100vh; background: #ff4e50; padding: 20px; color: white; }
        .sidebar a { color: white; display: block; padding: 10px; text-decoration: none; }
        .sidebar a:hover { background: #495057; color: black; }
        .content { margin-left: 220px; padding: 30px; }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar position-fixed">
            <h4>BiteRush Admin</h4>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.restaurants.index') }}">Restaurants</a>
            <a href="{{ route('admin.meals.index') }}">Meals</a>
            <a href="{{ route('admin.orders.index') }}">Orders</a>
            <a href="{{ route('admin.users.index') }}">Users</a>
            <a href="{{ route('admin.coupons.index') }}">Coupons</a>
            <a href="{{ route('admin.settings.index') }}">Settings</a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </div>
        <div class="content w-100">
            @yield('content')
        </div>
    </div>

    @stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
