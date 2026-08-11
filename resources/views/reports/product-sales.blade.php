@extends('layouts.app')
@section('content')
<h5 class="fw-bold mb-4">{{ __('m.product_sales') }}</h5>
<form method="GET" class="d-flex gap-2 mb-4">
    <input type="date" name="from" class="form-control form-control-sm" style="max-width:160px;" value="{{ $from }}">
    <input type="date" name="to" class="form-control form-control-sm" style="max-width:160px;" value="{{ $to }}">
    <button class="btn btn-sm btn-primary">{{ __('m.generate') }}</button>
</form>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('m.product_name') }}</th><th>{{ __('m.category') }}</th><th>{{ __('m.qty_sold') }}</th><th>{{ __('m.revenue') }}</th></tr></thead>
            <tbody>
            @forelse($products as $p)
                <tr><td class="fw-semibold">{{ $p->product->name }}</td><td>{{ $p->product->category->name }}</td><td>{{ $p->total_qty }}</td><td class="fw-bold">{{ number_format($p->total_revenue, 2) }}</td></tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->withQueryString()->links() }}</div>
@endsection
