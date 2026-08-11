@extends('layouts.app')
@section('content')
<h5 class="fw-bold mb-4">{{ __('m.stock_report') }}</h5>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('m.product_name') }}</th><th>{{ __('m.category') }}</th><th>{{ __('m.stock_qty') }}</th><th>{{ __('m.status') }}</th></tr></thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="fw-semibold">{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td class="{{ $product->isLowStock() ? 'text-danger fw-bold' : '' }}">{{ $product->stock_quantity }}</td>
                    <td>@if($product->isLowStock())<span class="badge bg-danger">{{ __('m.low_stock') }}</span>@else <span class="badge bg-success">OK</span>@endif</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
