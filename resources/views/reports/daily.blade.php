@extends('layouts.app')
@section('content')
<h5 class="fw-bold mb-4">{{ __('m.daily_report') }}</h5>
<form method="GET" class="d-flex gap-2 mb-4">
    <input type="date" name="date" class="form-control" style="max-width:200px;" value="{{ $date }}">
    <button class="btn btn-primary"><i class="bi bi-search me-1"></i>{{ __('m.generate') }}</button>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">{{ __('m.total_revenue') }}</div><div class="stat-value text-primary">{{ number_format($totalRevenue, 2) }}</div><small class="text-muted">{{ __('m.etb') }}</small></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">{{ __('m.drink_sales') }}</div><div class="stat-value" style="font-size:1.3rem;">{{ number_format($drinkSales, 2) }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">{{ __('m.food_sales') }}</div><div class="stat-value" style="font-size:1.3rem;">{{ number_format($foodSales, 2) }}</div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">{{ __('m.bed_income') }}</div><div class="stat-value" style="font-size:1.3rem;">{{ number_format($bedIncome, 2) }}</div></div></div>
</div>

<div class="row g-3 mb-4">
    @foreach($paymentBreakdown as $method => $amount)
    <div class="col"><div class="stat-card text-center"><div class="stat-label">{{ __('m.'.$method) }}</div><div class="fw-bold">{{ number_format($amount, 2) }}</div></div></div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('m.orders') }} ({{ $orders->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>{{ __('m.order_number') }}</th><th>{{ __('m.time') }}</th><th>{{ __('m.employee') }}</th><th>{{ __('m.total') }}</th><th>{{ __('m.payment_method') }}</th></tr></thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr><td>{{ $order->order_number }}</td><td>{{ $order->created_at->format('H:i') }}</td><td>{{ $order->employee?->name ?? '-' }}</td><td class="fw-bold">{{ number_format($order->total, 2) }}</td><td>{{ __('m.'.$order->payment_method) }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">{{ __('m.rooms') }} ({{ $rentals->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>{{ __('m.room_number') }}</th><th>{{ __('m.guest_name') }}</th><th>{{ __('m.total') }}</th></tr></thead>
                    <tbody>
                    @foreach($rentals as $r)
                        <tr><td>{{ $r->room->room_number }}</td><td>{{ $r->guest_name }}</td><td class="fw-bold">{{ number_format($r->total_price, 2) }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
