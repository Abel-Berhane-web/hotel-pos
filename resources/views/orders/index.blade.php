@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ __('m.orders') }}</h5>
    @if(auth()->user()->canCreateOrders())
    <a href="{{ route('orders.create') }}" class="btn btn-primary"><i class="bi bi-plus me-1"></i>{{ __('m.new_order') }}</a>
    @endif
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label" style="font-size:.8rem;">{{ __('m.from_date') }}</label><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
            <div class="col-md-2"><label class="form-label" style="font-size:.8rem;">{{ __('m.to_date') }}</label><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
            <div class="col-md-2"><label class="form-label" style="font-size:.8rem;">{{ __('m.payment_method') }}</label>
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="">{{ __('m.all') }}</option>
                    @foreach(['cash','bank_transfer','telebirr','cbe_birr','credit'] as $pm)
                    <option value="{{ $pm }}" {{ request('payment_method')===$pm?'selected':'' }}>{{ __('m.'.$pm) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-primary w-100"><i class="bi bi-funnel me-1"></i>{{ __('m.filter') }}</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>{{ __('m.order_number') }}</th><th>{{ __('m.date') }}</th><th>{{ __('m.cashier') }}</th><th>{{ __('m.employee') }}</th><th>{{ __('m.items') }}</th><th>{{ __('m.total') }}</th><th>{{ __('m.payment_method') }}</th><th>{{ __('m.actions') }}</th></tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="fw-semibold">{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('M d, H:i') }}</td>
                    <td>{{ $order->cashier->name }}</td>
                    <td>{{ $order->employee?->name ?? '-' }}</td>
                    <td>{{ $order->items->count() }}</td>
                    <td class="fw-bold">{{ number_format($order->total, 2) }}</td>
                    <td><span class="badge badge-{{ $order->payment_method === 'cash' ? 'cash' : ($order->payment_method === 'bank_transfer' ? 'bank' : ($order->payment_method === 'telebirr' ? 'telebirr' : ($order->payment_method === 'cbe_birr' ? 'cbe' : 'credit'))) }}">{{ __('m.'.$order->payment_method) }}</span></td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('orders.destroy', $order) }}" class="d-inline" onsubmit="return confirm('{{ __('m.confirm') }}')">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $orders->withQueryString()->links() }}</div>
@endsection
