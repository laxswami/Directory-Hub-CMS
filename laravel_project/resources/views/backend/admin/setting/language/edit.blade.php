@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('setting_language.language.language-setting') }}</h1>
            <p class="mb-4">{{ __('setting_language.language.language-setting-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <form method="POST" action="{{ route('admin.settings.language.update') }}" class="">
                        @csrf

                        <div class="row form-group align-items-center">
                            <div class="col-12 col-xl-2">
                                <label class="text-black" for="setting_language_default_language">{{ __('theme_directory_hub.setting.default-language') }}</label>
                                <select class="selectpicker form-control @error('setting_language_default_language') is-invalid @enderror" name="setting_language_default_language" data-live-search="true">
                                    <option value="">{{ __('backend.setting.language.select-language') }}</option>

                                    @foreach(\App\Setting::LANGUAGES as $setting_languages_key => $language)
                                        <option value="{{ $setting_languages_key }}" {{ $settings->settingLanguage->setting_language_default_language == $setting_languages_key ? 'selected' : '' }}>
                                            {{ __('prefer_languages.' . $setting_languages_key) }}
                                        </option>
                                    @endforeach

                                </select>
                                <small class="form-text text-muted">
                                    {{ __('theme_directory_hub.setting.default-language-help') }}
                                </small>
                                @error('setting_language_default_language')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-12 mt-3 mt-xl-0 col-xl-10">

                                <div class="row">
                                    <div class="col-12">
                                        <label class="text-black">{{ __('setting_language.language.available-website-languages') }}</label>
                                        <small class="form-text text-muted">
                                            {{ __('setting_language.language.available-website-languages-help') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    @foreach(\App\Setting::LANGUAGES as $setting_languages_key => $language)
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                            <div class="form-check form-check-inline">
                                                @if(old('setting_languages'))
                                                    <input name="setting_languages[]" class="form-check-input" type="checkbox" id="setting_languages_{{ $setting_languages_key }}" value="{{ $setting_languages_key }}" {{ in_array($setting_languages_key, (old('setting_languages') ? old('setting_languages') : array()) ) ? 'checked' : '' }}>
                                                @else
                                                    <input name="setting_languages[]" class="form-check-input" type="checkbox" id="setting_languages_{{ $setting_languages_key }}" value="{{ $setting_languages_key }}" {{ $settings->settingLanguage->$language == \App\SettingLanguage::LANGUAGE_ENABLE  ? 'checked' : '' }}>
                                                @endif
                                                <label class="form-check-label" for="setting_languages_{{ $setting_languages_key }}">{{ __('prefer_languages.' . $setting_languages_key) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
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
    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.admin.partials.bootstrap-select-locale')
@endsection
