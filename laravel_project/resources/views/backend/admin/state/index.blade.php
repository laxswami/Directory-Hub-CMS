@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.state.state') }}</h1>
            <p class="mb-4">{{ __('backend.state.state-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.states.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.state.add-state') }}</span>
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
                            <form class="form-inline" action="{{ route('admin.states.index') }}" method="GET">
                                <div class="form-group mr-2">
                                    <select class="custom-select" name="country">
                                        <option value="0">{{ __('backend.state.select-country') }}</option>
                                        @foreach($all_countries as $all_countries_key => $country)
                                            <option value="{{ $country->id }}" {{ $country->id == $country_id ? 'selected' : '' }}>{{ $country->country_name }}</option>
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
                                <th>{{ __('backend.state.id') }}</th>
                                <th>{{ __('backend.state.name') }}</th>
                                <th>{{ __('backend.state.abbr') }}</th>
                                <th>{{ __('backend.state.slug') }}</th>
                                <th>{{ __('backend.state.country') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.state.id') }}</th>
                                <th>{{ __('backend.state.name') }}</th>
                                <th>{{ __('backend.state.abbr') }}</th>
                                <th>{{ __('backend.state.slug') }}</th>
                                <th>{{ __('backend.state.country') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_states as $key => $state)
                                <tr>
                                    <td>{{ $state->id }}</td>
                                    <td>{{ $state->state_name }}</td>
                                    <td>{{ $state->state_abbr }}</td>
                                    <td>{{ $state->state_slug }}</td>
                                    <td>{{ $state->country->country_name }}</td>
                                    <td>
                                        <a href="{{ route('admin.states.edit', $state->id) }}" class="btn btn-primary btn-circle">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {

            "use strict";

            $('#dataTable').DataTable();
        });
    </script>
@endsection
