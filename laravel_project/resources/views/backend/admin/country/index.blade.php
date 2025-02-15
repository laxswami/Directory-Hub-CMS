@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.country.country') }}</h1>
            <p class="mb-4">{{ __('backend.country.country-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.countries.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.country.add-country') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('backend.country.id') }}</th>
                                <th>{{ __('backend.country.name') }}</th>
                                <th>{{ __('backend.country.abbr') }}</th>
                                <th>{{ __('backend.country.slug') }}</th>
                                <th>{{ __('setting_language.country.country-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.country.id') }}</th>
                                <th>{{ __('backend.country.name') }}</th>
                                <th>{{ __('backend.country.abbr') }}</th>
                                <th>{{ __('backend.country.slug') }}</th>
                                <th>{{ __('setting_language.country.country-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_countries as $all_countries_key => $country)
                                <tr>
                                    <td>{{ $country->id }}</td>
                                    <td>{{ $country->country_name }}</td>
                                    <td>{{ $country->country_abbr }}</td>
                                    <td>{{ $country->country_slug }}</td>
                                    <td>
                                        @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                                            <span class="bg-success rounded text-white pl-2 pr-2 pt-1 pb-1">{{ __('setting_language.country.country-status-enable') }}</span>
                                        @else
                                            <span class="bg-warning rounded text-white pl-2 pr-2 pt-1 pb-1">{{ __('setting_language.country.country-status-disable') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-primary btn-circle">
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
