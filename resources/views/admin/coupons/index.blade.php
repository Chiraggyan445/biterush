@extends('admin.layouts.admin')
@section('title', 'Coupons')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">🎟️ Manage Coupons</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCouponModal">Add Coupon</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Value</th>
                <th>Max Discount</th>
                <th>Valid From</th>
                <th>Valid To</th>
                <th>Source</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $coupon)
            <tr>
                <td>{{ $coupon->code }}</td>
                <td>{{ ucfirst($coupon->discount_type) }}</td>
                <td>{{ $coupon->discount_type === 'percent' ? $coupon->discount_value.'%' : '₹'.$coupon->discount_value }}</td>
                <td>{{ $coupon->max_discount ?? '—' }}</td>
                <td>{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '—' }}</td>
                <td>{{ $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '—' }}</td>
                <td>{{ ucfirst($coupon->source ?? '—') }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCouponModal{{ $coupon->id }}">Edit</button>

                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
           <div class="modal fade" id="editCouponModal{{ $coupon->id }}" tabindex="-1" aria-labelledby="editCouponModalLabel{{ $coupon->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}" class="modal-content">
            @csrf 
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title" id="editCouponModalLabel{{ $coupon->id }}">Edit Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- ✅ Debugging: show current values --}}
                <div class="mb-2 text-muted small">
                    Editing Coupon: <strong>{{ $coupon->code }}</strong><br>
                    Source: {{ $coupon->source ?? 'null' }}<br>
                    Type: {{ $coupon->discount_type ?? 'null' }}
                </div>

                @include('admin.coupons._form', ['coupon' => $coupon])
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endforeach
        </tbody>
    </table>

<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.coupons.store') }}" class="modal-content">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="addCouponModalLabel">Add Coupon</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            @include('admin.coupons._form')
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary">Add</button>
        </div>
    </form>
  </div>
</div>
@endsection
