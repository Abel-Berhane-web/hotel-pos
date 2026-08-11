@extends('layouts.app')
@section('content')
<h5 class="fw-bold mb-4">{{ __('m.audit_log') }}</h5>
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2"><label class="form-label" style="font-size:.8rem;">{{ __('m.from_date') }}</label><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
            <div class="col-md-2"><label class="form-label" style="font-size:.8rem;">{{ __('m.to_date') }}</label><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
            <div class="col-md-2"><label class="form-label" style="font-size:.8rem;">{{ __('m.user') }}</label>
                <select name="user_id" class="form-select form-select-sm"><option value="">{{ __('m.all') }}</option>@foreach($users as $u)<option value="{{ $u->id }}" {{ request('user_id')==$u->id?'selected':'' }}>{{ $u->name }}</option>@endforeach</select>
            </div>
            <div class="col-md-2"><label class="form-label" style="font-size:.8rem;">{{ __('m.action') }}</label><input type="text" name="action" class="form-control form-control-sm" value="{{ request('action') }}"></div>
            <div class="col-md-2"><button class="btn btn-sm btn-primary w-100">{{ __('m.filter') }}</button></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead><tr><th>{{ __('m.date') }}</th><th>{{ __('m.user') }}</th><th>{{ __('m.action') }}</th><th>{{ __('m.details') }}</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td style="font-size:.85rem;">{{ $log->created_at->format('M d H:i') }}</td>
                    <td>{{ $log->user?->name ?? '—' }}</td>
                    <td><span class="badge bg-light text-dark">{{ $log->action }}</span></td>
                    <td style="font-size:.85rem;">{{ $log->details }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ __('m.no_data') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $logs->withQueryString()->links() }}</div>
@endsection
