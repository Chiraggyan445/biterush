    @extends('admin.layouts.admin')

    @section('title', 'Admin Dashboard')

    @section('content')
    <style>
        .dashboard-header h2 {
            color: #ff4e50;
            font-weight: 700;
        }

        .card-stat {
            border: none;
            border-radius: 15px;
            background: orange;
            color: white;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card-stat:hover {
            transform: translateY(-5px);
        }

        .card-stat h5 {
            font-weight: 600;
            margin-bottom: 0;
        }

        .card-stat h3 {
            font-size: 2rem;
            font-weight: bold;
        }

        .table thead th {
            background-color: #f8f9fa;
        }

        .badge-status {
            padding: 0.4rem 0.7rem;
            font-size: 0.85rem;
            border-radius: 10px;
            text-transform: capitalize;
        }

        .badge-success { background-color: #28a745; }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-failed { background-color: #dc3545; }
        .badge-cancelled { background-color: #6c757d; }
    </style>

    <div class="dashboard-header mb-4">
        <h2>🍽️ Welcome, Admin!</h2>
        <p>Monitor your orders, users, meals, and restaurants all in one place.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card card-stat p-4">
                <div class="card-body text-center">
                    <h5><i class="bi bi-people-fill me-1"></i> Users</h5>
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-4">
                <div class="card-body text-center">
                    <h5><i class="bi bi-shop me-1"></i> Restaurants</h5>
                    <h3>{{ $totalRestaurants }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-4">
                <div class="card-body text-center">
                    <h5><i class="bi bi-egg-fried me-1"></i> Meals</h5>
                    <h3>{{ $totalMeals }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-4">
                <div class="card-body text-center">
                    <h5><i class="bi bi-bag-check-fill me-1"></i> Paid Orders</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5 shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Orders</h5>
        </div>
        <div class="card-body">
            @if($recentOrders->isEmpty())
                <p class="text-muted">No recent orders found.</p>
            @else
            <div class="table-responsive">
                <table class="table align-middle table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Placed On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge badge-status badge-{{ $order->status }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    @endsection
