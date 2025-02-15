@extends('backend.user.layouts.app')

@section('styles')

    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('review.backend.write-a-review') }}</h1>
            <p class="mb-4">{{ __('review.backend.write-a-review-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.items.reviews.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-3">
                    @if(empty($item->item_image))
                        <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive rounded">
                    @else
                        <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $item->item_image) }}" class="img-responsive rounded">
                    @endif

                    <a href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

                </div>
                <div class="col-9">
                    <p>
                        @foreach($item->allCategories()->get() as $key => $category)
                            <span class="bg-info rounded text-white pl-2 pr-2 pt-1 pb-1 mr-1">
                                {{ $category->category_name }}
                            </span>
                        @endforeach
                    </p>
                    <h1 class="h4 mb-2 text-gray-800">{{ $item->item_title }}</h1>
                    <p class="mb-4">
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                        {{ $item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE ? $item->item_address . ', ' : '' }} {{ $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code }}
                        @else
                            <span class="bg-primary text-white pl-1 pr-1 rounded">{{ __('theme_directory_hub.online-listing.online-listing') }}</span>
                        @endif
                    </p>
                    <hr/>
                    <p class="mb-4">{{ $item->item_description }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-8">
                    <form method="POST" action="{{ route('user.items.reviews.store', ['item_slug' => $item->item_slug]) }}">
                        @csrf

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('review.backend.select-rating') }}</span>
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="rating" class="text-black">{{ __('review.backend.overall-rating') }}</label><br>
                                <select class="rating_stars" name="rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ __('rating_summary.1-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ __('rating_summary.2-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ __('rating_summary.3-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ __('rating_summary.4-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ __('rating_summary.5-stars') }}</option>
                                </select>
                                @error('rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-row mb-3">
                            <div class="col-md-3">
                                <label for="customer_service_rating" class="text-black">{{ __('review.backend.customer-service') }}</label><br>
                                <select class="rating_stars" name="customer_service_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ __('rating_summary.1-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ __('rating_summary.2-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ __('rating_summary.3-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ __('rating_summary.4-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ __('rating_summary.5-stars') }}</option>
                                </select>
                                @error('customer_service_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="quality_rating" class="text-black">{{ __('review.backend.quality') }}</label><br>
                                <select class="rating_stars" name="quality_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ __('rating_summary.1-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ __('rating_summary.2-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ __('rating_summary.3-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ __('rating_summary.4-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ __('rating_summary.5-stars') }}</option>
                                </select>
                                @error('quality_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="friendly_rating" class="text-black">{{ __('review.backend.friendly') }}</label><br>
                                <select class="rating_stars" name="friendly_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ __('rating_summary.1-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ __('rating_summary.2-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ __('rating_summary.3-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ __('rating_summary.4-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ __('rating_summary.5-stars') }}</option>
                                </select>
                                @error('friendly_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="pricing_rating" class="text-black">{{ __('review.backend.pricing') }}</label><br>
                                <select class="rating_stars" name="pricing_rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ __('rating_summary.1-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ __('rating_summary.2-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ __('rating_summary.3-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ __('rating_summary.4-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ __('rating_summary.5-stars') }}</option>
                                </select>
                                @error('pricing_rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('review.backend.tell-experience') }}</span>
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="title" class="text-black">{{ __('review.backend.title') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}">
                                @error('title')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="body" class="text-black">{{ __('review.backend.description') }}</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" rows="5" name="body">{{ old('body') }}</textarea>
                                @error('body')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-12">
                                <div class="form-check form-check-inline">
                                    <input {{ old('recommend') == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="recommend" name="recommend" value="1">
                                    <label class="form-check-label" for="recommend">
                                        {{ __('review.backend.recommend') }}
                                    </label>
                                </div>
                                @error('recommend')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('review_galleries.upload-photos') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('review_galleries.upload-photos-help') }}
                                </small>
                                @error('review_image_galleries')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button id="upload_gallery" type="button" class="btn btn-primary mb-2">{{ __('review_galleries.choose-photo') }}</button>
                                        <div class="row" id="selected-images">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('review.backend.post-review') }}
                                </button>
                            </div>
                        </div>

                </form>
                </div>
                <div class="col-4"></div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')

    <!-- Bootstrap Fd Plugin Js-->
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script>
        $(document).ready(function() {

            "use strict";

            /**
             * Start image gallery uplaod
             */
            $('#upload_gallery').on('click', function(){
                window.selectedImages = [];

                $.FileDialog({
                    accept: "image/jpeg",
                }).on("files.bs.filedialog", function (event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {

                        if(a == 12) {break;}
                        selectedImages.push(event.files[a]);
                        html += "<div class='col-lg-3 col-md-4 col-sm-6 mb-2' id='review_image_gallery_" + a + "'>" +
                            "<img style='max-width: 120px;' src='" + event.files[a].content + "'>" +
                            "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#review_image_gallery_" + a + "\").remove();'>" + "{{ __('backend.shared.delete') }}" + "</button>" +
                            "<input type='hidden' value='" + event.files[a].content + "' name='review_image_galleries[]'>" +
                            "</div>";
                    }
                    document.getElementById("selected-images").innerHTML += html;
                });
            });
            /**
             * End image gallery uplaod
             */


        });
    </script>

@endsection
