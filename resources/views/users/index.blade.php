@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">{{ __('m.users') }}</h5>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-plus me-1"></i>{{ __('m.add') }}</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>{{ __('m.name') }}</th><th>{{ __('m.email') }}</th><th>{{ __('m.role') }}</th><th>{{ __('m.status') }}</th><th>{{ __('m.actions') }}</th></tr></thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-primary">{{ __('m.'.$user->role) }}</span></td>
                    <td><span class="badge {{ $user->is_active?'bg-success':'bg-secondary' }}">{{ $user->is_active?__('m.active'):__('m.inactive') }}</span></td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('{{ __('m.confirm') }}')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-person-x"></i></button></form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
