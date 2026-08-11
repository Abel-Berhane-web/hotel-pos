@extends('layouts.app')
@section('content')
<div class="mb-4">
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>{{ __('m.back') }}</a>
    <h5 class="fw-bold">{{ $order->order_number }}</h5>
</div>
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('m.items') }}</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>{{ __('m.product_name') }}</th><th>{{ __('m.quantity') }}</th><th>{{ __('m.price') }}</th><th>{{ __('m.total') }}</th></tr></thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td class="fw-bold">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-end fw-bold">{{ __('m.subtotal') }}</td><td class="fw-bold">{{ number_format($order->subtotal, 2) }}</td></tr>
                        @if($order->tax > 0)<tr><td colspan="3" class="text-end">{{ __('m.tax') }}</td><td>{{ number_format($order->tax, 2) }}</td></tr>@endif
                        <tr><td colspan="3" class="text-end fw-bold" style="font-size:1.1rem;">{{ __('m.total') }}</td><td class="fw-bold text-primary" style="font-size:1.1rem;">{{ number_format($order->total, 2) }} {{ __('m.etb') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <p><strong>{{ __('m.order_date') }}:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p><strong>{{ __('m.cashier') }}:</strong> {{ $order->cashier->name }}</p>
                <p><strong>{{ __('m.employee') }}:</strong> {{ $order->employee?->name ?? '-' }}</p>
                <p><strong>{{ __('m.payment_method') }}:</strong> {{ __('m.'.$order->payment_method) }}</p>
                @if($order->note)<p><strong>{{ __('m.note') }}:</strong> {{ $order->note }}</p>@endif
            </div>
        </div>
    </div>
</div>
@endsection
