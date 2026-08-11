@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ __('m.categories') }}</h5>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus me-1"></i>{{ __('m.add_category') }}</a>
</div>
<div class="card" style="max-width:700px;">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('m.name_en') }}</th><th>{{ __('m.name_am') }}</th><th>{{ __('m.type') }}</th><th>{{ __('m.sort_order') }}</th><th>{{ __('m.status') }}</th><th>{{ __('m.actions') }}</th></tr></thead>
            <tbody>
            @forelse($categories as $cat)
                <tr><td>{{ $cat->name_en }}</td><td>{{ $cat->name_am }}</td><td><span class="badge bg-{{ $cat->type==='drink'?'info':'warning' }}">{{ __('m.'.$cat->type) }}</span></td><td>{{ $cat->sort_order }}</td><td><span class="badge {{ $cat->is_active?'bg-success':'bg-secondary' }}">{{ $cat->is_active?__('m.active'):__('m.inactive') }}</span></td>
                <td><a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a></td></tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
