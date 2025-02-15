@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('maintenance_mode.maintenance-setting') }}</h1>
            <p class="mb-4">{{ __('maintenance_mode.maintenance-setting-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <form method="POST" action="{{ route('admin.settings.maintenance.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                        <div class="form-check">
                                            <input {{ (old('setting_site_maintenance_mode') ? old('setting_site_maintenance_mode') : $settings->setting_site_maintenance_mode) == \App\Setting::SITE_MAINTENANCE_MODE_OFF ? 'checked' : '' }} class="form-check-input" type="radio" name="setting_site_maintenance_mode" id="setting_site_maintenance_mode_off" value="{{ \App\Setting::SITE_MAINTENANCE_MODE_OFF }}" aria-describedby="setting_site_maintenance_mode_off_help">
                                            <label class="form-check-label" for="setting_site_maintenance_mode_off">
                                                {{ __('maintenance_mode.maintenance-mode-off') }}
                                            </label>
                                            <small id="setting_site_maintenance_mode_off_help" class="form-text text-muted">
                                                {{ __('maintenance_mode.maintenance-mode-off-help') }}
                                            </small>
                                            @error('setting_site_maintenance_mode')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input {{ (old('setting_site_maintenance_mode') ? old('setting_site_maintenance_mode') : $settings->setting_site_maintenance_mode) == \App\Setting::SITE_MAINTENANCE_MODE_ON ? 'checked' : '' }} class="form-check-input" type="radio" name="setting_site_maintenance_mode" id="setting_site_maintenance_mode_on" value="{{ \App\Setting::SITE_MAINTENANCE_MODE_ON }}" aria-describedby="setting_site_maintenance_mode_on_help">
                                            <label class="form-check-label" for="setting_site_maintenance_mode_on">
                                                {{ __('maintenance_mode.maintenance-mode-on') }}
                                            </label>
                                            <small id="setting_site_maintenance_mode_on_help" class="form-text text-muted">
                                                {{ __('maintenance_mode.maintenance-mode-on-help') }}
                                            </small>
                                            @error('setting_site_maintenance_mode')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
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
