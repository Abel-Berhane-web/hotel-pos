@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ __('m.rooms') }}</h5>
    <div class="d-flex gap-2">
        @if(auth()->user()->isAdmin() || auth()->user()->isReceptionist())
        <a href="{{ route('rooms.manage') }}" class="btn btn-outline-secondary"><i class="bi bi-gear me-1"></i>{{ __('m.manage_rooms') }}</a>
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-info"><i class="bi bi-calendar-event me-1"></i>{{ __('m.reservations') }}</a>
        @endif
        <a href="{{ route('rentals.index') }}" class="btn btn-outline-primary"><i class="bi bi-clock-history me-1"></i>{{ __('m.rental_history') }}</a>
    </div>
</div>

<div class="row g-3">
@forelse($rooms as $room)
    <div class="col-md-3 col-sm-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="fw-bold">{{ $room->room_number }}</h5>
                @if($room->name)<p class="text-muted mb-2" style="font-size:.85rem;">{{ $room->name }}</p>@endif
                <span class="badge badge-{{ $room->status }} mb-2" style="font-size:.8rem;">{{ __('m.'.$room->status) }}</span>
                <p class="fw-bold text-primary mb-2">{{ number_format($room->price_per_night, 2) }} {{ __('m.etb') }}/{{ __('m.nights') }}</p>

                @if($room->isOccupied() && $room->currentRental)
                    <div class="rounded p-2 mb-2" style="font-size:.8rem;background:var(--bg-app, #f8f9fa);">
                        <div class="fw-semibold">{{ $room->currentRental->guest_name }}</div>
                        <div class="text-muted">{{ $room->currentRental->check_in->format('M d, H:i') }}</div>
                        @if($room->currentRental->payment_status === 'pending')
                            <div class="text-danger fw-bold mt-1"><i class="bi bi-exclamation-circle"></i> {{ __('m.pending') }}</div>
                        @else
                            <div class="text-success fw-bold mt-1"><i class="bi bi-check-circle"></i> {{ __('m.paid') }}</div>
                        @endif
                    </div>
                    @if($room->currentRental->payment_status === 'pending')
                    <form method="POST" action="{{ route('rooms.confirm_payment', $room->currentRental) }}" class="mb-2">
                        @csrf
                        <button class="btn btn-sm btn-success w-100 fw-bold"><i class="bi bi-check2-all me-1"></i> Confirm Payment</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('rooms.checkout', $room) }}" onsubmit="return confirm('{{ __('m.confirm') }}')">
                        @csrf
                        <button class="btn btn-sm btn-warning w-100 fw-bold"><i class="bi bi-box-arrow-right me-1"></i>{{ __('m.check_out') }}</button>
                    </form>
                @elseif($room->isAvailable())
                    <button class="btn btn-sm btn-success w-100" data-bs-toggle="modal" data-bs-target="#rentModal{{ $room->id }}">
                        <i class="bi bi-door-open me-1"></i>{{ __('m.rent_bed') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Rent Modal --}}
    @if($room->isAvailable())
    <div class="modal fade" id="rentModal{{ $room->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h6 class="modal-title fw-bold">{{ __('m.rent_bed') }}: {{ $room->room_number }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="{{ route('rooms.rent', $room) }}">@csrf
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">{{ __('m.guest_name') }} *</label><input type="text" name="guest_name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.guest_phone') }}</label><input type="text" name="guest_phone" class="form-control"></div>
            
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="is_reservation" value="1" id="isRes{{ $room->id }}" onchange="toggleRes{{ $room->id }}()">
                <label class="form-check-label fw-bold text-primary" for="isRes{{ $room->id }}">{{ __('m.future_reservation') }}</label>
            </div>
            <div class="mb-3 d-none" id="checkInDateGroup{{ $room->id }}">
                <label class="form-label">{{ __('m.check_in_date') }} *</label>
                <input type="date" name="check_in_date" class="form-control" id="checkInInput{{ $room->id }}">
            </div>

            <div class="mb-3"><label class="form-label">{{ __('m.nights') }} *</label><input type="number" name="nights" class="form-control" value="1" min="1" required id="nights{{ $room->id }}" onchange="calcDiscount{{ $room->id }}()"></div>

            <hr>
            <p class="fw-semibold mb-2">{{ __('m.discount') }}</p>
            <div class="mb-2">
                <div class="form-check form-check-inline"><input type="radio" name="discount_type" value="none" class="form-check-input" id="dNone{{ $room->id }}" checked onchange="calcDiscount{{ $room->id }}()"><label class="form-check-label" for="dNone{{ $room->id }}">{{ __('m.no_discount') }}</label></div>
                <div class="form-check form-check-inline"><input type="radio" name="discount_type" value="percentage" class="form-check-input" id="dPct{{ $room->id }}" onchange="calcDiscount{{ $room->id }}()"><label class="form-check-label" for="dPct{{ $room->id }}">{{ __('m.percentage') }} %</label></div>
                <div class="form-check form-check-inline"><input type="radio" name="discount_type" value="fixed" class="form-check-input" id="dFixed{{ $room->id }}" onchange="calcDiscount{{ $room->id }}()"><label class="form-check-label" for="dFixed{{ $room->id }}">{{ __('m.fixed_amount') }}</label></div>
            </div>
            <div class="mb-3"><label class="form-label">{{ __('m.discount_value') }}</label><input type="number" step="0.01" name="discount_value" class="form-control" value="0" id="dVal{{ $room->id }}" onkeyup="calcDiscount{{ $room->id }}()"></div>
            <div class="bg-light p-2 rounded" style="font-size:.85rem;" id="priceCalc{{ $room->id }}">
                <div>{{ __('m.original_price') }}: <strong id="origPrice{{ $room->id }}">{{ number_format($room->price_per_night, 2) }}</strong></div>
                <div>{{ __('m.discount_amount') }}: <strong id="discAmt{{ $room->id }}">0.00</strong></div>
                <div>{{ __('m.final_price') }}: <strong class="text-primary" id="finalPrice{{ $room->id }}">{{ number_format($room->price_per_night, 2) }}</strong></div>
            </div>
            <hr>

            <div class="mb-3"><label class="form-label">{{ __('m.payment_method') }}</label>
                <select name="payment_method" class="form-select">
                    @foreach(['cash','bank_transfer','telebirr','cbe_birr','credit'] as $pm)
                    <option value="{{ $pm }}">{{ __('m.'.$pm) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3"><label class="form-label">{{ __('m.payment_status') }}</label>
                <select name="payment_status" class="form-select"><option value="paid">{{ __('m.paid') }}</option><option value="pending">{{ __('m.pending') }}</option></select>
            </div>
            <div class="mb-3"><label class="form-label">{{ __('m.note') }}</label><input type="text" name="note" class="form-control"></div>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">{{ __('m.rent_bed') }}</button></div>
        </form>
    </div></div></div>

    <script>
    function calcDiscount{{ $room->id }}() {
        const ppn = {{ $room->price_per_night }};
        const nights = parseInt(document.getElementById('nights{{ $room->id }}').value) || 1;
        const orig = ppn * nights;
        const dType = document.querySelector('input[name="discount_type"]:checked')?.value || 'none';
        const dVal = parseFloat(document.getElementById('dVal{{ $room->id }}').value) || 0;
        let disc = 0;
        if (dType === 'percentage') disc = orig * dVal / 100;
        else if (dType === 'fixed') disc = Math.min(dVal, orig);
        document.getElementById('origPrice{{ $room->id }}').textContent = orig.toFixed(2);
        document.getElementById('discAmt{{ $room->id }}').textContent = disc.toFixed(2);
        document.getElementById('finalPrice{{ $room->id }}').textContent = (orig - disc).toFixed(2);
    }
    function toggleRes{{ $room->id }}() {
        const isRes = document.getElementById('isRes{{ $room->id }}').checked;
        const dateGroup = document.getElementById('checkInDateGroup{{ $room->id }}');
        const dateInput = document.getElementById('checkInInput{{ $room->id }}');
        if (isRes) {
            dateGroup.classList.remove('d-none');
            dateInput.required = true;
        } else {
            dateGroup.classList.add('d-none');
            dateInput.required = false;
        }
    }
    </script>
    @endif
@empty
    <div class="col-12 text-center text-muted py-5"><i class="bi bi-door-closed" style="font-size:3rem;"></i><p class="mt-2">{{ __('m.no_data') }}</p></div>
@endforelse
</div>
@endsection
