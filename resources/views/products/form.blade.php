@extends('layouts.app')
@section('content')
<div class="mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>{{ __('m.back') }}</a>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-header fw-bold">{{ $product->exists ? __('m.edit_product') : __('m.add_product') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}">
            @csrf
            @if($product->exists) @method('PUT') @endif
            <div class="mb-3"><label class="form-label">{{ __('m.name_en') }} *</label><input type="text" name="name_en" class="form-control" value="{{ old('name_en', $product->name_en) }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.name_am') }} *</label><input type="text" name="name_am" class="form-control" value="{{ old('name_am', $product->name_am) }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.category') }} *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">{{ __('m.select') }}</option>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }} / {{ $cat->name_am }}</option>@endforeach
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-6"><label class="form-label">{{ __('m.selling_price') }} *</label><input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', $product->selling_price) }}" required></div>
                <div class="col-6"><label class="form-label">{{ __('m.cost_price') }}</label><input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}"></div>
            </div>
            <div class="row mb-3">
                <div class="col-6"><label class="form-label">{{ __('m.unit') }}</label><input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit ?? 'piece') }}"></div>
                <div class="col-6"><label class="form-label">{{ __('m.stock_qty') }}</label><input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}"></div>
            </div>
            <div class="mb-3 form-check"><input type="checkbox" name="track_stock" value="1" class="form-check-input" id="trackStock" {{ old('track_stock', $product->track_stock ?? true) ? 'checked' : '' }}><label class="form-check-label" for="trackStock">{{ __('m.track_stock') }}</label></div>
            <div class="mb-3 form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}><label class="form-check-label" for="isActive">{{ __('m.active') }}</label></div>
            <button type="submit" class="btn btn-primary">{{ __('m.save') }}</button>
        </form>
    </div>
</div>
@endsection
