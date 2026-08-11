@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ __('m.manage_rooms') }}</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal"><i class="bi bi-plus me-1"></i>{{ __('m.add_room') }}</button>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('m.room_number') }}</th><th>{{ __('m.name_en') }}</th><th>{{ __('m.name_am') }}</th><th>{{ __('m.price_per_night') }}</th><th>{{ __('m.status') }}</th><th>{{ __('m.actions') }}</th></tr></thead>
            <tbody>
            @forelse($rooms as $room)
                <tr>
                    <td class="fw-bold">{{ $room->room_number }}</td><td>{{ $room->name_en }}</td><td>{{ $room->name_am }}</td>
                    <td>{{ number_format($room->price_per_night, 2) }}</td>
                    <td><span class="badge badge-{{ $room->status }}">{{ __('m.'.$room->status) }}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editRoom{{ $room->id }}"><i class="bi bi-pencil"></i></button>
                        <form method="POST" action="{{ route('rooms.destroy', $room) }}" class="d-inline" onsubmit="return confirm('{{ __('m.confirm') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                    </td>
                </tr>
                <!-- Edit Modal -->
                <div class="modal fade" id="editRoom{{ $room->id }}" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
                    <div class="modal-header"><h6 class="modal-title fw-bold">{{ __('m.edit_room') }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <form method="POST" action="{{ route('rooms.update', $room) }}">@csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">{{ __('m.room_number') }} *</label><input type="text" name="room_number" class="form-control" value="{{ $room->room_number }}" required></div>
                        <div class="mb-3"><label class="form-label">{{ __('m.name_en') }}</label><input type="text" name="name_en" class="form-control" value="{{ $room->name_en }}"></div>
                        <div class="mb-3"><label class="form-label">{{ __('m.name_am') }}</label><input type="text" name="name_am" class="form-control" value="{{ $room->name_am }}"></div>
                        <div class="mb-3"><label class="form-label">{{ __('m.price_per_night') }} *</label><input type="number" step="0.01" name="price_per_night" class="form-control" value="{{ $room->price_per_night }}" required></div>
                        <div class="mb-3"><label class="form-label">{{ __('m.status') }}</label><select name="status" class="form-select"><option value="available" {{ $room->status==='available'?'selected':'' }}>{{ __('m.available') }}</option><option value="occupied" {{ $room->status==='occupied'?'selected':'' }}>{{ __('m.occupied') }}</option><option value="maintenance" {{ $room->status==='maintenance'?'selected':'' }}>{{ __('m.maintenance') }}</option></select></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">{{ __('m.save') }}</button></div>
                    </form>
                </div></div></div>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Room Modal -->
<div class="modal fade" id="addRoomModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h6 class="modal-title fw-bold">{{ __('m.add_room') }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST" action="{{ route('rooms.store') }}">@csrf
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">{{ __('m.room_number') }} *</label><input type="text" name="room_number" class="form-control" placeholder="B1" required></div>
        <div class="mb-3"><label class="form-label">{{ __('m.name_en') }}</label><input type="text" name="name_en" class="form-control"></div>
        <div class="mb-3"><label class="form-label">{{ __('m.name_am') }}</label><input type="text" name="name_am" class="form-control"></div>
        <div class="mb-3"><label class="form-label">{{ __('m.price_per_night') }} *</label><input type="number" step="0.01" name="price_per_night" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">{{ __('m.status') }}</label><select name="status" class="form-select"><option value="available">{{ __('m.available') }}</option><option value="maintenance">{{ __('m.maintenance') }}</option></select></div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">{{ __('m.save') }}</button></div>
    </form>
</div></div></div>
@endsection
