@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('recaptcha.google-recaptcha') }}</h1>
            <p class="mb-4">{{ __('recaptcha.google-recaptcha-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.recaptcha.update') }}" class="">
                        @csrf
                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_RECAPTCHA_LOGIN_ENABLE }}" name="setting_site_recaptcha_login_enable" type="checkbox" class="custom-control-input" id="setting_site_recaptcha_login_enable" {{ (old('setting_site_recaptcha_login_enable') ? old('setting_site_recaptcha_login_enable') : $all_recaptcha_settings->setting_site_recaptcha_login_enable) == \App\Setting::SITE_RECAPTCHA_LOGIN_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_recaptcha_login_enable">{{ __('recaptcha.enable-login') }}</label>
                                </div>
                                @error('setting_site_recaptcha_login_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE }}" name="setting_site_recaptcha_sign_up_enable" type="checkbox" class="custom-control-input" id="setting_site_recaptcha_sign_up_enable" {{ (old('setting_site_recaptcha_sign_up_enable') ? old('setting_site_recaptcha_sign_up_enable') : $all_recaptcha_settings->setting_site_recaptcha_sign_up_enable) == \App\Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_recaptcha_sign_up_enable">{{ __('recaptcha.enable-sign-up') }}</label>
                                </div>
                                @error('setting_site_recaptcha_sign_up_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_RECAPTCHA_CONTACT_ENABLE }}" name="setting_site_recaptcha_contact_enable" type="checkbox" class="custom-control-input" id="setting_site_recaptcha_contact_enable" {{ (old('setting_site_recaptcha_contact_enable') ? old('setting_site_recaptcha_contact_enable') : $all_recaptcha_settings->setting_site_recaptcha_contact_enable) == \App\Setting::SITE_RECAPTCHA_CONTACT_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_recaptcha_contact_enable">{{ __('recaptcha.enable-contact') }}</label>
                                </div>
                                @error('setting_site_recaptcha_contact_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE }}" name="setting_site_recaptcha_item_lead_enable" type="checkbox" class="custom-control-input" id="setting_site_recaptcha_item_lead_enable" {{ (old('setting_site_recaptcha_item_lead_enable') ? old('setting_site_recaptcha_item_lead_enable') : $all_recaptcha_settings->setting_site_recaptcha_item_lead_enable) == \App\Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_recaptcha_item_lead_enable">{{ __('role_permission.setting.item-lead-enable') }}</label>
                                </div>
                                @error('setting_site_recaptcha_item_lead_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_recaptcha_site_key">{{ __('recaptcha.recaptcha-site-key') }}</label>
                                <input id="setting_site_recaptcha_site_key" type="text" class="form-control @error('setting_site_recaptcha_site_key') is-invalid @enderror" name="setting_site_recaptcha_site_key" value="{{ old('setting_site_recaptcha_site_key') ? old('setting_site_recaptcha_site_key') : $all_recaptcha_settings->setting_site_recaptcha_site_key }}">
                                @error('setting_site_recaptcha_site_key')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_recaptcha_secret_key">{{ __('recaptcha.recaptcha-site-secret') }}</label>
                                <input id="setting_site_recaptcha_secret_key" type="text" class="form-control @error('setting_site_recaptcha_secret_key') is-invalid @enderror" name="setting_site_recaptcha_secret_key" value="{{ old('setting_site_recaptcha_secret_key') ? old('setting_site_recaptcha_secret_key') : $all_recaptcha_settings->setting_site_recaptcha_secret_key }}">
                                @error('setting_site_recaptcha_secret_key')
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
