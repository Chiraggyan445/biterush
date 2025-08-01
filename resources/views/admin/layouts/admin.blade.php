<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <a href="#">Restaurants</a>
            <a href="#">Meals</a>
            <a href="{{ route('admin.orders.show', $order->id) }}">Orders #{{ $order->id }}</a>
            <a href="#">Users</a>
            <a href="#">Coupons</a>
            <a href="#">Settings</a>
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
</body>
</html>
