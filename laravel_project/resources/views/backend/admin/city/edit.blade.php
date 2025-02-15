@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.city.edit-city') }}</h1>
            <p class="mb-4">{{ __('backend.city.edit-city-desc') }}</p>
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
                    <form method="POST" action="{{ route('admin.cities.update', $city) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                {{ __('backend.city.country') }}: {{ $city->state->country->country_name }}<br>
                                {{ __('backend.city.state') }}: {{ $city->state->state_name }}
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="city_name" class="text-black">{{ __('backend.city.city-name') }}</label>
                                <input id="city_name" type="text" class="form-control @error('city_name') is-invalid @enderror" name="city_name" value="{{ old('city_name') ? old('city_name') : $city->city_name }}" autofocus>
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
                                <input id="city_slug" type="text" class="form-control @error('city_slug') is-invalid @enderror" name="city_slug" value="{{ old('city_slug') ? old('city_slug') : $city->city_slug }}">
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
                                <input id="city_lat" type="text" class="form-control @error('city_lat') is-invalid @enderror" name="city_lat" value="{{ old('city_lat') ? old('city_lat') : $city->city_lat }}">
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
                                <input id="city_lng" type="text" class="form-control @error('city_lng') is-invalid @enderror" name="city_lng" value="{{ old('city_lng') ? old('city_lng') : $city->city_lng }}">
                                @error('city_lng')
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
                    <p>{{ __('country_delete.delete-city-warning') }}</p>
                    <p>{{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.city'), 'record_name' => $city->city_name]) }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.cities.destroy', $city) }}" method="POST">
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
