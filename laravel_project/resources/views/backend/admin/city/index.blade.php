@extends('backend.admin.layouts.app')

@section('styles')

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.city.city') }}</h1>
            <p class="mb-4">{{ __('backend.city.city-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.cities.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.city.add-city') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12"><span class="text-lg">{{ __('backend.shared.data-filter') }}</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <form class="form-inline" action="{{ route('admin.cities.index') }}" method="GET">
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="state">
                                        <option value="0">{{ __('backend.city.select-state-city') }}</option>
                                        @foreach($all_states as $key => $state)
                                            <option value="{{ $state->id }}" {{ $state->id == $state_id ? 'selected' : '' }}>{{ $state->state_name . ', ' . $state->country->country_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">{{ __('backend.shared.update') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('backend.city.id') }}</th>
                                <th>{{ __('backend.city.name') }}</th>
                                <th>{{ __('backend.city.slug') }}</th>
                                <th>{{ __('backend.city.country') }}</th>
                                <th>{{ __('backend.city.state') }}</th>
                                <th>{{ __('backend.city.lat') }}</th>
                                <th>{{ __('backend.city.lng') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.city.id') }}</th>
                                <th>{{ __('backend.city.name') }}</th>
                                <th>{{ __('backend.city.slug') }}</th>
                                <th>{{ __('backend.city.country') }}</th>
                                <th>{{ __('backend.city.state') }}</th>
                                <th>{{ __('backend.city.lat') }}</th>
                                <th>{{ __('backend.city.lng') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @if(count($all_cities))
                                @foreach($all_cities as $key => $city)
                                    <tr>
                                        <td>{{ $city->id }}</td>
                                        <td>{{ $city->city_name }}</td>
                                        <td>{{ $city->city_slug }}</td>
                                        <td>{{ $city->state->country->country_name }}</td>
                                        <td>{{ $city->state->state_name }}</td>
                                        <td>{{ $city->city_lat }}</td>
                                        <td>{{ $city->city_lng }}</td>
                                        <td>
                                            <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-primary btn-circle">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><th>{{ __('backend.city.no-data') }}</th></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    @if($state_id > 0)
                        {{ $all_cities->appends(['state' => $state_id])->links() }}
                    @else
                        {{ $all_cities->links() }}
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
