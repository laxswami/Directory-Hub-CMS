@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('paypal.edit-paypal-setting') }}</h1>
            <p class="mb-4">{{ __('paypal.edit-paypal-setting-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.payment.paypal.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-12">
                                @if($all_paypal_settings->setting_site_paypal_enable == \App\Setting::SITE_PAYMENT_PAYPAL_ENABLE)
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-success text-white rounded">{{ __('paypal.paypal-enabled') }}</span>
                                @else
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-warning text-white rounded">{{ __('paypal.paypal-disabled') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <span>{{ __('payment.paypal-ipn') }}: {{ route('user.paypal.notify') }}</span>
                            </div>
                        </div>
                        <hr>

                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_PAYMENT_PAYPAL_ENABLE }}" name="setting_site_paypal_enable" type="checkbox" class="custom-control-input" id="setting_site_paypal_enable" {{ (old('setting_site_paypal_enable') ? old('setting_site_paypal_enable') : $all_paypal_settings->setting_site_paypal_enable) == \App\Setting::SITE_PAYMENT_PAYPAL_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_paypal_enable">{{ __('paypal.enable-paypal') }}</label>
                                </div>
                                @error('setting_site_paypal_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_paypal_mode">{{ __('backend.setting.mode') }}</label>
                                <select class="custom-select @error('setting_site_paypal_mode') is-invalid @enderror" name="setting_site_paypal_mode">
                                    <option value="{{ \App\Setting::SITE_PAYMENT_PAYPAL_SANDBOX }}" {{ (old('setting_site_paypal_mode') ? old('setting_site_paypal_mode') : $all_paypal_settings->setting_site_paypal_mode) == \App\Setting::SITE_PAYMENT_PAYPAL_SANDBOX ? 'selected' : '' }}>{{ __('paypal.paypal-sandbox') }}</option>
                                    <option value="{{ \App\Setting::SITE_PAYMENT_PAYPAL_LIVE }}" {{ (old('setting_site_paypal_mode') ? old('setting_site_paypal_mode') : $all_paypal_settings->setting_site_paypal_mode) == \App\Setting::SITE_PAYMENT_PAYPAL_LIVE ? 'selected' : '' }}>{{ __('paypal.paypal-live') }}</option>
                                </select>
                                @error('setting_site_paypal_mode')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_paypal_currency">{{ __('backend.setting.currency') }}</label>
                                <select class="custom-select @error('setting_site_paypal_currency') is-invalid @enderror" name="setting_site_paypal_currency">
                                    <option value="USD" {{ $all_paypal_settings->setting_site_paypal_currency == 'USD' ? 'selected' : '' }}>United States dollar - USD</option>
                                    <option value="AUD" {{ $all_paypal_settings->setting_site_paypal_currency == 'AUD' ? 'selected' : '' }}>Australian dollar - AUD</option>
                                    <option value="BRL" {{ $all_paypal_settings->setting_site_paypal_currency == 'BRL' ? 'selected' : '' }}>Brazilian real - BRL</option>
                                    <option value="CAD" {{ $all_paypal_settings->setting_site_paypal_currency == 'CAD' ? 'selected' : '' }}>Canadian dollar - CAD</option>
                                    <option value="CZK" {{ $all_paypal_settings->setting_site_paypal_currency == 'CZK' ? 'selected' : '' }}>Czech koruna - CZK</option>
                                    <option value="DKK" {{ $all_paypal_settings->setting_site_paypal_currency == 'DKK' ? 'selected' : '' }}>Danish krone - DKK</option>
                                    <option value="EUR" {{ $all_paypal_settings->setting_site_paypal_currency == 'EUR' ? 'selected' : '' }}>Euro - EUR</option>
                                    <option value="HKD" {{ $all_paypal_settings->setting_site_paypal_currency == 'HKD' ? 'selected' : '' }}>Hong Kong dollar - HKD</option>
                                    <option value="INR" {{ $all_paypal_settings->setting_site_paypal_currency == 'INR' ? 'selected' : '' }}>Indian rupee - INR</option>
                                    <option value="ILS" {{ $all_paypal_settings->setting_site_paypal_currency == 'ILS' ? 'selected' : '' }}>Israeli new shekel - ILS</option>
                                    <option value="MXN" {{ $all_paypal_settings->setting_site_paypal_currency == 'MXN' ? 'selected' : '' }}>Mexican peso - MXN</option>
                                    <option value="NZD" {{ $all_paypal_settings->setting_site_paypal_currency == 'NZD' ? 'selected' : '' }}>New Zealand dollar - NZD</option>
                                    <option value="NOK" {{ $all_paypal_settings->setting_site_paypal_currency == 'NOK' ? 'selected' : '' }}>Norwegian krone - MOK</option>
                                    <option value="PHP" {{ $all_paypal_settings->setting_site_paypal_currency == 'PHP' ? 'selected' : '' }}>Philippine peso - PHP</option>
                                    <option value="PLN" {{ $all_paypal_settings->setting_site_paypal_currency == 'PLN' ? 'selected' : '' }}>Polish złoty - PLN</option>
                                    <option value="GBP" {{ $all_paypal_settings->setting_site_paypal_currency == 'GBP' ? 'selected' : '' }}>Pound sterling - GBP</option>
                                    <option value="RUB" {{ $all_paypal_settings->setting_site_paypal_currency == 'RUB' ? 'selected' : '' }}>Russian ruble - RUB</option>
                                    <option value="SGD" {{ $all_paypal_settings->setting_site_paypal_currency == 'SGD' ? 'selected' : '' }}>Singapore dollar - SGD</option>
                                    <option value="SEK" {{ $all_paypal_settings->setting_site_paypal_currency == 'SEK' ? 'selected' : '' }}>Swedish krona - SEK</option>
                                    <option value="CHF" {{ $all_paypal_settings->setting_site_paypal_currency == 'CHF' ? 'selected' : '' }}>Swiss franc - CHF</option>
                                    <option value="THB" {{ $all_paypal_settings->setting_site_paypal_currency == 'THB' ? 'selected' : '' }}>Thai baht - THB</option>
                                </select>
                                @error('setting_site_paypal_currency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_paypal_sandbox_username">{{ __('backend.setting.sandbox-username') }}</label>
                                <input id="setting_site_paypal_sandbox_username" type="text" class="form-control @error('setting_site_paypal_sandbox_username') is-invalid @enderror" name="setting_site_paypal_sandbox_username" value="{{ old('setting_site_paypal_sandbox_username') ? old('setting_site_paypal_sandbox_username') : $all_paypal_settings->setting_site_paypal_sandbox_username }}">
                                @error('setting_site_paypal_sandbox_username')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_paypal_sandbox_password">{{ __('backend.setting.sandbox-password') }}</label>
                                <input id="setting_site_paypal_sandbox_password" type="text" class="form-control @error('setting_site_paypal_sandbox_password') is-invalid @enderror" name="setting_site_paypal_sandbox_password" value="{{ old('setting_site_paypal_sandbox_password') ? old('setting_site_paypal_sandbox_password') : $all_paypal_settings->setting_site_paypal_sandbox_password }}">
                                @error('setting_site_paypal_sandbox_password')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_paypal_sandbox_secret">{{ __('backend.setting.sandbox-secret') }}</label>
                                <input id="setting_site_paypal_sandbox_secret" type="text" class="form-control @error('setting_site_paypal_sandbox_secret') is-invalid @enderror" name="setting_site_paypal_sandbox_secret" value="{{ old('setting_site_paypal_sandbox_secret') ? old('setting_site_paypal_sandbox_secret') : $all_paypal_settings->setting_site_paypal_sandbox_secret }}">
                                @error('setting_site_paypal_sandbox_secret')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_paypal_sandbox_certificate">{{ __('backend.setting.sandbox-certificate') }}</label>
                                <input id="setting_site_paypal_sandbox_certificate" type="text" class="form-control @error('setting_site_paypal_sandbox_certificate') is-invalid @enderror" name="setting_site_paypal_sandbox_certificate" value="{{ old('setting_site_paypal_sandbox_certificate') ? old('setting_site_paypal_sandbox_certificate') : $all_paypal_settings->setting_site_paypal_sandbox_certificate }}">
                                @error('setting_site_paypal_sandbox_certificate')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_paypal_live_username">{{ __('backend.setting.live-username') }}</label>
                                <input id="setting_site_paypal_live_username" type="text" class="form-control @error('setting_site_paypal_live_username') is-invalid @enderror" name="setting_site_paypal_live_username" value="{{ old('setting_site_paypal_live_username') ? old('setting_site_paypal_live_username') : $all_paypal_settings->setting_site_paypal_live_username }}">
                                @error('setting_site_paypal_live_username')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_paypal_live_password">{{ __('backend.setting.live-password') }}</label>
                                <input id="setting_site_paypal_live_password" type="text" class="form-control @error('setting_site_paypal_live_password') is-invalid @enderror" name="setting_site_paypal_live_password" value="{{ old('setting_site_paypal_live_password') ? old('setting_site_paypal_live_password') : $all_paypal_settings->setting_site_paypal_live_password }}">
                                @error('setting_site_paypal_live_password')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_paypal_live_secret">{{ __('backend.setting.live-secret') }}</label>
                                <input id="setting_site_paypal_live_secret" type="text" class="form-control @error('setting_site_paypal_live_secret') is-invalid @enderror" name="setting_site_paypal_live_secret" value="{{ old('setting_site_paypal_live_secret') ? old('setting_site_paypal_live_secret') : $all_paypal_settings->setting_site_paypal_live_secret }}">
                                @error('setting_site_paypal_live_secret')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_paypal_live_certificate">{{ __('backend.setting.live-certificate') }}</label>
                                <input id="setting_site_paypal_live_certificate" type="text" class="form-control @error('setting_site_paypal_live_certificate') is-invalid @enderror" name="setting_site_paypal_live_certificate" value="{{ old('setting_site_paypal_live_certificate') ? old('setting_site_paypal_live_certificate') : $all_paypal_settings->setting_site_paypal_live_certificate }}">
                                @error('setting_site_paypal_live_certificate')
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
