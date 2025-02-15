@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/simplemde/dist/simplemde.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('advertisement.edit-ad') }}</h1>
            <p class="mb-4">{{ __('advertisement.edit-ad-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row mb-4">
                <div class="col-12">

                    <span class="text-lg text-gray-800">{{ __('advertisement.pages-to-show-ads') }}: </span>
                    <span class="text-lg">
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
                    </span>

                </div>
            </div>



            <div class="row">
                <div class="col-12 col-md-10 col-lg-6">
                    <form method="POST" action="{{ route('admin.advertisements.update', $advertisement) }}">
                        @csrf
                        @method('PUT')

                        <input name="advertisement_place" value="{{ $advertisement->advertisement_place }}" type="hidden" id="input_advertisement_place">

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="advertisement_name" class="text-black">{{ __('advertisement.ad-name') }}</label>
                                <input id="advertisement_name" type="text" class="form-control @error('advertisement_name') is-invalid @enderror" name="advertisement_name" value="{{ old('advertisement_name') ? old('advertisement_name') : $advertisement->advertisement_name }}" autofocus>
                                @error('advertisement_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="advertisement_code">{{ __('advertisement.ad-code') }}</label>
                                <textarea rows="6" id="advertisement_code" type="text" class="form-control @error('advertisement_code') is-invalid @enderror" name="advertisement_code">{{ old('advertisement_code') ? old('advertisement_code') : $advertisement->advertisement_code }}</textarea>
                                @error('advertisement_code')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-6">
                                <label class="text-black" for="advertisement_status">{{ __('advertisement.ad-status') }}</label>

                                <select class="custom-select @error('advertisement_status') is-invalid @enderror" name="advertisement_status">
                                    <option value="{{ \App\Advertisement::AD_STATUS_ENABLE }}" {{ (old('advertisement_status') ? old('advertisement_status') : $advertisement->advertisement_status) == \App\Advertisement::AD_STATUS_ENABLE ? 'selected' : '' }}>{{ __('advertisement.ad-status-enable') }}</option>
                                    <option value="{{ \App\Advertisement::AD_STATUS_DISABLE }}" {{ (old('advertisement_status') ? old('advertisement_status') : $advertisement->advertisement_status) == \App\Advertisement::AD_STATUS_DISABLE ? 'selected' : '' }}>{{ __('advertisement.ad-status-disable') }}</option>
                                </select>
                                @error('advertisement_status')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="text-black" for="advertisement_position">{{ __('advertisement.ad-position') }}</label>
                                <select class="custom-select @error('advertisement_position') is-invalid @enderror" name="advertisement_position">

                                    @if($advertisement->advertisement_place == 1 || $advertisement->advertisement_place == 2)
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-before-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-after-content') }}
                                        </option>
                                    @elseif($advertisement->advertisement_place == 3)
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_GALLERY }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_GALLERY ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-gallery') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_DESCRIPTION }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_DESCRIPTION ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-description') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_LOCATION }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_LOCATION ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-location') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_FEATURES }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_FEATURES ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-features') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_REVIEWS }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_REVIEWS ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-reviews') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_COMMENTS }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_COMMENTS ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-comments') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_SHARE }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_SHARE ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-share') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_SHARE }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_SHARE ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-share') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-before-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-after-content') }}
                                        </option>
                                    @elseif($advertisement->advertisement_place == 4 || $advertisement->advertisement_place == 5 || $advertisement->advertisement_place == 6)
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-before-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-after-content') }}
                                        </option>
                                    @elseif($advertisement->advertisement_place == 7)
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_BREADCRUMB ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-breadcrumb') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_FEATURE_IMAGE }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_FEATURE_IMAGE ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-feature-image') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_TITLE }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_TITLE ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-title') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_POST_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_POST_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-post-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_POST_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_POST_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-post-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_COMMENTS }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_COMMENTS ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-comments') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_BEFORE_SHARE }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_BEFORE_SHARE ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-before-share') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_AFTER_SHARE }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_AFTER_SHARE ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-after-share') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_BEFORE_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-before-content') }}
                                        </option>
                                        <option value="{{ \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT }}" {{ (old('advertisement_position') ? old('advertisement_position') : $advertisement->advertisement_position) == \App\Advertisement::AD_POSITION_SIDEBAR_AFTER_CONTENT ? 'selected' : '' }}>
                                            {{ __('advertisement.ad-position-sidebar-after-content') }}
                                        </option>
                                    @endif

                                </select>
                                @error('advertisement_position')
                                <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-6">
                                <label class="text-black" for="advertisement_alignment">{{ __('advertisement.ad-alignment') }}</label>

                                <select class="custom-select @error('advertisement_alignment') is-invalid @enderror" name="advertisement_alignment">
                                    <option value="{{ \App\Advertisement::AD_ALIGNMENT_LEFT }}" {{ (old('advertisement_alignment') ? old('advertisement_alignment') : $advertisement->advertisement_alignment) == \App\Advertisement::AD_ALIGNMENT_LEFT ? 'selected' : '' }}>{{ __('advertisement.ad-alignment-left') }}</option>
                                    <option value="{{ \App\Advertisement::AD_ALIGNMENT_CENTER }}" {{ (old('advertisement_alignment') ? old('advertisement_alignment') : $advertisement->advertisement_alignment) == \App\Advertisement::AD_ALIGNMENT_CENTER ? 'selected' : '' }}>{{ __('advertisement.ad-alignment-center') }}</option>
                                    <option value="{{ \App\Advertisement::AD_ALIGNMENT_RIGHT }}" {{ (old('advertisement_alignment') ? old('advertisement_alignment') : $advertisement->advertisement_alignment) == \App\Advertisement::AD_ALIGNMENT_RIGHT ? 'selected' : '' }}>{{ __('advertisement.ad-alignment-right') }}</option>
                                </select>
                                @error('advertisement_alignment')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>


                        <div class="row form-group">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('advertisement.update-ad') }}
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
                    {{ __('advertisement.delete-confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.advertisements.destroy', $advertisement->id) }}" method="POST">
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
    <script src="{{ asset('backend/vendor/simplemde/dist/simplemde.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            "use strict";

            /**
             * Start initial ad code textarea markdown
             */
            var simplemde = new SimpleMDE({
                element: document.getElementById("advertisement_code"),
                status: false,
                toolbar: false,
                spellChecker: false,
            });
            /**
             * End initial ad code textarea markdown
             */
        });
    </script>
@endsection
