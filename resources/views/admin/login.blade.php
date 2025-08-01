@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<style>
    .admin-login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 90vh;
        background: linear-gradient(to right, #ff6e7f, #bfe9ff);
        font-family: 'Segoe UI', sans-serif;
    }

    .admin-login-card {
        background: #fff;
        padding: 35px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 450px;
        animation: fadeInDown 0.6s ease-in-out;
    }

    .admin-login-card h2 {
        font-weight: 700;
        color: #ff4e50;
        margin-bottom: 25px;
        text-align: center;
    }

    .admin-login-card .form-control {
        border-radius: 12px;
        height: 45px;
    }

    .admin-login-card label {
        font-weight: 600;
    }

    .admin-login-card .btn-primary {
        width: 100%;
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
        background: linear-gradient(to right, #ff4e50, #f9d423);
        border: none;
    }

    .admin-login-card .btn-primary:hover {
        background: linear-gradient(to right, #f12711, #f5af19);
    }

    .admin-icon {
        font-size: 40px;
        color: #ff4e50;
        text-align: center;
        margin-bottom: 20px;
    }

    @keyframes fadeInDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .alert-custom {
        background-color: #ffe5e5;
        border-left: 6px solid #ff4e50;
        color: #b30000;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }
</style>

<div class="admin-login-wrapper">
    <div class="admin-login-card">
        <div class="admin-icon">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <h2>Admin Login</h2>

        {{-- Error messages --}}
        @if($errors->any())
            <div class="alert-custom">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="email"><i class="bi bi-envelope-fill"></i> Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="admin@biterush.com" required>
            </div>
            <div class="mb-3">
                <label for="password"><i class="bi bi-lock-fill"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login as Admin
            </button>
        </form>
    </div>
</div>
@endsection
