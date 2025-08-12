@extends('admin.layouts.admin')
@section('title', 'Add User')

@section('content')
<div class="container py-4">
    <h2>Add New User</h2>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role:</label>
            <select name="role" class="form-control">
                <option value="customer">Customer</option>
                <option value="restaurant">Restaurant</option>
                <option value="agent">Delivery Agent</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add User</button>
    </form>
</div>
@endsection
