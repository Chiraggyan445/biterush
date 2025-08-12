@extends('admin.layouts.admin')
@section('title', 'Meals')

@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-4">
    <h2 class="mb-4">🍽️ Manage Meals</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Add Meal Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addMealModal">➕ Add Meal</button>

    <!-- Meals Table -->
    <table class="table table-bordered align-middle bg-white shadow-sm">
        <thead class="table-light">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th style="width: 180px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($meals as $meal)
            <tr>
                <td>
                    <img src="{{ asset($meal['image']) }}" width="80"
                         onerror="this.src='{{ asset('images/no-image.png') }}'">
                </td>
                <td>{{ $meal['name'] }}</td>
                <td>{{ Str::limit($meal['description'] ?? '', 60) }}</td>
                <td>
                    <!-- 📝 Edit -->
                    <button class="btn btn-sm btn-primary me-1"
                            data-bs-toggle="modal"
                            data-bs-target="#editMealModal"
                            data-meal='@json($meal)'>
                        ✏️ Edit
                    </button>

                    <!-- 🗑️ Delete -->
                    <form action="{{ route('admin.meals.destroy', $meal['slug']) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this meal?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">🗑️ Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No meals found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ✅ Add Meal Modal -->
<div class="modal fade" id="addMealModal" tabindex="-1" aria-labelledby="addMealModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('admin.meals.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addMealModalLabel">➕ Add Meal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Meal Name</label>
              <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">Image</label>
              <input type="file" name="image" class="form-control" required>
          </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Edit Meal Modal (shared, dynamic) -->
<div class="modal fade" id="editMealModal" tabindex="-1" aria-labelledby="editMealModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editMealForm" class="modal-content" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="editMealModalLabel">✏️ Edit Meal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label class="form-label">Meal Name</label>
              <input type="text" name="name" id="editMealName" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" id="editMealDescription" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
              <label class="form-label">New Image (optional)</label>
              <input type="file" name="image" class="form-control">
          </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Edit Modal JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editMealModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const meal = JSON.parse(button.getAttribute('data-meal'));

        document.getElementById('editMealName').value = meal.name;
        document.getElementById('editMealDescription').value = meal.description ?? '';

        const form = document.getElementById('editMealForm');
        form.action = "/admin/meals/" + meal.slug + "/update";
    });

    editModal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('editMealForm').reset();
    });
});
</script>
@endsection
