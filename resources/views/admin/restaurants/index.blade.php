@extends('admin.layouts.admin')

@section('title', 'Manage Restaurants')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Manage Restaurants</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addRestaurantModal">
        <i class="bi bi-plus-circle me-1"></i> Add Restaurant
    </button>

    <!-- Status Filter -->
    <form class="mb-3">
        <label class="form-label me-2">Status Filter:</label>
        <select name="status" onchange="this.form.submit()" class="form-select w-auto d-inline-block">
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
    </form>

    @if($restaurants->count())
    <!-- Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>City</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($restaurants as $restaurant)
            <tr>
                <td>{{ $restaurant->id }}</td>
                <td>{{ $restaurant->name }}</td>
                <td>{{ $restaurant->city }}</td>
                <td>
                    <span class="badge bg-{{ $restaurant->status == 'approved' ? 'success' : ($restaurant->status == 'pending' ? 'warning' : 'secondary') }}">
                        {{ ucfirst($restaurant->status) }}
                    </span>
                </td>
                <td>
                    <!-- Edit Button -->
                    <button class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#editRestaurantModal{{ $restaurant->id }}">
                        <i class="bi bi-pencil-square me-1"></i>Edit
                    </button>

                    <!-- Delete -->
                    <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this restaurant?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </form>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editRestaurantModal{{ $restaurant->id }}" tabindex="-1">
                      <div class="modal-dialog">
                        <form action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST" class="modal-content">
                          @csrf
                          @method('PUT')
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Restaurant #{{ $restaurant->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <div class="mb-3">
                              <label class="form-label">Name</label>
                              <input type="text" name="name" value="{{ $restaurant->name }}" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Address</label>
                              <input type="text" name="address" value="{{ $restaurant->address }}" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">City</label>
                              <input type="text" name="city" value="{{ $restaurant->city }}" class="form-control" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Status</label>
                              <select name="status" class="form-select" required>
                                <option value="approved" {{ $restaurant->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ $restaurant->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="suspended" {{ $restaurant->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                              </select>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          </div>
                        </form>
                      </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $restaurants->appends(request()->query())->links() }}
    </div>

    @else
        <div class="alert alert-info">No restaurants found.</div>
    @endif

    <!-- Add Restaurant Modal -->
    <div class="modal fade" id="addRestaurantModal" tabindex="-1">
      <div class="modal-dialog">
        <form action="{{ route('admin.restaurants.store') }}" method="POST" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Add New Restaurant</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <input type="text" name="address" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">City</label>
              <input type="text" name="city" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="suspended">Suspended</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Restaurant</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
</div>
@endsection
