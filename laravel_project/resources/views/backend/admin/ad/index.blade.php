@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('advertisement.manage-ad') }}</h1>
            <p class="mb-4">{{ __('advertisement.manage-ad-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.advertisements.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('advertisement.add-ad') }}</span>
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
                                <th>{{ __('advertisement.ad-name') }}</th>
                                <th>{{ __('advertisement.ad-status') }}</th>
                                <th>{{ __('advertisement.ad-place') }}</th>
                                <th>{{ __('advertisement.ad-position') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('advertisement.ad-name') }}</th>
                                <th>{{ __('advertisement.ad-status') }}</th>
                                <th>{{ __('advertisement.ad-place') }}</th>
                                <th>{{ __('advertisement.ad-position') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_advertisements as $key => $advertisement)
                                <tr>
                                    <td>{{ $advertisement->advertisement_name }}</td>
                                    <td>
                                        @if($advertisement->advertisement_status == \App\Advertisement::AD_STATUS_ENABLE)
                                            <a class="btn btn-success btn-sm text-white">{{ __('advertisement.ad-status-enable') }}</a>
                                        @else
                                            <a class="btn btn-warning btn-sm text-white">{{ __('advertisement.ad-status-disable') }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($advertisement->advertisement_place == \App\Advertisement::AD_PLACE_LISTING_RESULTS_PAGES)
                                            {{ __('advertisement.ad-place-listing-results') }}
                                        @elseif($advertisement->advertisement_place == \App\Advertisement::AD_PLACE_LISTING_SEARCH_PAGE)
                                            {{ __('advertisement.ad-place-listing-search') }}
                                        @elseif($advertisement->advertisement_place == \App\Advertisement::AD_PLACE_BUSINESS_LISTING_PAGE)
                                            {{ __('advertisement.ad-place-business-listing') }}
                                        @elseif($advertisement->advertisement_place == \App\Advertisement::AD_PLACE_BLOG_POSTS_PAGES)
                                            {{ __('advertisement.ad-place-blog-posts') }}
                                        @elseif($advertisement->advertisement_place == \App\Advertisement::AD_PLACE_BLOG_TOPIC_PAGES)
                                            {{ __('advertisement.ad-place-blog-topic') }}
                                        @elseif($advertisement->advertisement_place == \App\Advertisement::AD_PLACE_BLOG_TAG_PAGES)
                                            {{ __('advertisement.ad-place-blog-tag') }}
                                        @elseif($advertisement->advertisement_place == \App\Advertisement::AD_PLACE_SINGLE_POST_PAGE)
                                            {{ __('advertisement.ad-place-single-post') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB)
                                            {{ __('advertisement.ad-position-before-breadcrumb') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB)
                                            {{ __('advertisement.ad-position-after-breadcrumb') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_CONTENT)
                                            {{ __('advertisement.ad-position-before-content') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_AFTER_CONTENT)
                                            {{ __('advertisement.ad-position-after-content') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT)
                                            {{ __('advertisement.ad-position-sidebar-before-content') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT)
                                            {{ __('advertisement.ad-position-sidebar-after-content') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_GALLERY)
                                            {{ __('advertisement.ad-position-before-gallery') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_DESCRIPTION)
                                            {{ __('advertisement.ad-position-before-description') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_LOCATION)
                                            {{ __('advertisement.ad-position-before-location') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_FEATURES)
                                            {{ __('advertisement.ad-position-before-features') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_REVIEWS)
                                            {{ __('advertisement.ad-position-before-reviews') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_COMMENTS)
                                            {{ __('advertisement.ad-position-before-comments') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_SHARE)
                                            {{ __('advertisement.ad-position-before-share') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_AFTER_SHARE)
                                            {{ __('advertisement.ad-position-after-share') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_FEATURE_IMAGE)
                                            {{ __('advertisement.ad-position-before-feature-image') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_TITLE)
                                            {{ __('advertisement.ad-position-before-title') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_BEFORE_POST_CONTENT)
                                            {{ __('advertisement.ad-position-before-post-content') }}
                                        @elseif($advertisement->advertisement_position == \App\Advertisement::AD_POSITION_AFTER_POST_CONTENT)
                                            {{ __('advertisement.ad-position-after-post-content') }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.advertisements.edit', $advertisement->id) }}" class="btn btn-primary btn-circle">
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
