@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('social_login.manage-login') }}</h1>
            <p class="mb-4">{{ __('social_login.manage-login-desc') }}</p>
        </div>
        <div class="col-3 text-right">
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
                                <th>{{ __('social_login.login-provider') }}</th>
                                <th>{{ __('social_login.login-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('social_login.login-provider') }}</th>
                                <th>{{ __('social_login.login-status') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_social_logins as $key => $social_login)
                                <tr>
                                    <td>{{ $social_login->social_login_provider_name }}</td>
                                    <td>
                                        @if($social_login->social_login_enabled == \App\SocialLogin::SOCIAL_LOGIN_ENABLED)
                                            <a class="btn btn-success btn-sm text-white">{{ __('social_login.login-enabled') }}</a>
                                        @else
                                            <a class="btn btn-warning btn-sm text-white">{{ __('social_login.login-disabled') }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.social-logins.edit', $social_login->id) }}" class="btn btn-primary btn-circle">
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
