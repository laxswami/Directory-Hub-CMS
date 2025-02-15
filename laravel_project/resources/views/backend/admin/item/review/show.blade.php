@extends('backend.admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/rateyo/jquery.rateyo.min.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ asset('backend/vendor/justified-gallery/justifiedGallery.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/vendor/colorbox/colorbox.css') }}" type="text/css">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('review.backend.review-detail') }}</h1>
            <p class="mb-4">{{ __('review.backend.review-detail-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.reviews.index') }}" class="btn btn-info btn-icon-split">
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

                    <a target="_blank" href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

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
                <div class="col-4">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <span class="text-lg text-gray-800">{{ __('review.backend.status') }}: </span>

                            @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)

                                <span class="text-success">{{ __('review.backend.review-approved') }}</span>
                            @else

                                <span class="text-warning">{{ __('review.backend.review-pending') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="rating" class="text-black">{{ __('review.backend.overall-rating') }}</label>

                            <div class="pl-0 rating_stars rating_stars_{{ $review->id }}" data-id="rating_stars_{{ $review->id }}" data-rating="{{ $review->rating }}"></div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_service_rating" class="text-black">{{ __('review.backend.customer-service') }}</label>

                            <div class="pl-0 rating_stars rating_stars_customer_service_{{ $review->id }}" data-id="rating_stars_customer_service_{{ $review->id }}" data-rating="{{ $review->customer_service_rating }}"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="quality_rating" class="text-black">{{ __('review.backend.quality') }}</label>

                            <div class="pl-0 rating_stars rating_stars_quality_{{ $review->id }}" data-id="rating_stars_quality_{{ $review->id }}" data-rating="{{ $review->quality_rating }}"></div>
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="friendly_rating" class="text-black">{{ __('review.backend.friendly') }}</label>

                            <div class="pl-0 rating_stars rating_stars_friendly_{{ $review->id }}" data-id="rating_stars_friendly_{{ $review->id }}" data-rating="{{ $review->friendly_rating }}"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="pricing_rating" class="text-black">{{ __('review.backend.pricing') }}</label>

                            <div class="pl-0 rating_stars rating_stars_pricing_{{ $review->id }}" data-id="rating_stars_pricing_{{ $review->id }}" data-rating="{{ $review->pricing_rating }}"></div>
                        </div>
                    </div>

                </div>
                <div class="col-8">

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-1">
                            @if(empty(\App\User::find($review->author_id)->user_image))
                                <img src="{{ asset('backend/images/placeholder/profile-'. intval(\App\User::find($review->author_id)->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                            @else

                                <img src="{{ Storage::disk('public')->url('user/' . \App\User::find($review->author_id)->user_image) }}" alt="{{ \App\User::find($review->author_id)->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-md-4">
                            <span>{{ \App\User::find($review->author_id)->name }}</span>
                        </div>
                        <div class="col-md-7 text-right">
                            <span>{{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                            @if($review->created_at != $review->updated_at)
                                <br>
                                <span>{{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($review->updated_at)->diffForHumans() }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-lg text-gray-800">{{ $review->title }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p>{!! clean(nl2br($review->body), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            @if($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES)
                                <span class="text-success">{{ __('review.backend.recommend') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12" id="review-image-gallery">
                            @foreach($review_image_galleries as $key => $review_image_gallery)
                                <a href="{{ Storage::disk('public')->url('item/review/' . $review_image_gallery->review_image_gallery_name) }}" rel="review-image-gallery-thumb">
                                    <img class="rounded" alt="Image" src="{{ Storage::disk('public')->url('item/review/' . $review_image_gallery->review_image_gallery_thumb_name) }}"/>
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)
                        <form action="{{ route('admin.items.reviews.disapprove', ['review_id' => $review->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning">{{ __('backend.shared.disapprove') }}</button>
                        </form>
                    @else
                        <form action="{{ route('admin.items.reviews.approve', ['review_id' => $review->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">{{ __('backend.shared.approve') }}</button>
                        </form>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                        {{ __('backend.shared.delete') }}
                    </a>
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
                    {{ __('review.backend.delete-a-review') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.items.reviews.delete', ['review_id' => $review->id]) }}" method="POST">
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

    <script src="{{ asset('backend/vendor/rateyo/jquery.rateyo.min.js') }}"></script>

    <script src="{{ asset('backend/vendor/justified-gallery/jquery.justifiedGallery.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/colorbox/jquery.colorbox-min.js') }}"></script>

    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {

            "use strict";

            /**
             * Start initial rating stars
             */

            /*
             * NOTE: You should listen for the event before calling `rateYo` on the element
             *       or use `onInit` option to achieve the same thing
             */
            $(".rating_stars").on("rateyo.init", function (e, data) {

                console.log(e.target.getAttribute('data-id'));
                console.log(e.target.getAttribute('data-rating'));
                console.log("RateYo initialized! with " + data.rating);

                var $rateYo = $("." + e.target.getAttribute('data-id')).rateYo();
                $rateYo.rateYo("rating", e.target.getAttribute('data-rating'));

                /* set the option `multiColor` to show Multi Color Rating */
                $rateYo.rateYo("option", "spacing", "2px");
                $rateYo.rateYo("option", "starWidth", "15px");
                $rateYo.rateYo("option", "readOnly", true);

            });

            $(".rating_stars").rateYo({
                spacing: "2px",
                starWidth: "15px",
                readOnly: true,
                rating: 0
            });
            /**
             * End initial rating stars
             */

            /**
             * Start initial review image gallery justify gallery
             */
            $("#review-image-gallery").justifiedGallery({
                rowHeight : 80,
                maxRowHeight: 100,
                lastRow : 'nojustify',
                margins : 3,
                captions: false,
                randomize: true,
                rel : 'review-image-gallery-thumb', //replace with 'gallery1' the rel attribute of each link
            }).on('jg.complete', function () {
                $(this).find('a').colorbox({
                    maxWidth : '95%',
                    maxHeight : '95%',
                    opacity : 0.8,
                });
            });
            /**
             * End initial review image gallery justify gallery
             */
        });
    </script>

@endsection
