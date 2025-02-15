@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.country.add-country') }}</h1>
            <p class="mb-4">{{ __('backend.country.add-country-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.countries.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <form method="POST" action="{{ route('admin.countries.store') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="country_name" class="text-black">{{ __('backend.country.country-name') }}</label>
                                <input id="country_name" type="text" class="form-control @error('country_name') is-invalid @enderror" name="country_name" value="{{ old('country_name') }}" autofocus>
                                @error('country_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="country_abbr">{{ __('backend.country.country-abbr') }}</label>
                                <input id="country_abbr" type="text" class="form-control @error('country_abbr') is-invalid @enderror" name="country_abbr" value="{{ old('country_abbr') }}">
                                @error('country_abbr')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="country_slug">{{ __('setting_language.location.url-slug') }}</label>
                                <input id="country_slug" type="text" class="form-control @error('country_slug') is-invalid @enderror" name="country_slug" value="{{ old('country_slug') }}">
                                <small class="form-text text-muted">
                                    {{ __('setting_language.location.url-slug-help') }}
                                </small>
                                @error('country_slug')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="country_status" class="text-black">{{ __('setting_language.country.country-status') }}</label>
                                <select class="custom-select" name="country_status">
                                    <option value="{{ \App\Country::COUNTRY_STATUS_ENABLE }}" {{ old('country_status') == \App\Country::COUNTRY_STATUS_ENABLE ? 'selected' : '' }}>
                                        {{ __('setting_language.country.country-status-enable') }}
                                    </option>
                                    <option value="{{ \App\Country::COUNTRY_STATUS_DISABLE }}" {{ old('country_status') == \App\Country::COUNTRY_STATUS_DISABLE ? 'selected' : '' }}>
                                        {{ __('setting_language.country.country-status-disable') }}
                                    </option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('setting_language.country.country-status-help') }}
                                </small>
                                @error('country_status')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.create') }}
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
