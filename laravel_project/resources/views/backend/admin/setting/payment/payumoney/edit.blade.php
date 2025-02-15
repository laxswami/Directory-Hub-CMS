@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('payumoney.edit-payumoney-setting') }}</h1>
            <p class="mb-4">{{ __('payumoney.edit-payumoney-setting-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.payment.payumoney.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-12">
                                @if($site_global_settings->setting_site_payumoney_enable == \App\Setting::SITE_PAYMENT_PAYUMONEY_ENABLE)
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-success text-white rounded">{{ __('payumoney.payumoney-enabled') }}</span>
                                @else
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-warning text-white rounded">{{ __('payumoney.payumoney-disabled') }}</span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_PAYMENT_PAYUMONEY_ENABLE }}" name="setting_site_payumoney_enable" type="checkbox" class="custom-control-input" id="setting_site_payumoney_enable" {{ (old('setting_site_payumoney_enable') ? old('setting_site_payumoney_enable') : $site_global_settings->setting_site_payumoney_enable) == \App\Setting::SITE_PAYMENT_PAYUMONEY_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_payumoney_enable">{{ __('payumoney.enable-payumoney') }}</label>
                                </div>
                                @error('setting_site_payumoney_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 col-sm-12">
                                <label class="text-black" for="setting_site_payumoney_mode">{{ __('payumoney.mode') }}</label>
                                <select class="custom-select @error('setting_site_payumoney_mode') is-invalid @enderror" name="setting_site_payumoney_mode">
                                    <option value="{{ \App\Setting::SITE_PAYMENT_PAYUMONEY_MODE_LIVE }}" {{ (old('setting_site_payumoney_mode') ? old('setting_site_payumoney_mode') : $site_global_settings->setting_site_payumoney_mode) == \App\Setting::SITE_PAYMENT_PAYUMONEY_MODE_LIVE ? 'selected' : '' }}>{{ __('payumoney.live') }}</option>
                                    <option value="{{ \App\Setting::SITE_PAYMENT_PAYUMONEY_MODE_TEST }}" {{ (old('setting_site_payumoney_mode') ? old('setting_site_payumoney_mode') : $site_global_settings->setting_site_payumoney_mode) == \App\Setting::SITE_PAYMENT_PAYUMONEY_MODE_TEST ? 'selected' : '' }}>{{ __('payumoney.test') }}</option>
                                </select>
                                @error('setting_site_payumoney_mode')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">

                                <label class="text-black" for="setting_site_payumoney_merchant_key">{{ __('payumoney.merchant-key') }}</label>
                                <input id="setting_site_payumoney_merchant_key" type="text" class="form-control @error('setting_site_payumoney_merchant_key') is-invalid @enderror" name="setting_site_payumoney_merchant_key" value="{{ old('setting_site_payumoney_merchant_key') ? old('setting_site_payumoney_merchant_key') : $site_global_settings->setting_site_payumoney_merchant_key }}">
                                @error('setting_site_payumoney_merchant_key')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">

                                <label class="text-black" for="setting_site_payumoney_salt">{{ __('payumoney.merchant-salt') }}</label>
                                <input id="setting_site_payumoney_salt" type="text" class="form-control @error('setting_site_payumoney_salt') is-invalid @enderror" name="setting_site_payumoney_salt" value="{{ old('setting_site_payumoney_salt') ? old('setting_site_payumoney_salt') : $site_global_settings->setting_site_payumoney_salt }}">
                                @error('setting_site_payumoney_salt')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
