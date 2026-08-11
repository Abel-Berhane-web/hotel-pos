@extends('layouts.app')
@section('content')
<div class="mb-4">
    <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>{{ __('m.back') }}</a>
    <h5 class="fw-bold">{{ __('m.room_number') }}: {{ $rental->room->room_number }}</h5>
</div>
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('m.rental_history') }}</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr><td class="text-muted w-25">{{ __('m.room_number') }}</td><td class="fw-bold">{{ $rental->room->room_number }} ({{ $rental->room->name }})</td></tr>
                        <tr><td class="text-muted">{{ __('m.guest_name') }}</td><td>{{ $rental->guest_name }}</td></tr>
                        <tr><td class="text-muted">{{ __('m.guest_phone') }}</td><td>{{ $rental->guest_phone ?? '-' }}</td></tr>
                        <tr><td class="text-muted">{{ __('m.nights') }}</td><td>{{ $rental->nights }}</td></tr>
                        <tr><td class="text-muted">{{ __('m.check_in') }}</td><td>{{ $rental->check_in?->format('M d, Y H:i') }}</td></tr>
                        <tr><td class="text-muted">{{ __('m.check_out') }}</td><td>{{ $rental->check_out?->format('M d, Y H:i') ?? '-' }}</td></tr>
                        @if($rental->note)<tr><td class="text-muted">{{ __('m.note') }}</td><td>{{ $rental->note }}</td></tr>@endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">{{ __('m.payment_method') }}</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('m.original_price') }}</span>
                    <span>{{ number_format($rental->original_price, 2) }}</span>
                </div>
                @if($rental->discount_amount > 0)
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>{{ __('m.discount_amount') }} ({{ $rental->discount_type === 'percentage' ? $rental->discount_value.'%' : __('m.fixed_amount') }})</span>
                    <span>-{{ number_format($rental->discount_amount, 2) }}</span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold" style="font-size:1.1rem;">{{ __('m.total') }}</span>
                    <span class="fw-bold text-primary" style="font-size:1.1rem;">{{ number_format($rental->total_price, 2) }} {{ __('m.etb') }}</span>
                </div>
                
                <p class="mb-1"><strong>{{ __('m.payment_method') }}:</strong> <span class="badge badge-{{ $rental->payment_method === 'cash' ? 'cash' : ($rental->payment_method === 'bank_transfer' ? 'bank' : ($rental->payment_method === 'telebirr' ? 'telebirr' : ($rental->payment_method === 'cbe_birr' ? 'cbe' : 'credit'))) }}">{{ __('m.'.$rental->payment_method) }}</span></p>
                <p class="mb-1"><strong>{{ __('m.payment_status') }}:</strong>
                    @if($rental->payment_status === 'pending')
                        <span class="badge bg-danger">{{ __('m.pending') }}</span>
                    @else
                        <span class="badge bg-success">{{ __('m.paid') }}</span>
                    @endif
                </p>
                <p class="mb-0 mt-3 text-muted" style="font-size:0.85rem;"><strong>{{ __('m.receptionist') }}:</strong> {{ $rental->receptionist->name }}</p>
            </div>
        </div>
        
        @if($rental->payment_status === 'pending')
        <form method="POST" action="{{ route('rooms.confirm_payment', $rental) }}">
            @csrf
            <button class="btn btn-success w-100 fw-bold"><i class="bi bi-check2-all me-1"></i> Confirm Payment</button>
        </form>
        @endif
    </div>
</div>
@endsection
