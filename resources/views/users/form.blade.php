@extends('layouts.app')
@section('content')
<a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i>{{ __('m.back') }}</a>
<div class="card" style="max-width:500px;">
    <div class="card-header fw-bold">{{ $user->exists ? __('m.edit') : __('m.add') }} {{ __('m.user') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">
            @csrf @if($user->exists) @method('PUT') @endif
            <div class="mb-3"><label class="form-label">{{ __('m.name') }} *</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.email') }} *</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.password') }} {{ $user->exists ? '(leave blank to keep)' : '*' }}</label><input type="password" name="password" class="form-control" {{ $user->exists ? '' : 'required' }}></div>
            <div class="mb-3"><label class="form-label">{{ __('m.role') }} *</label>
                <select name="role" class="form-select" required>
                    @foreach(['admin','manager','cashier','receptionist','employee'] as $role)
                    <option value="{{ $role }}" {{ old('role', $user->role)===$role?'selected':'' }}>{{ __('m.'.$role) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $user->is_active ?? true)?'checked':'' }}><label class="form-check-label" for="isActive">{{ __('m.active') }}</label></div>
            <button type="submit" class="btn btn-primary">{{ __('m.save') }}</button>
        </form>
    </div>
</div>
@endsection
