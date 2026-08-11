@extends('layouts.app')
@section('content')
<h5 class="fw-bold mb-4">{{ __('m.settings') }}</h5>
<div class="card" style="max-width:600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            <div class="mb-3"><label class="form-label">{{ __('m.business_name_en') }}</label><input type="text" name="business_name_en" class="form-control" value="{{ $settings['business_name_en'] }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.business_name_am') }}</label><input type="text" name="business_name_am" class="form-control" value="{{ $settings['business_name_am'] }}" required></div>
            <div class="mb-3"><label class="form-label">{{ __('m.business_phone') }}</label><input type="text" name="business_phone" class="form-control" value="{{ $settings['business_phone'] }}"></div>
            <div class="row mb-3">
                <div class="col-6"><label class="form-label">{{ __('m.currency') }}</label><input type="text" name="currency" class="form-control" value="{{ $settings['currency'] }}" required></div>
                <div class="col-6"><label class="form-label">{{ __('m.tax_rate') }}</label><input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ $settings['tax_rate'] }}" required></div>
            </div>
            <div class="mb-3"><label class="form-label">{{ __('m.low_stock_threshold') }}</label><input type="number" name="low_stock_threshold" class="form-control" value="{{ $settings['low_stock_threshold'] }}" required></div>
            <button type="submit" class="btn btn-primary">{{ __('m.save') }}</button>
        </form>
    </div>
</div>
@endsection
