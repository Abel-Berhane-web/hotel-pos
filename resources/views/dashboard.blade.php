@extends('layouts.app')
@section('content')
@php $user = auth()->user(); @endphp

{{-- Admin / Manager Dashboard --}}
@if($user->isAdmin() || $user->isManager())

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
        <div class="page-breadcrumb"><i class="bi bi-house-door me-1"></i> Home <i class="bi bi-chevron-right mx-1"></i> {{ __('m.dashboard') }} <i class="bi bi-chevron-right mx-1"></i> Overview</div>
        <h1 class="page-heading">{{ __('m.dashboard') }}</h1>
        <p class="page-subtitle">Today's hotel performance</p>
    </div>
</div>

{{-- KPI Row --}}
<div class="row g-3 mb-3">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">{{ __('m.todays_revenue') }} <i class="bi bi-cash-coin"></i></div>
            <div class="stat-value">{{ number_format($data['todays_revenue'] ?? 0, 2) }}</div>
            <span class="stat-unit">{{ __('m.etb') }}</span>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">{{ __('m.todays_orders') }} <i class="bi bi-cart3"></i></div>
            <div class="stat-value">{{ $data['todays_orders'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">{{ __('m.rooms_available') }} <i class="bi bi-door-open"></i></div>
            <div class="stat-value" style="color:#10b981;">{{ $data['rooms_available'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-label">{{ __('m.rooms_occupied') }} <i class="bi bi-door-closed"></i></div>
            <div class="stat-value" style="color:#ef4444;">{{ $data['rooms_occupied'] }}</div>
        </div>
    </div>
</div>

{{-- Sales Overview + Payment Methods --}}
<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart-line me-2"></i>Sales Overview</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-4 text-center">
                        <div style="font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:4px;">{{ __('m.drink_sales') }}</div>
                        <div style="font-size:18px;font-weight:700;">{{ number_format($data['drink_sales'] ?? 0, 2) }} {{ __('m.etb') }}</div>
                    </div>
                    <div class="col-4 text-center">
                        <div style="font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:4px;">{{ __('m.food_sales') }}</div>
                        <div style="font-size:18px;font-weight:700;">{{ number_format($data['food_sales'] ?? 0, 2) }} {{ __('m.etb') }}</div>
                    </div>
                    <div class="col-4 text-center">
                        <div style="font-size:13px;font-weight:600;color:var(--text-secondary);margin-bottom:4px;">{{ __('m.bed_income') }}</div>
                        <div style="font-size:18px;font-weight:700;">{{ number_format($data['bed_income'] ?? 0, 2) }} {{ __('m.etb') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-credit-card me-2"></i>{{ __('m.payment_method') }}</div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($data['payment_breakdown'] ?? [] as $method => $amount)
                    <div class="col text-center">
                        <div style="font-size:12px;font-weight:600;color:var(--text-secondary);margin-bottom:4px;">{{ __('m.'.$method) }}</div>
                        <div style="font-size:15px;font-weight:700;">{{ number_format($amount, 2) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders + Room Activity --}}
<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                {{ __('m.recent_orders') }}
                <a href="{{ route('orders.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>{{ __('m.order_number') }}</th><th>{{ __('m.employee') }}</th><th>{{ __('m.total') }}</th><th>{{ __('m.payment_method') }}</th></tr></thead>
                    <tbody>
                    @forelse($data['recent_orders'] ?? [] as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a></td>
                            <td style="color:var(--text-secondary);">{{ $order->employee?->name ?? '-' }}</td>
                            <td class="fw-bold">{{ number_format($order->total, 2) }}</td>
                            <td><span class="badge badge-{{ $order->payment_method }}">{{ __('m.'.$order->payment_method) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-3" style="color:var(--text-muted);">{{ __('m.no_data') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                Room Activity
                <a href="{{ route('rentals.index') }}" class="view-all">View all</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>{{ __('m.room_number') }}</th><th>{{ __('m.guest_name') }}</th><th>{{ __('m.total') }}</th><th>{{ __('m.payment_method') }}</th></tr></thead>
                    <tbody>
                    @forelse($data['recent_rentals'] ?? [] as $rental)
                        <tr>
                            <td><a href="{{ route('rentals.show', $rental) }}">{{ $rental->room->room_number }}</a></td>
                            <td style="color:var(--text-secondary);">{{ $rental->guest_name }}</td>
                            <td class="fw-bold">{{ number_format($rental->total_price, 2) }}</td>
                            <td><span class="badge badge-{{ $rental->payment_method }}">{{ __('m.'.$rental->payment_method) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-3" style="color:var(--text-muted);">{{ __('m.no_data') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Low Stock --}}
@if(($data['low_stock'] ?? collect())->isNotEmpty())
<div class="card">
    <div class="card-header"><i class="bi bi-exclamation-triangle text-danger me-2"></i>{{ __('m.low_stock_alerts') }}</div>
    <div class="card-body p-0">
        <table class="table table-sm">
            <tbody>
            @foreach($data['low_stock'] as $product)
                <tr>
                    <td class="ps-3">{{ $product->name }}</td>
                    <td class="text-end pe-3"><span class="badge bg-danger">{{ $product->stock_quantity }}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endif

{{-- Cashier Dashboard --}}
@if($user->isCashier())
<div class="mb-3">
    <div class="page-breadcrumb"><i class="bi bi-house-door me-1"></i> Home <i class="bi bi-chevron-right mx-1"></i> {{ __('m.dashboard') }}</div>
    <h1 class="page-heading">{{ __('m.dashboard') }}</h1>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-label">{{ __('m.my_orders_today') }}</div>
            <div class="stat-value">{{ $data['my_orders_today'] ?? 0 }}</div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-label">{{ __('m.my_sales_today') }}</div>
            <div class="stat-value">{{ number_format($data['my_sales_today'] ?? 0, 2) }}</div>
            <span class="stat-unit">{{ __('m.etb') }}</span>
        </div>
    </div>
    <div class="col-md-4 d-flex align-items-stretch">
        <a href="{{ route('orders.create') }}" class="stat-card w-100 d-flex flex-column align-items-center justify-content-center" style="background:var(--accent);color:#fff;border-color:var(--accent);text-decoration:none;">
            <i class="bi bi-plus-circle" style="font-size:1.6rem;"></i>
            <div class="fw-bold mt-1" style="font-size:13px;">{{ __('m.new_order') }}</div>
        </a>
    </div>
</div>
@endif

{{-- Receptionist Dashboard --}}
@if($user->isReceptionist())
<div class="mb-3">
    <div class="page-breadcrumb"><i class="bi bi-house-door me-1"></i> Home <i class="bi bi-chevron-right mx-1"></i> {{ __('m.dashboard') }}</div>
    <h1 class="page-heading">{{ __('m.dashboard') }}</h1>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-label">{{ __('m.rooms_available') }}</div><div class="stat-value" style="color:#10b981;">{{ $data['rooms_available'] }}</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-label">{{ __('m.rooms_occupied') }}</div><div class="stat-value" style="color:#ef4444;">{{ $data['rooms_occupied'] }}</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-label">{{ __('m.todays_checkins') }}</div><div class="stat-value">{{ $data['todays_checkins'] ?? 0 }}</div></div></div>
    <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-label">{{ __('m.bed_income_today') }}</div><div class="stat-value">{{ number_format($data['bed_income_today'] ?? 0, 2) }}</div><span class="stat-unit">{{ __('m.etb') }}</span></div></div>
</div>
<div class="text-center mb-3">
    <a href="{{ route('rooms.index') }}" class="btn btn-primary px-4"><i class="bi bi-door-open me-2"></i>{{ __('m.rooms') }}</a>
</div>
@if(!empty($data['occupied_rooms']))
<div class="card">
    <div class="card-header">{{ __('m.occupied_rooms') }}</div>
    <div class="card-body p-0">
        <table class="table">
            <thead><tr><th>{{ __('m.room_number') }}</th><th>{{ __('m.guest_name') }}</th><th>{{ __('m.check_in') }}</th></tr></thead>
            <tbody>
            @foreach($data['occupied_rooms'] as $room)
                <tr><td>{{ $room->room_number }}</td><td>{{ $room->currentRental?->guest_name ?? '-' }}</td><td>{{ $room->currentRental?->check_in?->format('M d, H:i') }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endif

{{-- Employee Dashboard --}}
@if($user->isEmployee())
<div class="mb-3">
    <div class="page-breadcrumb"><i class="bi bi-house-door me-1"></i> Home <i class="bi bi-chevron-right mx-1"></i> {{ __('m.dashboard') }}</div>
    <h1 class="page-heading">{{ __('m.dashboard') }}</h1>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="stat-card text-center">
            <div class="stat-label">{{ __('m.my_assigned_orders') }}</div>
            <div class="stat-value">{{ ($data['my_assigned_orders'] ?? collect())->count() }}</div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">{{ __('m.my_assigned_orders') }} — {{ __('m.today') }}</div>
    <div class="card-body p-0">
        <table class="table">
            <thead><tr><th>{{ __('m.order_number') }}</th><th>{{ __('m.time') }}</th><th>{{ __('m.items') }}</th><th>{{ __('m.total') }}</th></tr></thead>
            <tbody>
            @forelse($data['my_assigned_orders'] ?? [] as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('H:i') }}</td>
                    <td>{{ $order->items->count() }}</td>
                    <td class="fw-bold">{{ number_format($order->total, 2) }} {{ __('m.etb') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-3" style="color:var(--text-muted);">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
