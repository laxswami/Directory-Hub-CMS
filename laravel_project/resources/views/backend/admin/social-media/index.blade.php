@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.social-media.social-media') }}</h1>
            <p class="mb-4">{{ __('backend.social-media.social-media-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.social-medias.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.social-media.add-social-media') }}</span>
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
                                <th>{{ __('backend.social-media.id') }}</th>
                                <th>{{ __('backend.social-media.name') }}</th>
                                <th>{{ __('backend.social-media.icon') }}</th>
                                <th>{{ __('backend.social-media.link') }}</th>
                                <th>{{ __('backend.social-media.order') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('backend.social-media.id') }}</th>
                                <th>{{ __('backend.social-media.name') }}</th>
                                <th>{{ __('backend.social-media.icon') }}</th>
                                <th>{{ __('backend.social-media.link') }}</th>
                                <th>{{ __('backend.social-media.order') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_social_medias as $key => $social_media)
                                <tr>
                                    <td>{{ $social_media->id }}</td>
                                    <td>{{ $social_media->social_media_name }}</td>
                                    <td>
                                        <i class="{{ $social_media->social_media_icon }}"></i>
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ $social_media->social_media_link }}">
                                            {{ $social_media->social_media_link }}
                                        </a>
                                    </td>
                                    <td>{{ $social_media->social_media_order }}</td>
                                    <td>
                                        <a href="{{ route('admin.social-medias.edit', $social_media->id) }}" class="btn btn-primary btn-circle">
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
