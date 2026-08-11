@extends('layouts.app')
@section('content')
<h5 class="fw-bold mb-4">{{ __('m.period_report') }}</h5>
<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('reports.period', ['preset'=>'last_15']) }}" class="btn btn-sm {{ $preset==='last_15'?'btn-primary':'btn-outline-primary' }}">{{ __('m.last_15_days') }}</a>
    <a href="{{ route('reports.period', ['preset'=>'last_30']) }}" class="btn btn-sm {{ $preset==='last_30'?'btn-primary':'btn-outline-primary' }}">{{ __('m.last_30_days') }}</a>
    <a href="{{ route('reports.period', ['preset'=>'this_month']) }}" class="btn btn-sm {{ $preset==='this_month'?'btn-primary':'btn-outline-primary' }}">{{ __('m.this_month') }}</a>
</div>
<form method="GET" class="d-flex gap-2 mb-4">
    <input type="date" name="from" class="form-control form-control-sm" style="max-width:160px;" value="{{ $from }}">
    <input type="date" name="to" class="form-control form-control-sm" style="max-width:160px;" value="{{ $to }}">
    <button class="btn btn-sm btn-primary">{{ __('m.generate') }}</button>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-label">{{ __('m.total_revenue') }}</div><div class="stat-value text-primary">{{ number_format($totalRevenue, 2) }}</div></div></div>
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
            <div class="card-header">{{ __('m.daily_report') }}</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>{{ __('m.date') }}</th><th>{{ __('m.orders') }}</th><th>{{ __('m.orders') }} {{ __('m.etb') }}</th><th>{{ __('m.rooms') }}</th><th>{{ __('m.total') }}</th></tr></thead>
                    <tbody>
                    @foreach($dailyData as $day)
                        <tr><td>{{ $day['date'] }}</td><td>{{ $day['orders'] }}</td><td>{{ number_format($day['order_total'], 2) }}</td><td>{{ number_format($day['bed_total'], 2) }}</td><td class="fw-bold">{{ number_format($day['total'], 2) }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">{{ __('m.top_products') }}</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>{{ __('m.product_name') }}</th><th>{{ __('m.qty_sold') }}</th><th>{{ __('m.revenue') }}</th></tr></thead>
                    <tbody>
                    @foreach($topProducts as $tp)
                        <tr><td>{{ $tp->product->name }}</td><td>{{ $tp->total_qty }}</td><td class="fw-bold">{{ number_format($tp->total_revenue, 2) }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
