@extends('admin.layouts.admin')
@section('title', 'Settings')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">⚙️ Site Settings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div class="mb-3">
            <label>Site Name</label>
            <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label>Currency</label>
            <input type="text" name="currency" class="form-control" value="{{ $settings['currency'] ?? 'INR' }}" required>
        </div>

        <div class="mb-3">
            <label>Razorpay Key</label>
            <input type="text" name="razorpay_key" class="form-control" value="{{ $settings['razorpay_key'] ?? '' }}">
        </div>

        <div class="mb-3">
            <label>Razorpay Secret</label>
            <input type="text" name="razorpay_secret" class="form-control" value="{{ $settings['razorpay_secret'] ?? '' }}">
        </div>

        <div class="mb-3">
            <label>Minimum Order Amount</label>
            <input type="number" name="minimum_order_amount" class="form-control" value="{{ $settings['minimum_order_amount'] ?? 0 }}" required>
        </div>

        <div class="mb-3">
            <label>Delivery Charge</label>
            <input type="number" name="delivery_charge" class="form-control" value="{{ $settings['delivery_charge'] ?? 0 }}" required>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="dark_mode" id="dark_mode" value="1" {{ ($settings['dark_mode'] ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="dark_mode">Enable Dark Mode</label>
        </div>

        <button class="btn btn-primary">Save Settings</button>
    </form>
</div>

@if($settings['dark_mode'] ?? false)
<style>
    body {
        background-color: #121212;
        color: white;
    }
    input, select, textarea, .form-control {
        background-color: #1e1e1e;
        color: white;
        border-color: #444;
    }
</style>
@endif
@endsection
