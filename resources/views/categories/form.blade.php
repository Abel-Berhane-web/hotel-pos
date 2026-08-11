@extends('layouts.app')
@section('content')
<a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>{{ __('m.back') }}</a>
<div class="card" style="max-width:500px;">
    <div class="card-header fw-bold">{{ $category->exists ? __('m.edit_category') : __('m.add_category') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}">
            @csrf @if($category->exists) @method('PUT') @endif
            <div class="mb-3"><label class="form-label">{{ __('m.name_en') }} *</label><input type="text" name="name_en" class="form-control" value="{{ old('name_en', $category->name_en) }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.name_am') }} *</label><input type="text" name="name_am" class="form-control" value="{{ old('name_am', $category->name_am) }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.type') }}</label><select name="type" class="form-select"><option value="drink" {{ old('type', $category->type)==='drink'?'selected':'' }}>{{ __('m.drink') }}</option><option value="food" {{ old('type', $category->type)==='food'?'selected':'' }}>{{ __('m.food') }}</option></select></div>
            <div class="mb-3"><label class="form-label">{{ __('m.sort_order') }}</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}"></div>
            <div class="mb-3 form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $category->is_active ?? true)?'checked':'' }}><label class="form-check-label" for="isActive">{{ __('m.active') }}</label></div>
            <button type="submit" class="btn btn-primary">{{ __('m.save') }}</button>
        </form>
    </div>
</div>
@endsection
