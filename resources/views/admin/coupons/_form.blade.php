@php $isEdit = isset($coupon); @endphp

<div class="mb-3">
    <label for="code" class="form-label">Coupon Code</label>
    <input type="text" name="code" id="code" class="form-control"
           value="{{ old('code', $isEdit ? $coupon->code : '') }}" required>
</div>

<div class="mb-3">
    <label for="discount_type" class="form-label">Discount Type</label>
    <select name="discount_type" id="discount_type" class="form-control" required>
        <option value="percent" {{ old('discount_type', $isEdit ? $coupon->discount_type : '') == 'percent' ? 'selected' : '' }}>
            Percent (%)
        </option>
        <option value="flat" {{ old('discount_type', $isEdit ? $coupon->discount_type : '') == 'flat' ? 'selected' : '' }}>
            Flat (₹)
        </option>
    </select>
</div>

<div class="mb-3">
    <label for="discount_value" class="form-label">Discount Value</label>
    <input type="number" step="0.01" name="discount_value" id="discount_value" class="form-control"
           value="{{ old('discount_value', $isEdit ? $coupon->discount_value : '') }}" required>
</div>

<div class="mb-3">
    <label for="max_discount" class="form-label">Max Discount</label>
    <input type="number" step="0.01" name="max_discount" id="max_discount" class="form-control"
           value="{{ old('max_discount', $isEdit ? $coupon->max_discount : '') }}">
</div>

<div class="mb-3">
    <label for="valid_from" class="form-label">Valid From</label>
    <input type="date" name="valid_from" id="valid_from" class="form-control"
           value="{{ old('valid_from', $isEdit && $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '') }}">
</div>

<div class="mb-3">
    <label for="valid_to" class="form-label">Valid To</label>
    <input type="date" name="valid_to" id="valid_to" class="form-control"
           value="{{ old('valid_to', $isEdit && $coupon->valid_to ? $coupon->valid_to->format('Y-m-d') : '') }}">
</div>

<div class="mb-3">
    <label for="source" class="form-label">Source</label>
    @php
        $selectedSource = old('source', $isEdit ? $coupon->source : '');
    @endphp
    <select name="source" id="source" class="form-control" required>
        <option value="">Select Source</option>
        <option value="wheel" {{ $selectedSource == 'wheel' ? 'selected' : '' }}>Wheel</option>
        <option value="manual" {{ $selectedSource == 'manual' ? 'selected' : '' }}>Manual</option>
    </select>
</div>
