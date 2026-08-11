@extends('layouts.app')
@section('content')
<div class="mb-4"><h5 class="fw-bold">{{ __('m.rental_history') }}</h5></div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('m.room_number') }}</th><th>{{ __('m.guest_name') }}</th><th>{{ __('m.check_in') }}</th><th>{{ __('m.check_out') }}</th><th>{{ __('m.nights') }}</th><th>{{ __('m.discount') }}</th><th>{{ __('m.total') }}</th><th>{{ __('m.payment_method') }}</th><th>{{ __('m.payment_status') }}</th></tr></thead>
            <tbody>
            @forelse($rentals as $r)
                <tr>
                    <td class="fw-semibold"><a href="{{ route('rentals.show', $r) }}">{{ $r->room->room_number }}</a></td>
                    <td>{{ $r->guest_name }}</td>
                    <td>{{ $r->check_in->format('M d, H:i') }}</td>
                    <td>{{ $r->check_out ? $r->check_out->format('M d, H:i') : '—' }}</td>
                    <td>{{ $r->nights }}</td>
                    <td>@if($r->discount_type !== 'none')<span class="text-success">-{{ number_format($r->discount_amount, 2) }}</span>@else — @endif</td>
                    <td class="fw-bold">{{ number_format($r->total_price, 2) }}</td>
                    <td>{{ __('m.'.$r->payment_method) }}</td>
                    <td><span class="badge {{ $r->payment_status==='paid'?'bg-success':'bg-warning' }}">{{ __('m.'.$r->payment_status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $rentals->links() }}</div>
@endsection
