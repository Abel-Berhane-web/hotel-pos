@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ __('m.products') }}</h5>
    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus me-1"></i>{{ __('m.add_product') }}</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('m.name_en') }}</th><th>{{ __('m.name_am') }}</th><th>{{ __('m.category') }}</th><th>{{ __('m.selling_price') }}</th><th>{{ __('m.stock_qty') }}</th><th>{{ __('m.status') }}</th><th>{{ __('m.actions') }}</th></tr></thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td class="fw-semibold">{{ $product->name_en }}</td>
                    <td>{{ $product->name_am }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ number_format($product->selling_price, 2) }}</td>
                    <td>
                        @if($product->track_stock)
                            <span class="{{ $product->isLowStock() ? 'text-danger fw-bold' : '' }}">{{ $product->stock_quantity }}</span>
                        @else <span class="text-muted">—</span> @endif
                    </td>
                    <td><span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $product->is_active ? __('m.active') : __('m.inactive') }}</span></td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <!-- Stock Adjust Modal Trigger -->
                        @if($product->track_stock)
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#stockModal{{ $product->id }}"><i class="bi bi-box-seam"></i></button>
                        <!-- Stock Modal -->
                        <div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
                            <div class="modal-header"><h6 class="modal-title fw-bold">{{ __('m.adjust_stock') }}: {{ $product->name }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <form method="POST" action="{{ route('products.stock', $product) }}">@csrf
                            <div class="modal-body">
                                <p class="text-muted">{{ __('m.stock_qty') }}: <strong>{{ $product->stock_quantity }}</strong></p>
                                <div class="mb-3"><label class="form-label">{{ __('m.type') }}</label><select name="type" class="form-select"><option value="purchase">{{ __('m.purchase') }}</option><option value="adjustment">{{ __('m.adjustment') }}</option><option value="damage">{{ __('m.damage') }}</option><option value="return">{{ __('m.return') }}</option></select></div>
                                <div class="mb-3"><label class="form-label">{{ __('m.quantity') }}</label><input type="number" name="quantity" class="form-control" min="1" required></div>
                                <div class="mb-3"><label class="form-label">{{ __('m.reason') }}</label><input type="text" name="reason" class="form-control"></div>
                            </div>
                            <div class="modal-footer"><button type="submit" class="btn btn-primary">{{ __('m.save') }}</button></div>
                            </form>
                        </div></div></div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
