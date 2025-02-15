@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.city.add-city') }}</h1>
            <p class="mb-4">{{ __('backend.city.add-city-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.cities.index') }}" class="btn btn-info btn-icon-split">
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
                    <form method="POST" action="{{ route('admin.cities.store') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="state_id" class="text-black">{{ __('backend.city.select-state') }}</label>
                                <select class="custom-select" name="state_id">
                                    @foreach($all_states as $key => $state)
                                        <option value="{{ $state->id }}" {{ $state->id == old('state_id') ? 'selected' : '' }}>{{ $state->state_name . ', ' . $state->country->country_name }}</option>
                                    @endforeach
                                </select>

                                @error('state_id')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="city_name" class="text-black">{{ __('backend.city.city-name') }}</label>
                                <input id="city_name" type="text" class="form-control @error('city_name') is-invalid @enderror" name="city_name" value="{{ old('city_name') }}">
                                @error('city_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="city_slug" class="text-black">{{ __('setting_language.location.url-slug') }}</label>
                                <input id="city_slug" type="text" class="form-control @error('city_slug') is-invalid @enderror" name="city_slug" value="{{ old('city_slug') }}">
                                <small class="form-text text-muted">
                                    {{ __('setting_language.location.url-slug-help') }}
                                </small>
                                @error('city_slug')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="city_lat">{{ __('backend.city.city-lat') }}</label>
                                <input id="city_lat" type="text" class="form-control @error('city_lat') is-invalid @enderror" name="city_lat" value="{{ old('city_lat') }}">
                                @error('city_lat')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="city_lng">{{ __('backend.city.city-lng') }}</label>
                                <input id="city_lng" type="text" class="form-control @error('city_lng') is-invalid @enderror" name="city_lng" value="{{ old('city_lng') }}">
                                @error('city_lng')
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
