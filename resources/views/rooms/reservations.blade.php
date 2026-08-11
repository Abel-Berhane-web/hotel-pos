@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ __('m.reservations') }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>{{ __('m.back') }}</a>
        <a href="{{ route('rooms.index') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>New Reservation</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>{{ __('m.room_number') }}</th>
                        <th>{{ __('m.guest_name') }}</th>
                        <th>{{ __('m.check_in_date') }}</th>
                        <th>{{ __('m.nights') }}</th>
                        <th>{{ __('m.payment_status') }}</th>
                        <th class="text-end">{{ __('m.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $rental)
                    <tr>
                        <td>
                            <div class="fw-bold text-primary">{{ $rental->room->room_number }}</div>
                            <div class="text-muted" style="font-size:0.85rem;">{{ $rental->room->name }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $rental->guest_name }}</div>
                            <div class="text-muted" style="font-size:0.85rem;">{{ $rental->guest_phone ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold {{ $rental->check_in->isToday() ? 'text-success' : '' }}">
                                {{ $rental->check_in->format('M d, Y') }}
                                @if($rental->check_in->isToday()) <span class="badge bg-success ms-1">Today</span> @endif
                            </div>
                        </td>
                        <td>{{ $rental->nights }}</td>
                        <td>
                            <span class="badge badge-{{ $rental->payment_status === 'paid' ? 'cash' : 'credit' }}">
                                {{ ucfirst($rental->payment_status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <form method="POST" action="{{ route('reservations.check_in', $rental) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success fw-bold" {{ !$rental->check_in->isToday() && !$rental->check_in->isPast() ? 'disabled' : '' }}>
                                        <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('m.check_in') }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('reservations.cancel', $rental) }}" onsubmit="return confirm('{{ __('m.confirm') }}')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" title="{{ __('m.cancel_reservation') }}"><i class="bi bi-x-circle"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x" style="font-size:2rem;"></i>
                            <p class="mt-2">{{ __('m.no_upcoming_reservations') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    {{ $reservations->links() }}
</div>
@endsection
