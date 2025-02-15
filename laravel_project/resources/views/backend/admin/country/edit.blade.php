@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.country.edit-country') }}</h1>
            <p class="mb-4">{{ __('backend.country.edit-country-desc') }}</p>
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
                    <form method="POST" action="{{ route('admin.countries.update', $country->id) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="country_name" class="text-black">{{ __('backend.country.country-name') }}</label>
                                <input id="country_name" type="text" class="form-control @error('country_name') is-invalid @enderror" name="country_name" value="{{ old('country_name') ? old('country_name') : $country->country_name }}" autofocus>
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
                                <input id="country_abbr" type="text" class="form-control @error('country_abbr') is-invalid @enderror" name="country_abbr" value="{{ old('country_abbr') ? old('country_abbr') : $country->country_abbr }}">
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
                                <input id="country_slug" type="text" class="form-control @error('country_slug') is-invalid @enderror" name="country_slug" value="{{ old('country_slug') ? old('country_slug') : $country->country_slug }}">
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
                                    <option value="{{ \App\Country::COUNTRY_STATUS_ENABLE }}" {{ (old('country_status') ? old('country_status') : $country->country_status) == \App\Country::COUNTRY_STATUS_ENABLE ? 'selected' : '' }}>
                                        {{ __('setting_language.country.country-status-enable') }}
                                    </option>
                                    <option value="{{ \App\Country::COUNTRY_STATUS_DISABLE }}" {{ (old('country_status') ? old('country_status') : $country->country_status) == \App\Country::COUNTRY_STATUS_DISABLE ? 'selected' : '' }}>
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

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                            </div>
                            <div class="col-4 text-right">
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    {{ __('backend.shared.delete') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ __('country_delete.delete-country-warning') }}
                    </p>
                    <p>
                        {{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.country'), 'record_name' => $country->country_name]) }}
                    </p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.countries.destroy', $country->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection
