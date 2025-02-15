@extends('frontend.layouts.app')

@section('styles')

    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR && $site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link rel="stylesheet" href="{{ asset('frontend/vendor/justified-gallery/justifiedGallery.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/colorbox/colorbox.css') }}" type="text/css">

    <!-- Start Google reCAPTCHA version 2 -->
    @if($site_global_settings->setting_site_recaptcha_item_lead_enable == \App\Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
        {!! htmlScriptTagJsApi(['lang' => empty($site_global_settings->setting_site_language) ? 'en' : $site_global_settings->setting_site_language]) !!}
    @endif
    <!-- End Google reCAPTCHA version 2 -->
@endsection

@section('content')

    <!-- Display on xl -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
        <div class="site-blocks-cover inner-page-cover overlay d-none d-xl-flex" style="background-image: url({{ Storage::disk('public')->url('item/' . $item->item_image_blur) }});">
    @else
        <div class="site-blocks-cover inner-page-cover overlay d-none d-xl-flex" style="background-image: url({{ asset('frontend/images/placeholder/full_item_feature_image.webp') }});">
    @endif
        <div class="container">
            <div class="row align-items-center item-blocks-cover">

                <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
                    @if(!empty($item->item_image_tiny))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image" class="img-fluid rounded">
                    @elseif(!empty($item->item_image))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                    @else
                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">

                    <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>

                    @if($item_has_claimed)
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <span class="text-primary">
                                <i class="fas fa-check-circle"></i>
                                {{ __('item_claim.claimed') }}
                            </span>
                        </div>
                    </div>
                    @endif

                    @if($item_count_rating > 0)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="rating_stars_header"></div>
                        </div>
                        <div class="col-md-9 pl-0">
                            <span class="item-cover-address-section">
                                @if($item_count_rating == 1)
                                    {{ '(' . $item_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                @else
                                    {{ '(' . $item_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif

                    @foreach($item_display_categories as $item_display_categories_key => $item_category)
                    <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $item_category->category_slug) }}">
                        <span class="category">{{ $item_category->category_name }}</span>
                    </a>
                    @endforeach

                    @if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                        <a class="text-primary" href="#" data-toggle="modal" data-target="#showCategoriesModal">
                            {{ __('categories.and') . " " . strval($item_total_categories - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " ". __('categories.more') }}
                            <i class="far fa-window-restore text-primary"></i>
                        </a>
                    @endif

                    <p class="item-cover-address-section">
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                {{ $item->item_address }} <br>
                            @endif
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                        @endif
                    </p>

                    @guest
                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                    @else

                        @if($item->user_id != Auth::user()->id)

                            @if(Auth::user()->isAdmin())
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @else
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @endif

                        @endif

                    @endguest
                    <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                    @guest
                        <a class="btn btn-primary rounded text-white" id="item-save-button-xl"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                        <form id="item-save-form-xl" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                            @csrf
                        </form>
                    @else
                        @if(Auth::user()->hasSavedItem($item->id))
                            <a class="btn btn-warning rounded text-white" id="item-saved-button-xl"><i class="fas fa-check"></i> {{ __('frontend.item.saved') }}</a>
                            <form id="item-unsave-form-xl" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @else
                            <a class="btn btn-primary rounded text-white" id="item-save-button-xl"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                            <form id="item-save-form-xl" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @endif
                    @endguest

                    @if(!empty($item->item_phone))
                    <a class="btn btn-primary rounded text-white" href="tel:{{ $item->item_phone }}"><i class="fas fa-phone-alt"></i> {{ __('frontend.item.call') }}</a>
                    @endif
                    <a class="btn btn-primary rounded text-white" href="#" data-toggle="modal" data-target="#qrcodeModal"><i class="fas fa-qrcode"></i> {{ __('theme_directory_hub.listing.qr-code') }}</a>

                </div>
                <div class="col-lg-3 col-md-5 pl-0 pr-0 item-cover-contact-section" data-aos="fade-up" data-aos-delay="400">
                    @if(!empty($item->item_phone))
                        <h3><i class="fas fa-phone-alt"></i> {{ $item->item_phone }}</h3>
                    @endif
                    <p>
                        @if(!empty($item->item_website))
                            <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i class="fa-solid fa-globe"></i></a>
                        @endif
                        @if(!empty($item->item_social_facebook))
                            <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i class="fa-brands fa-facebook-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_twitter))
                            <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i class="fa-brands fa-twitter-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_linkedin))
                            <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if(!empty($item->item_social_instagram))
                            <a class="mr-1" href="https://instagram.com/_u/{{ $item->item_social_instagram }}/" target="_blank" rel="nofollow"><i class="fa-brands fa-instagram-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_whatsapp))
                            <a class="mr-1" href="https://wa.me/{{ $item->item_social_whatsapp }}" target="_blank" rel="nofollow"><i class="fa-brands fa-whatsapp-square"></i></a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Display on lg, md -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
        <div class="site-blocks-cover inner-page-cover overlay d-none d-md-flex d-lg-flex d-xl-none" style="background-image: url({{ Storage::disk('public')->url('item/' . $item->item_image_blur) }});">
    @else
        <div class="site-blocks-cover inner-page-cover overlay d-none d-md-flex d-lg-flex d-xl-none" style="background-image: url({{ asset('frontend/images/placeholder/full_item_feature_image.webp') }});">
    @endif
        <div class="container">
            <div class="row align-items-center item-blocks-cover">
                <div class="col-lg-2 col-md-3" data-aos="fade-up" data-aos-delay="400">
                    @if(!empty($item->item_image_tiny))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image" class="img-fluid rounded">
                    @elseif(!empty($item->item_image))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                    @else
                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-lg-7 col-md-9" data-aos="fade-up" data-aos-delay="400">

                    <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>

                    @if($item_has_claimed)
                        <div class="row mb-2">
                            <div class="col-md-12">
                            <span class="text-primary">
                                <i class="fas fa-check-circle"></i>
                                {{ __('item_claim.claimed') }}
                            </span>
                            </div>
                        </div>
                    @endif

                    @if($item_count_rating > 0)
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="rating_stars_header"></div>
                            </div>
                            <div class="col-md-8 pl-0">
                                <span class="item-cover-address-section">
                                    @if($item_count_rating == 1)
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                    @else
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    @foreach($item_display_categories as $key => $item_category)
                        <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $item_category->category_slug) }}">
                            <span class="category">{{ $item_category->category_name }}</span>
                        </a>
                    @endforeach

                    @if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                        <a class="text-primary" href="#" data-toggle="modal" data-target="#showCategoriesModal">
                            {{ __('categories.and') . " " . strval($item_total_categories - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " ". __('categories.more') }}
                            <i class="far fa-window-restore text-primary"></i>
                        </a>
                    @endif

                    <p class="item-cover-address-section">
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                {{ $item->item_address }} <br>
                            @endif
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                        @endif
                    </p>

                    @if(!empty($item->item_phone))
                        <p class="item-cover-address-section"><i class="fas fa-phone-alt"></i> {{ $item->item_phone }}</p>
                    @endif
                    <p class="item-cover-address-section">
                        @if(!empty($item->item_website))
                            <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i class="fa-solid fa-globe"></i></a>
                        @endif
                        @if(!empty($item->item_social_facebook))
                            <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i class="fa-brands fa-facebook-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_twitter))
                            <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i class="fa-brands fa-twitter-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_linkedin))
                            <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if(!empty($item->item_social_instagram))
                            <a class="mr-1" href="https://instagram.com/_u/{{ $item->item_social_instagram }}/" target="_blank" rel="nofollow"><i class="fa-brands fa-instagram-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_whatsapp))
                            <a class="mr-1" href="https://wa.me/{{ $item->item_social_whatsapp }}" target="_blank" rel="nofollow"><i class="fa-brands fa-whatsapp-square"></i></a>
                        @endif
                    </p>

                    @guest
                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                    @else

                        @if($item->user_id != Auth::user()->id)

                            @if(Auth::user()->isAdmin())
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @else
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @endif

                        @endif

                    @endguest
                    <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                    @guest
                        <a class="btn btn-primary rounded text-white" id="item-save-button-md"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                        <form id="item-save-form-md" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                            @csrf
                        </form>
                    @else
                        @if(Auth::user()->hasSavedItem($item->id))
                            <a class="btn btn-warning rounded text-white" id="item-saved-button-md"><i class="fas fa-check"></i> {{ __('frontend.item.saved') }}</a>
                            <form id="item-unsave-form-md" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @else
                            <a class="btn btn-primary rounded text-white" id="item-save-button-md"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                            <form id="item-save-form-md" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @endif
                    @endguest

                    @if(!empty($item->item_phone))
                    <a class="btn btn-primary rounded text-white" href="tel:{{ $item->item_phone }}"><i class="fas fa-phone-alt"></i> {{ __('frontend.item.call') }}</a>
                    @endif
                    <a class="btn btn-primary rounded text-white" href="#" data-toggle="modal" data-target="#qrcodeModal"><i class="fas fa-qrcode"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Display on sm and xs -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
        <div class="site-blocks-cover site-blocks-cover-sm inner-page-cover overlay d-md-none" style="background-image: url({{ Storage::disk('public')->url('item/' . $item->item_image_blur) }});">
    @else
        <div class="site-blocks-cover site-blocks-cover-sm inner-page-cover overlay d-md-none" style="background-image: url({{ asset('frontend/images/placeholder/full_item_feature_image.webp') }});">
    @endif
        <div class="container">
            <div class="row align-items-center item-blocks-cover-sm">
                <div class="col-12" data-aos="fade-up" data-aos-delay="400">

                    <h1 class="item-cover-title-section item-cover-title-section-sm-xs">{{ $item->item_title }}</h1>

                    @if($item_has_claimed)
                        <div class="row mb-2">
                            <div class="col-md-12">
                            <span class="text-primary">
                                <i class="fas fa-check-circle"></i>
                                {{ __('item_claim.claimed') }}
                            </span>
                            </div>
                        </div>
                    @endif

                    @if($item_count_rating > 0)
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="rating_stars_header"></div>
                            </div>
                            <div class="col-6 pl-0">
                                <span class="item-cover-address-section item-cover-address-section-sm-xs">
                                    @if($item_count_rating == 1)
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                    @else
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    @foreach($item_display_categories as $key => $item_category)
                        <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $item_category->category_slug) }}">
                            <span class="category">{{ $item_category->category_name }}</span>
                        </a>
                    @endforeach

                    @if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                        <a class="text-primary" href="#" data-toggle="modal" data-target="#showCategoriesModal">
                            {{ __('categories.and') . " " . strval($item_total_categories - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " ". __('categories.more') }}
                            <i class="far fa-window-restore text-primary"></i>
                        </a>
                    @endif

                    <p class="item-cover-address-section item-cover-address-section-sm-xs">
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                {{ $item->item_address }} <br>
                            @endif
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                        @endif
                    </p>

                    @if(!empty($item->item_phone))
                        <p class="item-cover-address-section item-cover-address-section-sm-xs">
                            <i class="fas fa-phone-alt"></i> {{ $item->item_phone }}
                            <a class="btn btn-outline-primary btn-sm rounded" href="tel:{{ $item->item_phone }}">{{ __('frontend.item.call') }}</a>
                        </p>
                    @endif
                    <p class="item-cover-address-section item-cover-address-section-sm-xs">
                        @if(!empty($item->item_website))
                            <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i class="fa-solid fa-globe"></i></a>
                        @endif
                        @if(!empty($item->item_social_facebook))
                            <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i class="fa-brands fa-facebook-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_twitter))
                            <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i class="fa-brands fa-twitter-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_linkedin))
                            <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if(!empty($item->item_social_instagram))
                            <a class="mr-1" href="https://instagram.com/_u/{{ $item->item_social_instagram }}/" target="_blank" rel="nofollow"><i class="fa-brands fa-instagram-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_whatsapp))
                            <a class="mr-1" href="https://wa.me/{{ $item->item_social_whatsapp }}" target="_blank" rel="nofollow"><i class="fa-brands fa-whatsapp-square"></i></a>
                        @endif
                    </p>

                    @guest
                        <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                    @else

                        @if($item->user_id != Auth::user()->id)

                            @if(Auth::user()->isAdmin())
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @else
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @endif

                        @endif

                    @endguest
                    <a class="btn btn-primary btn-sm rounded text-white item-share-button"><i class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                    @guest
                        <a class="btn btn-primary btn-sm rounded text-white" id="item-save-button-sm"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                        <form id="item-save-form-sm" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                            @csrf
                        </form>
                    @else
                        @if(Auth::user()->hasSavedItem($item->id))
                            <a class="btn btn-warning btn-sm rounded text-white" id="item-saved-button-sm"><i class="fas fa-check"></i> {{ __('frontend.item.saved') }}</a>
                            <form id="item-unsave-form-sm" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @else
                            <a class="btn btn-primary btn-sm rounded text-white" id="item-save-button-sm"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                            <form id="item-save-form-sm" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @endif
                    @endguest
                    <a class="btn btn-primary btn-sm rounded text-white" href="#" data-toggle="modal" data-target="#qrcodeModal"><i class="fas fa-qrcode"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            @include('frontend.partials.alert')

            @if($ads_before_breadcrumb->count() > 0)
                @foreach($ads_before_breadcrumb as $ads_before_breadcrumb_key => $ad_before_breadcrumb)
                    <div class="row mb-3">
                        @if($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                            <div class="col-12 text-left">
                                <div>
                                    {!! $ad_before_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                            <div class="col-12 text-center">
                                <div>
                                    {!! $ad_before_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                            <div class="col-12 text-right">
                                <div>
                                    {!! $ad_before_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            @endif

            <div class="row mb-3">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('page.home') }}">
                                    <i class="fas fa-bars"></i>
                                    {{ __('frontend.header.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('page.categories') }}">{{ __('frontend.item.all-categories') }}</a></li>

                            @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            <li class="breadcrumb-item"><a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a></li>
                            @endif

                            <li class="breadcrumb-item active" aria-current="page">{{ $item->item_title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if($ads_after_breadcrumb->count() > 0)
                @foreach($ads_after_breadcrumb as $ads_after_breadcrumb_key => $ad_after_breadcrumb)
                    <div class="row mb-3">
                        @if($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                            <div class="col-12 text-left">
                                <div>
                                    {!! $ad_after_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                            <div class="col-12 text-center">
                                <div>
                                    {!! $ad_after_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                            <div class="col-12 text-right">
                                <div>
                                    {!! $ad_after_breadcrumb->advertisement_code !!}
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            @endif

            <div class="row">
                <div class="col-lg-8">

                    @if(Auth::check() && Auth::user()->id == $item->user_id)
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    {{ __('products.alert.this-is-your-item') }}
                                    @if(Auth::user()->isAdmin())
                                        <a class="pl-1" target="_blank" href="{{ route('admin.items.edit', $item->id) }}">
                                            <i class="fas fa-external-link-alt"></i>
                                            {{ __('products.edit-item-link') }}
                                        </a>
                                    @else
                                        <a class="pl-1" target="_blank" href="{{ route('user.items.edit', $item->id) }}">
                                            <i class="fas fa-external-link-alt"></i>
                                            {{ __('products.edit-item-link') }}
                                        </a>
                                    @endif
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- start item section after breadcrumb -->
                    @if($item_sections_after_breadcrumb->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_breadcrumb as $item_sections_after_breadcrumb_key => $after_breadcrumb_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_breadcrumb_section->item_section_title }}</h4>

                                    @php
                                        $after_breadcrumb_section_collections = $after_breadcrumb_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_breadcrumb_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_breadcrumb_section_collections as $after_breadcrumb_section_collections_key => $after_breadcrumb_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_breadcrumb_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_breadcrumb = \App\Product::find($after_breadcrumb_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_breadcrumb->product_slug]) }}">
                                                                    @if(empty($find_product_after_breadcrumb->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_breadcrumb->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_breadcrumb->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_breadcrumb->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_breadcrumb->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_breadcrumb->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_breadcrumb->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after breadcrumb -->

                    @if($ads_before_gallery->count() > 0)
                        @foreach($ads_before_gallery as $ads_before_gallery_key => $ad_before_gallery)
                            <div class="row mb-3">
                                @if($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_gallery->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_gallery->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_gallery->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            @if($item->galleries()->count() > 0)
                                <div id="item-image-gallery">
                                    @php
                                    $item_galleries = $item->galleries()->get();
                                    @endphp
                                    @foreach($item_galleries as $galleries_key => $gallery)
                                        <a href="{{ Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_name) }}" rel="item-image-gallery-thumb">
                                            <img alt="Image" src="{{ empty($gallery->item_image_gallery_thumb_name) ? Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_name) : Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_thumb_name) }}"/>
                                        </a>
                                    @endforeach
                                </div>
                            @else

                                <div class="text-center">
                                    @if(empty($item->item_image))
                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image.webp') }}" alt="Image" class="img-fluid rounded">
                                    @else
                                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                                    @endif
                                </div>

                            @endif
                            <hr>
                        </div>
                    </div>

                    <!-- start item section after gallery -->
                    @if($item_sections_after_gallery->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_gallery as $item_sections_after_gallery_key => $after_gallery_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_gallery_section->item_section_title }}</h4>

                                    @php
                                        $after_gallery_section_collections = $after_gallery_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_gallery_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_gallery_section_collections as $after_gallery_section_collections_key => $after_gallery_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_gallery_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_gallery = \App\Product::find($after_gallery_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_gallery->product_slug]) }}">
                                                                    @if(empty($find_product_after_gallery->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_gallery->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_gallery->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_gallery->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_gallery->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_gallery->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_gallery->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after gallery -->

                    @if(!empty($item->item_youtube_id))

                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('customization.item.video') }}</h4>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/{{ $item->item_youtube_id }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <hr>
                            </div>
                        </div>

                    @endif

                    @if($ads_before_description->count() > 0)
                        @foreach($ads_before_description as $ads_before_description_key => $ad_before_description)
                            <div class="row mb-3">
                                @if($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_description->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_description->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_description->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    @if(!empty($item->item_description))
                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 class="h5 mb-4 text-black">{{ __('frontend.item.description') }}</h4>
                            <p>{!! clean(nl2br($item->item_description), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                            <hr>
                        </div>
                    </div>
                    @endif

                    <!-- start item section after description -->
                    @if($item_sections_after_description->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_description as $item_sections_after_description_key => $after_description_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_description_section->item_section_title }}</h4>

                                    @php
                                        $after_description_section_collections = $after_description_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_description_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_description_section_collections as $after_description_section_collections_key => $after_description_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_description_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_description = \App\Product::find($after_description_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_description->product_slug]) }}">
                                                                    @if(empty($find_product_after_description->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_description->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_description->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_description->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_description->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_description->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_description->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after description -->

                    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                        @if($ads_before_location->count() > 0)
                            @foreach($ads_before_location as $ads_before_location_key => $ad_before_location)
                                <div class="row mb-3">
                                    @if($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_location->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_location->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_location->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('frontend.item.location') }}</h4>
                                <div class="row pt-2 pb-2">
                                    <div class="col-12">
                                        <div id="mapid-item"></div>

                                        <div class="row align-items-center pt-2">
                                            <div class="col-7">
                                                <small>
                                                    @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                                        {{ $item->item_address }}
                                                    @endif
                                                        {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                                                </small>
                                            </div>
                                            <div class="col-5 text-right">
                                                <a class="btn btn-primary btn-sm rounded text-white" href="{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $item->item_lat . ',' . $item->item_lng }}" target="_blank">
                                                    <i class="fas fa-directions"></i>
                                                    {{ __('google_map.get-directions') }}
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <!-- start item section after location map -->
                        @if($item_sections_after_location_map->count() > 0)
                            <div class="row mb-3">
                                <div class="col-12">
                                    @foreach($item_sections_after_location_map as $item_sections_after_location_map_key => $after_location_map_section)
                                        <h4 class="h5 mb-4 text-black">{{ $after_location_map_section->item_section_title }}</h4>

                                        @php
                                            $after_location_map_section_collections = $after_location_map_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                        @endphp

                                        @if($after_location_map_section_collections->count() > 0)
                                            <div class="row">
                                                @foreach($after_location_map_section_collections as $after_location_map_section_collections_key => $after_location_map_section_collection)
                                                    <div class="col-md-6 col-sm-12 mb-3">

                                                        @if($after_location_map_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                            @php
                                                                $find_product_after_location_map = \App\Product::find($after_location_map_section_collection->item_section_collection_collectible_id);
                                                            @endphp
                                                            <div class="row align-items-center border-right">
                                                                <div class="col-md-5 col-4">
                                                                    <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_location_map->product_slug]) }}">
                                                                        @if(empty($find_product_after_location_map->product_image_small))
                                                                            <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                        @else
                                                                            <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_location_map->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                                <div class="pl-0 col-md-7 col-8">

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span class="text-black">{{ str_limit($find_product_after_location_map->product_name, 20) }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span>{{ str_limit($find_product_after_location_map->product_description, 40) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            @if(!empty($find_product_after_location_map->product_price))
                                                                                <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_location_map->product_price, 2) }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-1">
                                                                        <div class="col-12">
                                                                            <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_location_map->product_slug]) }}">
                                                                                {{ __('item_section.read-more') }}
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- end item section after location map -->

                    @endif

                    @if($ads_before_features->count() > 0)
                        @foreach($ads_before_features as $ads_before_features_key => $ad_before_features)
                            <div class="row mb-3">
                                @if($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_features->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_features->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_features->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    @if($item_features->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('frontend.item.features') }}</h4>

                                @foreach($item_features as $item_features_key => $feature)

                                    <div class="row pt-2 pb-2 mt-2 mb-2 border-left {{ $item_features_key%2 == 0 ? 'bg-light' : '' }}">
                                            <div class="col-3">
                                                {{ $feature->customField->custom_field_name }}
                                            </div>

                                            <div class="col-9">
                                                @if($feature->item_feature_value)
                                                    @if($feature->customField->custom_field_type == \App\CustomField::TYPE_LINK)
                                                        @php
                                                            $parsed_url = parse_url($feature->item_feature_value);
                                                        @endphp

                                                        @if(is_array($parsed_url) && array_key_exists('host', $parsed_url))
                                                            <a target="_blank" rel=”nofollow” href="{{ $feature->item_feature_value }}">
                                                                {{ $parsed_url['host'] }}
                                                            </a>
                                                        @else
                                                            {!! clean(nl2br($feature->item_feature_value), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}
                                                        @endif

                                                    @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
                                                        @if(count(explode(',', $feature->item_feature_value)))

                                                            @foreach(explode(',', $feature->item_feature_value) as $item_feature_value_multiple_select_key => $value)
                                                                <span class="review">{{ $value }}</span>
                                                            @endforeach

                                                        @else
                                                            {{ $feature->item_feature_value }}
                                                        @endif

                                                    @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_SELECT)
                                                        {{ $feature->item_feature_value }}

                                                    @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_TEXT)
                                                        {!! clean(nl2br($feature->item_feature_value), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                @endforeach
                                <hr>
                            </div>
                        </div>
                    @endif

                    <!-- start item section after features -->
                    @if($item_sections_after_features->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_features as $item_sections_after_features_key => $after_features_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_features_section->item_section_title }}</h4>

                                    @php
                                        $after_features_section_collections = $after_features_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_features_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_features_section_collections as $after_features_section_collections_key => $after_features_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_features_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_features = \App\Product::find($after_features_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_features->product_slug]) }}">
                                                                    @if(empty($find_product_after_features->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_features->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_features->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_features->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_features->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_features->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_features->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after features -->

                    @if($ads_before_reviews->count() > 0)
                        @foreach($ads_before_reviews as $ads_before_reviews_key => $ad_before_reviews)
                            <div class="row mb-3">
                                @if($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_reviews->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_reviews->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_reviews->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 id="review-section" class="h5 mb-4 text-black">{{ __('review.frontend.reviews-cap') }}</h4>
                            @if($reviews->count() == 0)

                                @guest
                                    <div class="row mb-3 pt-3 pb-3 bg-light">
                                        <div class="col-md-12 text-center">
                                            <p class="mb-0">
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                            </p>
                                            <span>{{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}</span>

                                            <div class="row mt-2">
                                                <div class="col-md-12 text-center">
                                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    @if($item->user_id != Auth::user()->id)

                                        @if($item->reviewedByUser(Auth::user()->id))
                                            <div class="row mb-3 pt-3 pb-3 bg-light">
                                                <div class="col-md-9">
                                                    {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    @if(Auth::user()->isAdmin())
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @endif
                                                </div>
                                            </div>

                                        @else
                                            <div class="row mb-3 pt-3 pb-3 bg-light">
                                                <div class="col-md-12 text-center">
                                                    <p class="mb-0">
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                    </p>
                                                    <span>{{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}</span>

                                                    <div class="row mt-2">
                                                        <div class="col-md-12 text-center">
                                                            @if(Auth::user()->isAdmin())
                                                                <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                            @else
                                                                <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif

                                    @else
                                        <div class="row mb-3 pt-3 pb-3 bg-light">
                                            <div class="col-md-12 text-center">
                                                <span>{{ __('review.frontend.no-review', ['item_name' => $item->item_title]) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endguest

                            @else

                                <div class="row mb-3 pt-3 pb-3 bg-light">
                                    <div class="col-md-9">
                                        @guest
                                            {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                                        @else
                                            @if($item->user_id != Auth::user()->id)

                                                @if(Auth::user()->isAdmin())
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                                                    @else
                                                        {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                                                    @endif

                                                @else
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                                                    @else
                                                        {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                                                    @endif

                                                @endif

                                            @else
                                                {{ __('review.frontend.my-reviews') }}
                                            @endif
                                        @endguest
                                    </div>
                                    <div class="col-md-3 text-right">
                                        @guest
                                            <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                        @else

                                            @if($item->user_id != Auth::user()->id)

                                                @if(Auth::user()->isAdmin())
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                    @endif

                                                @else
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                    @endif

                                                @endif

                                            @endif

                                        @endguest
                                    </div>
                                </div>

                                <!-- Start review summary -->
                                @if($item_count_rating > 0)

                                    <div class="row mt-4 mb-3">
                                        <div class="col-12 text-right">
                                            <form action="{{ route('page.item', ['item_slug' => $item->item_slug]) }}#review-section" method="GET" class="form-inline" id="item-rating-sort-by-form">
                                                <div class="form-group">
                                                    <label for="rating_sort_by">{{ __('rating_summary.sort-by') }}</label>
                                                    <select class="custom-select ml-2 @error('rating_sort_by') is-invalid @enderror" name="rating_sort_by" id="rating_sort_by">
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_NEWEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_NEWEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-newest') }}</option>
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_OLDEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_OLDEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-oldest') }}</option>
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_HIGHEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_HIGHEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-highest') }}</option>
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_LOWEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_LOWEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-lowest') }}</option>
                                                    </select>
                                                    @error('rating_sort_by')
                                                    <span class="invalid-tooltip">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-3 bg-primary text-white">
                                            <div id="review_summary">
                                                <strong>{{ number_format($item_average_rating, 1) }}</strong>
                                                @if($item_count_rating > 1)
                                                    <small>{{ __('rating_summary.based-on-reviews', ['item_rating_count' => $item_count_rating]) }}</small>
                                                @else
                                                    <small>{{ __('rating_summary.based-on-review', ['item_rating_count' => $item_count_rating]) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <!-- Rating Progeress Bar -->
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_one_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.1-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_two_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.2-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_three_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.3-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_four_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.4-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_five_star_percentage }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.5-stars') }}</strong></small></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endif
                                <!-- End review summary -->

                                @foreach($reviews as $reviews_key => $review)
                                    <div class="row mb-3">
                                        <div class="col-md-4">

                                            <div class="row align-items-center mb-3">
                                                <div class="col-4">
                                                    @if(empty(\App\User::find($review->author_id)->user_image))
                                                        <img src="{{ asset('frontend/images/placeholder/profile-'. intval($review->author_id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                    @else
                                                        <img src="{{ Storage::disk('public')->url('user/' . \App\User::find($review->author_id)->user_image) }}" alt="{{ \App\User::find($review->author_id)->name }}" class="img-fluid rounded-circle">
                                                    @endif
                                                </div>
                                                <div class="col-8 pl-0">
                                                    <span>{{ \App\User::find($review->author_id)->name }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <span>{{ __('review.backend.overall-rating') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_{{ $review->id }}" data-id="rating_stars_{{ $review->id }}" data-rating="{{ $review->rating }}"></div>
                                                </div>
                                            </div>

                                            @if($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES)
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                <span class="bg-success text-white pl-2 pr-2 pt-2 pb-2 rounded">
                                                    <i class="fas fa-check"></i>
                                                    {{ __('review.backend.recommend') }}
                                                </span>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="col-md-8">

                                            <div class="row mb-0">
                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.customer-service') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_customer_service_{{ $review->id }}" data-id="rating_stars_customer_service_{{ $review->id }}" data-rating="{{ $review->customer_service_rating }}"></div>
                                                </div>

                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.quality') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_quality_{{ $review->id }}" data-id="rating_stars_quality_{{ $review->id }}" data-rating="{{ $review->quality_rating }}"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.friendly') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_friendly_{{ $review->id }}" data-id="rating_stars_friendly_{{ $review->id }}" data-rating="{{ $review->friendly_rating }}"></div>
                                                </div>

                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.pricing') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_pricing_{{ $review->id }}" data-id="rating_stars_pricing_{{ $review->id }}" data-rating="{{ $review->pricing_rating }}"></div>
                                                </div>
                                            </div>

                                            @if(!empty($review->title))
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        <span class="text-black">{{ $review->title }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row mb-1">
                                                <div class="col-md-12">
                                                    <p>{!! clean(nl2br($review->body), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                                                </div>
                                            </div>

                                            @if($item->reviewGalleryCountByReviewId($review->id))
                                                <div class="row mb-1">
                                                    <div class="col-md-12" id="review-image-gallery-{{ $review->id }}">
                                                        @foreach($item->getReviewGalleriesByReviewId($review->id) as $key_1 => $review_image_gallery)
                                                            <a href="{{ Storage::disk('public')->url('item/review/' . $review_image_gallery->review_image_gallery_name) }}" rel="review-image-gallery-thumb{{ $review->id }}">
                                                                <img alt="Image" src="{{ Storage::disk('public')->url('item/review/' . $review_image_gallery->review_image_gallery_thumb_name) }}"/>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <span class="review font-size-13">{{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                                    @if($review->created_at != $review->updated_at)
                                                        <span class="review font-size-13">{{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($review->updated_at)->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- start item section after reviews -->
                    @if($item_sections_after_reviews->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_reviews as $item_sections_after_reviews_key => $after_reviews_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_reviews_section->item_section_title }}</h4>

                                    @php
                                        $after_reviews_section_collections = $after_reviews_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_reviews_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_reviews_section_collections as $after_reviews_section_collections_key => $after_reviews_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_reviews_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_reviews = \App\Product::find($after_reviews_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_reviews->product_slug]) }}">
                                                                    @if(empty($find_product_after_reviews->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_reviews->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_reviews->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_reviews->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_reviews->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_reviews->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_reviews->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after reviews -->

                    @if($ads_before_comments->count() > 0)
                        @foreach($ads_before_comments as $ads_before_comments_key => $ad_before_comments)
                            <div class="row mb-3">
                                @if($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_comments->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_comments->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_comments->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 class="h5 mb-4 text-black">{{ __('frontend.item.comments') }}</h4>

                            @comments([
                            'model' => $item,
                            'approved' => true,
                            'perPage' => 10
                            ])

                            <hr>
                        </div>
                    </div>

                    <!-- start item section after comments -->
                    @if($item_sections_after_comments->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_comments as $item_sections_after_comments_key => $after_comments_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_comments_section->item_section_title }}</h4>

                                    @php
                                        $after_comments_section_collections = $after_comments_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_comments_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_comments_section_collections as $after_comments_section_collections_key => $after_comments_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_comments_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_comments = \App\Product::find($after_comments_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_comments->product_slug]) }}">
                                                                    @if(empty($find_product_after_comments->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_comments->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_comments->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_comments->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_comments->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_comments->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_comments->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after comments -->

                    @if($ads_before_share->count() > 0)
                        @foreach($ads_before_share as $ads_before_share_key => $ad_before_share)
                            <div class="row mb-3">
                                @if($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 class="h5 mb-4 text-black">{{ __('frontend.item.share') }}</h4>
                            <div class="row">
                                <div class="col-12">

                                    <!-- Create link with share to Facebook -->
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href="" data-social="facebook">
                                        <i class="fab fa-facebook-f"></i>
                                        {{ __('social_share.facebook') }}
                                    </a>

                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href="" data-social="twitter">
                                        <i class="fab fa-twitter"></i>
                                        {{ __('social_share.twitter') }}
                                    </a>

                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href="" data-social="linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                        {{ __('social_share.linkedin') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href="" data-social="blogger">
                                        <i class="fab fa-blogger-b"></i>
                                        {{ __('social_share.blogger') }}
                                    </a>

                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href="" data-social="pinterest">
                                        <i class="fab fa-pinterest-p"></i>
                                        {{ __('social_share.pinterest') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href="" data-social="evernote">
                                        <i class="fab fa-evernote"></i>
                                        {{ __('social_share.evernote') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href="" data-social="reddit">
                                        <i class="fab fa-reddit-alien"></i>
                                        {{ __('social_share.reddit') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href="" data-social="buffer">
                                        <i class="fab fa-buffer"></i>
                                        {{ __('social_share.buffer') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href="" data-social="wordpress">
                                        <i class="fab fa-wordpress-simple"></i>
                                        {{ __('social_share.wordpress') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                                        <i class="fab fa-weibo"></i>
                                        {{ __('social_share.weibo') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                                        <i class="fab fa-skype"></i>
                                        {{ __('social_share.skype') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href="" data-social="telegram">
                                        <i class="fab fa-telegram-plane"></i>
                                        {{ __('social_share.telegram') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                                        <i class="fab fa-viber"></i>
                                        {{ __('social_share.viber') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href="" data-social="whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                        {{ __('social_share.whatsapp') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href="" data-social="wechat">
                                        <i class="fab fa-weixin"></i>
                                        {{ __('social_share.wechat') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                                        <i class="fab fa-line"></i>
                                        {{ __('social_share.line') }}
                                    </a>

                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>

                    @if($ads_after_share->count() > 0)
                        @foreach($ads_after_share as $ads_after_share_key => $ad_after_share)
                            <div class="row mb-3">
                                @if($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_after_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_after_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_after_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <!-- start item section after share -->
                    @if($item_sections_after_share->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_share as $item_sections_after_share_key => $after_share_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_share_section->item_section_title }}</h4>

                                    @php
                                        $after_share_section_collections = $after_share_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_share_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_share_section_collections as $after_share_section_collections_key => $after_share_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_share_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_share = \App\Product::find($after_share_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_share->product_slug]) }}">
                                                                    @if(empty($find_product_after_share->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_share->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_share->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_share->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_share->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_share->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_share->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after share -->

                    @if(!$item_has_claimed)
                    <div class="row pt-3 pb-3 pl-2 pr-2 border border-dark rounded bg-light align-items-center">
                        <div class="col-sm-12 col-md-9 pr-0">
                            <h4 class="h5 text-black">{{ __('item_claim.claim-business') }}</h4>
                            <span>{{ __('item_claim.unclaimed-desc') }}</span>
                        </div>
                        <div class="col-sm-12 col-md-3 text-right">
                            @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin())
                                <a class="btn btn-primary rounded text-white" href="{{ route('admin.item-claims.create', ['item_slug' => $item->item_slug]) }}" target="_blank">{{ __('item_claim.claim-business-button') }}</a>
                            @else
                                <a class="btn btn-primary rounded text-white" href="{{ route('user.item-claims.create', ['item_slug' => $item->item_slug]) }}" target="_blank">{{ __('item_claim.claim-business-button') }}</a>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

                <div class="col-lg-3 ml-auto">

                    <div class="pt-3">

                        @if($ads_before_sidebar_content->count() > 0)
                            @foreach($ads_before_sidebar_content as $ads_before_sidebar_content_key => $ad_before_sidebar_content)
                                <div class="row mb-5">
                                    @if($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                        <div class="row mb-2 align-items-center">
                            <div class="col-12">
                                <h3 class="h5 text-black">{{ __('item_hour.item-hours') }}</h3>
                            </div>
                        </div>

                        @if($item_hours->count() > 0)
                        <div class="row">
                            <div class="col-12 pl-0 pr-0">
                                @if($current_open_range)
                                    <div class="alert alert-success" role="alert">
                                        <i class="fas fa-door-open"></i>
                                        {{ __('item_hour.item-open-since') . ' ' . $current_open_range->start() . '.' }}
                                        {{ __('item_hour.item-will-close') . ' ' . $current_open_range->end() . '.' }}
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fas fa-exclamation-circle"></i>

                                        @php
                                            $previous_close_datetime = $opening_hours_obj->previousClose($datetime_now);
                                            $next_open_datetime = $opening_hours_obj->nextOpen($datetime_now);

                                            $previous_close_day_of_week = intval($previous_close_datetime->format('N'));
                                            $next_open_day_of_week = intval($next_open_datetime->format('N'));
                                        @endphp

                                        {{ __('item_hour.item-closed-since') }}

                                        @if($previous_close_day_of_week == 1)
                                            {{ __('item_hour.monday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 2)
                                            {{ __('item_hour.tuesday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 3)
                                            {{ __('item_hour.wednesday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 4)
                                            {{ __('item_hour.thursday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 5)
                                            {{ __('item_hour.friday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 6)
                                            {{ __('item_hour.saturday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 7)
                                            {{ __('item_hour.sunday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @endif

                                        {{ __('item_hour.item-will-re-open') }}

                                        @if($next_open_day_of_week == 1)
                                            {{ __('item_hour.monday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 2)
                                            {{ __('item_hour.tuesday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 3)
                                            {{ __('item_hour.wednesday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 4)
                                            {{ __('item_hour.thursday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 5)
                                            {{ __('item_hour.friday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 6)
                                            {{ __('item_hour.saturday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 7)
                                            {{ __('item_hour.sunday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @endif

                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-12">

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.monday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_monday) > 0)
                                            @foreach($item_hours_monday as $item_hours_monday_key => $an_item_hours_monday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_monday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.tuesday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_tuesday) > 0)
                                            @foreach($item_hours_tuesday as $item_hours_tuesday_key => $an_item_hours_tuesday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_tuesday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.wednesday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_wednesday) > 0)
                                            @foreach($item_hours_wednesday as $item_hours_wednesday_key => $an_item_hours_wednesday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_wednesday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.thursday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_thursday) > 0)
                                            @foreach($item_hours_thursday as $item_hours_thursday_key => $an_item_hours_thursday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_thursday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.friday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_friday) > 0)
                                            @foreach($item_hours_friday as $item_hours_friday_key => $an_item_hours_friday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_friday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.saturday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_saturday) > 0)
                                            @foreach($item_hours_saturday as $item_hours_saturday_key => $an_item_hours_saturday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_saturday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.sunday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_sunday) > 0)
                                            @foreach($item_hours_sunday as $item_hours_sunday_key => $an_item_hours_sunday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_sunday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(count($item_hour_exceptions_obj) > 0)
                                <div class="row pt-1 pb-1">
                                    <div class="col-12">
                                        <a class="text-secondary" href="#" data-toggle="modal" data-target="#itemHourExceptionsModal">
                                            <i class="far fa-window-restore"></i>
                                            {{ __('item_hour.item-hour-exceptions-link') }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                        @endif

                        @if(\Illuminate\Support\Facades\Auth::check())
                            @if(\Illuminate\Support\Facades\Auth::user()->id != $item->user_id)
                                <div class="row mb-2 align-items-center">
                                    <div class="col-12">
                                        <h3 class="h5 text-black">{{ __('backend.message.message-txt') }}</h3>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                    @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                                        <!-- message item owner contact form -->
                                        <form method="POST" action="{{ route('admin.messages.store') }}" class="">
                                            @csrf

                                            <input type="hidden" name="recipient" value="{{ $item->user_id }}">
                                            <input type="hidden" name="item" value="{{ $item->id }}">
                                            <div class="form-group">
                                                <input id="subject" type="text" class="form-control rounded @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}" placeholder="{{ __('backend.message.subject') }}">
                                                @error('subject')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <textarea rows="6" id="message" type="text" class="form-control rounded @error('message') is-invalid @enderror" name="message" placeholder="{{ __('backend.message.message-txt') }}">{{ old('message') }}</textarea>
                                                @error('message')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary btn-block rounded">
                                                    {{ __('frontend.item.send-message') }}
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <!-- message item owner contact form -->
                                        <form method="POST" action="{{ route('user.messages.store') }}" class="">
                                            @csrf

                                            <input type="hidden" name="recipient" value="{{ $item->user_id }}">
                                            <input type="hidden" name="item" value="{{ $item->id }}">
                                            <div class="form-group">
                                                <input id="subject" type="text" class="form-control rounded @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}" placeholder="{{ __('backend.message.subject') }}">
                                                @error('subject')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <textarea rows="6" id="message" type="text" class="form-control rounded @error('message') is-invalid @enderror" name="message" placeholder="{{ __('backend.message.message-txt') }}">{{ old('message') }}</textarea>
                                                @error('message')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary btn-block rounded">
                                                    {{ __('frontend.item.send-message') }}
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="row mb-2 align-items-center">
                            <div class="col-12">
                                <h3 class="h5 text-black">{{ __('rating_summary.managed-by') }}</h3>
                            </div>
                        </div>

                        <div class="row align-items-center mb-4">
                            <div class="col-4">
                                @if(empty($item->user->user_image))
                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                @else

                                    <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                @endif
                            </div>
                            <div class="col-8 pl-0">
                                <span class="font-size-13">{{ $item->user->name }}</span><br/>
                                <span class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if(!\Illuminate\Support\Facades\Auth::check())
                            <div class="row mb-4 align-items-center">
                                <div class="col-12">
                                    <a class="btn btn-primary btn-block rounded text-white" href="#" data-toggle="modal" data-target="#itemLeadModal">{{ __('rating_summary.contact') }}</a>
                                </div>
                            </div>
                        @endif

                        <hr>

                        @include('frontend.partials.search.side')

                        @if($ads_after_sidebar_content->count() > 0)
                            @foreach($ads_after_sidebar_content as $ads_after_sidebar_content_key => $ad_after_sidebar_content)
                                <div class="row mt-5">
                                    @if($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_after_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_after_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_after_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                    </div>

                </div>

            </div>
        </div>
    </div>

    @if($similar_items->count() > 0 || $nearby_items->count() > 0)
    <div class="site-section bg-light">
        <div class="container">

        @if($similar_items->count() > 0)
        <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.item.similar-listings') }}</h2>
            </div>
        </div>
        <div class="row">

            @foreach($similar_items as $similar_items_key => $similar_item)
                <div class="col-lg-6">
                    <div class="d-block d-md-flex listing">
                        <a href="{{ route('page.item', $similar_item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($similar_item->item_image_small) ? Storage::disk('public')->url('item/' . $similar_item->item_image_small) : (!empty($similar_item->item_image) ? Storage::disk('public')->url('item/' . $similar_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')) }})"></a>
                        <div class="lh-content">

                            @foreach($similar_item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY) as $similar_item_all_categories_key => $category)
                                <a href="{{ route('page.category', $category->category_slug) }}">
                                    <span class="category">
                                        @if(!empty($category->category_icon))
                                            <i class="{{ $category->category_icon }}"></i>
                                        @else
                                            <i class="fa-solid fa-heart"></i>
                                        @endif
                                        {{ $category->category_name }}
                                    </span>
                                </a>
                            @endforeach

                            @if($similar_item->allCategories()->count() > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                                <span class="category">{{ __('categories.and') . " " . strval($similar_item->allCategories()->count() - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " " . __('categories.more') }}</span>
                            @endif

                            <h3 class="pt-2"><a href="{{ route('page.item', $similar_item->item_slug) }}">{{ $similar_item->item_title }}</a></h3>

                            @if($similar_item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            <address>
                                <a href="{{ route('page.city', ['state_slug'=>$similar_item->state->state_slug, 'city_slug'=>$similar_item->city->city_slug]) }}">{{ $similar_item->city->city_name }}</a>,
                                <a href="{{ route('page.state', ['state_slug'=>$similar_item->state->state_slug]) }}">{{ $similar_item->state->state_name }}</a>
                            </address>
                            @endif

                            @if($similar_item->getCountRating() > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="pl-0 rating_stars rating_stars_{{ $similar_item->item_slug }}" data-id="rating_stars_{{ $similar_item->item_slug }}" data-rating="{{ $similar_item->item_average_rating }}"></div>
                                        <address class="mt-1">
                                            @if($similar_item->getCountRating() == 1)
                                                {{ '(' . $similar_item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                            @else
                                                {{ '(' . $similar_item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                            @endif
                                        </address>
                                    </div>
                                </div>
                            @endif

                            <hr class="item-box-hr">

                            <div class="row align-items-center">

                                <div class="col-5 col-md-7 pr-0">
                                    <div class="row align-items-center item-box-user-div">
                                        <div class="col-3 item-box-user-img-div">
                                            @if(empty($similar_item->user->user_image))
                                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($similar_item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else
                                                <img src="{{ Storage::disk('public')->url('user/' . $similar_item->user->user_image) }}" alt="{{ $similar_item->user->name }}" class="img-fluid rounded-circle">
                                            @endif
                                        </div>
                                        <div class="col-9 line-height-1-2 item-box-user-name-div">
                                            <div class="row pb-1">
                                                <div class="col-12">
                                                    <span class="font-size-13">{{ str_limit($similar_item->user->name, 14, '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="row line-height-1-0">
                                                <div class="col-12">
                                                    <span class="review">{{ $similar_item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-7 col-md-5 pl-0 text-right">
                                    @if($similar_item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                        @if($similar_item->hasOpened())
                                            <span class="item-box-hour-span-opened">{{ __('item_hour.frontend-item-box-hour-opened') }}</span>
                                        @else
                                            <span class="item-box-hour-span-closed">{{ __('item_hour.frontend-item-box-hour-closed') }}</span>
                                        @endif
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        @endif

        @if($nearby_items->count() > 0)
        <div class="row mb-4 mt-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.item.nearby-listings') }}</h2>
            </div>
        </div>
        <div class="row">

            @foreach($nearby_items as $nearby_items_key => $nearby_item)
                <div class="col-lg-6">
                    <div class="d-block d-md-flex listing">
                        <a href="{{ route('page.item', $nearby_item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($nearby_item->item_image_small) ? Storage::disk('public')->url('item/' . $nearby_item->item_image_small) : (!empty($nearby_item->item_image) ? Storage::disk('public')->url('item/' . $nearby_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')) }})"></a>
                        <div class="lh-content">

                            @foreach($nearby_item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY) as $nearby_item_all_categories_key => $category)
                                <a href="{{ route('page.category', $category->category_slug) }}">
                                    <span class="category">
                                        @if(!empty($category->category_icon))
                                            <i class="{{ $category->category_icon }}"></i>
                                        @else
                                            <i class="fa-solid fa-heart"></i>
                                        @endif
                                        {{ $category->category_name }}
                                    </span>
                                </a>
                            @endforeach

                            @if($nearby_item->allCategories()->count() > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                                <span class="category">{{ __('categories.and') . " " . strval($nearby_item->allCategories()->count() - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " " . __('categories.more') }}</span>
                            @endif

                            <h3 class="pt-2"><a href="{{ route('page.item', $nearby_item->item_slug) }}">{{ $nearby_item->item_title }}</a></h3>

                            @if($nearby_item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            <address>
                                <a href="{{ route('page.city', ['state_slug'=>$nearby_item->state->state_slug, 'city_slug'=>$nearby_item->city->city_slug]) }}">{{ $nearby_item->city->city_name }}</a>,
                                <a href="{{ route('page.state', ['state_slug'=>$nearby_item->state->state_slug]) }}">{{ $nearby_item->state->state_name }}</a>
                            </address>
                            @endif

                            @if($nearby_item->getCountRating() > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="pl-0 rating_stars rating_stars_{{ $nearby_item->item_slug }}" data-id="rating_stars_{{ $nearby_item->item_slug }}" data-rating="{{ $nearby_item->item_average_rating }}"></div>
                                        <address class="mt-1">
                                            @if($nearby_item->getCountRating() == 1)
                                                {{ '(' . $nearby_item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                            @else
                                                {{ '(' . $nearby_item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                            @endif
                                        </address>
                                    </div>
                                </div>
                            @endif

                            <hr class="item-box-hr">

                            <div class="row align-items-center">

                                <div class="col-5 col-md-7 pr-0">
                                    <div class="row align-items-center item-box-user-div">
                                        <div class="col-3 item-box-user-img-div">
                                            @if(empty($nearby_item->user->user_image))
                                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($nearby_item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else
                                                <img src="{{ Storage::disk('public')->url('user/' . $nearby_item->user->user_image) }}" alt="{{ $nearby_item->user->name }}" class="img-fluid rounded-circle">
                                            @endif
                                        </div>
                                        <div class="col-9 line-height-1-2 item-box-user-name-div">
                                            <div class="row pb-1">
                                                <div class="col-12">
                                                    <span class="font-size-13">{{ str_limit($nearby_item->user->name, 14, '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="row line-height-1-0">
                                                <div class="col-12">
                                                    <span class="review">{{ $nearby_item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-7 col-md-5 pl-0 text-right">
                                    @if($nearby_item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                        @if($nearby_item->hasOpened())
                                            <span class="item-box-hour-span-opened">{{ __('item_hour.frontend-item-box-hour-opened') }}</span>
                                        @else
                                            <span class="item-box-hour-span-closed">{{ __('item_hour.frontend-item-box-hour-closed') }}</span>
                                        @endif
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        @endif

    </div>
    </div>
    @endif

<!-- Modal - share -->
<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('frontend.item.share-listing') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">

                        <p>{{ __('frontend.item.share-listing-social-media') }}</p>

                        <!-- Create link with share to Facebook -->
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href="" data-social="facebook">
                            <i class="fab fa-facebook-f"></i>
                            {{ __('social_share.facebook') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href="" data-social="twitter">
                            <i class="fab fa-twitter"></i>
                            {{ __('social_share.twitter') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href="" data-social="linkedin">
                            <i class="fab fa-linkedin-in"></i>
                            {{ __('social_share.linkedin') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href="" data-social="blogger">
                            <i class="fab fa-blogger-b"></i>
                            {{ __('social_share.blogger') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href="" data-social="pinterest">
                            <i class="fab fa-pinterest-p"></i>
                            {{ __('social_share.pinterest') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href="" data-social="evernote">
                            <i class="fab fa-evernote"></i>
                            {{ __('social_share.evernote') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href="" data-social="reddit">
                            <i class="fab fa-reddit-alien"></i>
                            {{ __('social_share.reddit') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href="" data-social="buffer">
                            <i class="fab fa-buffer"></i>
                            {{ __('social_share.buffer') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href="" data-social="wordpress">
                            <i class="fab fa-wordpress-simple"></i>
                            {{ __('social_share.wordpress') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                            <i class="fab fa-weibo"></i>
                            {{ __('social_share.weibo') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                            <i class="fab fa-skype"></i>
                            {{ __('social_share.skype') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href="" data-social="telegram">
                            <i class="fab fa-telegram-plane"></i>
                            {{ __('social_share.telegram') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                            <i class="fab fa-viber"></i>
                            {{ __('social_share.viber') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href="" data-social="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            {{ __('social_share.whatsapp') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href="" data-social="wechat">
                            <i class="fab fa-weixin"></i>
                            {{ __('social_share.wechat') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                            <i class="fab fa-line"></i>
                            {{ __('social_share.line') }}
                        </a>

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <p>{{ __('frontend.item.share-listing-email') }}</p>
                        @if(!Auth::check())
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ __('frontend.item.login-require') }}
                                </div>
                            </div>
                        </div>
                        @endif
                        <form action="{{ route('page.item.email', ['item_slug' => $item->item_slug]) }}" method="POST">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label for="item_share_email_name" class="text-black">{{ __('frontend.item.name') }}</label>
                                    <input id="item_share_email_name" type="text" class="form-control @error('item_share_email_name') is-invalid @enderror" name="item_share_email_name" value="{{ old('item_share_email_name') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('item_share_email_name')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="item_share_email_from_email" class="text-black">{{ __('frontend.item.email') }}</label>
                                    <input id="item_share_email_from_email" type="email" class="form-control @error('item_share_email_from_email') is-invalid @enderror" name="item_share_email_from_email" value="{{ old('item_share_email_from_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('item_share_email_from_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="item_share_email_to_email" class="text-black">{{ __('frontend.item.email-to') }}</label>
                                    <input id="item_share_email_to_email" type="email" class="form-control @error('item_share_email_to_email') is-invalid @enderror" name="item_share_email_to_email" value="{{ old('item_share_email_to_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('item_share_email_to_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <label for="item_share_email_note" class="text-black">{{ __('frontend.item.add-note') }}</label>
                                    <textarea class="form-control @error('item_share_email_note') is-invalid @enderror" id="item_share_email_note" rows="3" name="item_share_email_note" {{ Auth::check() ? '' : 'disabled' }}>{{ old('item_share_email_note') }}</textarea>
                                    @error('item_share_email_note')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded" {{ Auth::check() ? '' : 'disabled' }}>
                                        {{ __('frontend.item.send-email') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

@if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
<!-- Modal show categories -->
<div class="modal fade" id="showCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="showCategoriesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('categories.all-cat') . " - " . $item->item_title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        @foreach($item_all_categories as $item_all_categories_key => $a_category)

                            <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $a_category->category_slug) }}">
                                <span class="category">{{ $a_category->category_name }}</span>
                            </a>

                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endif

@if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
@if(count($item_hour_exceptions_obj) > 0)
<div class="modal fade" id="itemHourExceptionsModal" tabindex="-1" role="dialog" aria-labelledby="itemHourExceptionsModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('item_hour.modal-item-hour-exceptions-title')  }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <p>{{ $item->item_title . ' ' . __('item_hour.modal-item-hour-exceptions-description') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">

                        @foreach($item_hour_exceptions_obj as $item_hour_exceptions_obj_key => $item_hour_exception)
                        <div class="row pt-1 pb-1 bg-light mb-1 align-items-center">
                            <div class="col-4">
                                <i class="far fa-calendar-alt"></i>
                                {{ $item_hour_exceptions_obj_key }}
                            </div>
                            <div class="col-8 text-right">
                                @php
                                    $item_hour_exception_iterator = $item_hour_exception->getIterator();
                                @endphp

                                @if(count($item_hour_exception_iterator) > 0)
                                    @foreach($item_hour_exception_iterator as $item_hour_exception_iterator_key => $an_item_hour_exception_iterator)
                                        <i class="far fa-clock"></i>
                                        @if(count($item_hour_exception_iterator) - 1 == $item_hour_exception_iterator_key)
                                            {{ $an_item_hour_exception_iterator }}
                                        @else
                                            {{ $an_item_hour_exception_iterator . ', ' }}
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('item_hour.item-closed') }}
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('importer_csv.error-notify-modal-close') }}</button>
            </div>
        </div>
    </div>
</div>
@endif
@endif

<!-- QR Code modal -->
<div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="qrcodeModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('theme_directory_hub.listing.qr-code')  }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div id="item-qrcode"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('importer_csv.error-notify-modal-close') }}</button>
            </div>
        </div>
    </div>
</div>

@if(!\Illuminate\Support\Facades\Auth::check())
<div class="modal fade" id="itemLeadModal" tabindex="-1" role="dialog" aria-labelledby="itemLeadModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('rating_summary.contact') . ' ' . $item->item_title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('page.item.lead.store', ['item_slug' => $item->item_slug]) }}" method="POST">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_name" class="text-black">{{ __('role_permission.item-leads.item-lead-name') }}</label>
                                    <input id="item_lead_name" type="text" class="form-control @error('item_lead_name') is-invalid @enderror" name="item_lead_name" value="{{ old('item_lead_name') }}">
                                    @error('item_lead_name')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_email" class="text-black">{{ __('role_permission.item-leads.item-lead-email') }}</label>
                                    <input id="item_lead_email" type="text" class="form-control @error('item_lead_email') is-invalid @enderror" name="item_lead_email" value="{{ old('item_lead_email') }}">
                                    @error('item_lead_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_phone" class="text-black">{{ __('role_permission.item-leads.item-lead-phone') }}</label>
                                    <input id="item_lead_phone" type="text" class="form-control @error('item_lead_phone') is-invalid @enderror" name="item_lead_phone" value="{{ old('item_lead_phone') }}">
                                    @error('item_lead_phone')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_subject" class="text-black">{{ __('role_permission.item-leads.item-lead-subject') }}</label>
                                    <input id="item_lead_subject" type="text" class="form-control @error('item_lead_subject') is-invalid @enderror" name="item_lead_subject" value="{{ old('item_lead_subject') }}">
                                    @error('item_lead_subject')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <label for="item_lead_message" class="text-black">{{ __('role_permission.item-leads.item-lead-message') }}</label>
                                    <textarea class="form-control @error('item_lead_message') is-invalid @enderror" id="item_lead_message" rows="3" name="item_lead_message">{{ old('item_lead_message') }}</textarea>
                                    @error('item_lead_message')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Start Google reCAPTCHA version 2 -->
                            @if($site_global_settings->setting_site_recaptcha_item_lead_enable == \App\Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    {!! htmlFormSnippet() !!}
                                    @error('g-recaptcha-response')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            <!-- End Google reCAPTCHA version 2 -->

                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded">
                                        {{ __('rating_summary.contact') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')

    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR && $site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    <script src="{{ asset('frontend/vendor/justified-gallery/jquery.justifiedGallery.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/colorbox/jquery.colorbox-min.js') }}"></script>

    <script src="{{ asset('frontend/vendor/goodshare/goodshare.min.js') }}"></script>

    <script src="{{ asset('frontend/vendor/jquery-qrcode/jquery-qrcode-0.18.0.min.js') }}"></script>

    <script>
        $(document).ready(function(){

            "use strict";

            /**
             * Start initial map
             */
            @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP && $item->item_type == \App\Item::ITEM_TYPE_REGULAR)
            var map = L.map('mapid-item', {
                center: [{{ $item->item_lat }}, {{ $item->item_lng }}],
                zoom: 13,
                scrollWheelZoom: false,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([{{ $item->item_lat }}, {{ $item->item_lng }}]).addTo(map);
            @endif
            /**
             * End initial map
             */


            /**
             * Start initial image gallery justify gallery
             */
            @if($item->galleries()->count() > 0)
            $("#item-image-gallery").justifiedGallery({
                rowHeight : 150,
                maxRowHeight: 180,
                lastRow : 'nojustify',
                margins : 3,
                captions: false,
                randomize: true,
                rel : 'item-image-gallery-thumb', //replace with 'gallery1' the rel attribute of each link
            }).on('jg.complete', function () {
                $(this).find('a').colorbox({
                    maxWidth : '95%',
                    maxHeight : '95%',
                    opacity : 0.8,
                });
            });
            @endif
            /**
             * End initial image gallery justify gallery
             */

            /**
             * Start initial review image gallery justify gallery
             */
            @foreach($reviews as $reviews_key => $review)
            @if($item->reviewGalleryCountByReviewId($review->id))
            $("#review-image-gallery-{{ $review->id }}").justifiedGallery({
                rowHeight : 80,
                maxRowHeight: 100,
                lastRow : 'nojustify',
                margins : 3,
                captions: false,
                randomize: true,
                rel : 'review-image-gallery-thumb-{{ $review->id }}', //replace with 'gallery1' the rel attribute of each link
            }).on('jg.complete', function () {
                $(this).find('a').colorbox({
                    maxWidth : '95%',
                    maxHeight : '95%',
                    opacity : 0.8,
                });
            });
            @endif
            @endforeach
            /**
             * End initial review image gallery justify gallery
             */

            /**
             * Start initial share button and share modal
             */
            $('.item-share-button').on('click', function(){
                $('#share-modal').modal('show');
            });

            @error('item_share_email_name')
            $('#share-modal').modal('show');
            @enderror

            @error('item_share_email_from_email')
            $('#share-modal').modal('show');
            @enderror

            @error('item_share_email_to_email')
            $('#share-modal').modal('show');
            @enderror

            @error('item_share_email_note')
            $('#share-modal').modal('show');
            @enderror
            /**
             * End initial share button and share modal
             */

            /**
             * Start initial listing lead modal
             */
            @error('item_lead_name')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_email')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_phone')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_subject')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_message')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('g-recaptcha-response')
            $('#itemLeadModal').modal('show');
            @enderror
            /**
             * End initial listing lead modal
             */

            /**
             * Start initial save button
             */
            // xl view
            $('#item-save-button-xl').on('click', function(){
                $("#item-save-button-xl").addClass("disabled");
                $("#item-save-form-xl").submit();
            });

            $('#item-saved-button-xl').on('click', function(){
                $("#item-saved-button-xl").off("mouseenter");
                $("#item-saved-button-xl").off("mouseleave");
                $("#item-saved-button-xl").addClass("disabled");
                $("#item-unsave-form-xl").submit();
            });

            $("#item-saved-button-xl").on('mouseenter', function(){
                $("#item-saved-button-xl").attr("class", "btn btn-danger rounded text-white");
                $("#item-saved-button-xl").html("<i class=\"far fa-trash-alt\"></i> <?php echo __('frontend.item.unsave') ?>");
            });

            $("#item-saved-button-xl").on('mouseleave', function(){
                $("#item-saved-button-xl").attr("class", "btn btn-warning rounded text-white");
                $("#item-saved-button-xl").html("<i class=\"fas fa-check\"></i> <?php echo __('frontend.item.saved') ?>");
            });

            // md view
            $('#item-save-button-md').on('click', function(){
                $("#item-save-button-md").addClass("disabled");
                $("#item-save-form-md").submit();
            });

            $('#item-saved-button-md').on('click', function(){
                $("#item-saved-button-md").off("mouseenter");
                $("#item-saved-button-md").off("mouseleave");
                $("#item-saved-button-md").addClass("disabled");
                $("#item-unsave-form-md").submit();
            });

            $("#item-saved-button-md").on('mouseenter', function(){
                $("#item-saved-button-md").attr("class", "btn btn-danger rounded text-white");
                $("#item-saved-button-md").html("<i class=\"far fa-trash-alt\"></i> <?php echo __('frontend.item.unsave') ?>");
            });

            $("#item-saved-button-md").on('mouseleave', function(){
                $("#item-saved-button-md").attr("class", "btn btn-warning rounded text-white");
                $("#item-saved-button-md").html("<i class=\"fas fa-check\"></i> <?php echo __('frontend.item.saved') ?>");
            });

            // sm view
            $('#item-save-button-sm').on('click', function(){
                $("#item-save-button-sm").addClass("disabled");
                $("#item-save-form-sm").submit();
            });

            $('#item-saved-button-sm').on('click', function(){
                $("#item-saved-button-sm").off("mouseenter");
                $("#item-saved-button-sm").off("mouseleave");
                $("#item-saved-button-sm").addClass("disabled");
                $("#item-unsave-form-sm").submit();
            });

            $("#item-saved-button-sm").on('mouseenter', function(){
                $("#item-saved-button-sm").attr("class", "btn btn-sm btn-danger rounded text-white");
                $("#item-saved-button-sm").html("<i class=\"far fa-trash-alt\"></i> <?php echo __('frontend.item.unsave') ?>");
            });

            $("#item-saved-button-sm").on('mouseleave', function(){
                $("#item-saved-button-sm").attr("class", "btn btn-sm btn-warning rounded text-white");
                $("#item-saved-button-sm").html("<i class=\"fas fa-check\"></i> <?php echo __('frontend.item.saved') ?>");
            });
            /**
             * End initial save button
             */

            /**
             * Start rating star
             */
            @if($item_count_rating > 0)
            $(".rating_stars_header").rateYo({
                spacing: "5px",
                starWidth: "23px",
                readOnly: true,
                rating: {{ $item_average_rating }},
            });
            @endif
            /**
             * End rating star
             */

            /**
             * Start rating sort by
             */
            $('#rating_sort_by').on('change', function() {
                $( "#item-rating-sort-by-form" ).submit();
            });
            /**
             * End rating sort by
             */

            /**
             * Start initial QR code
             */
            var window_width = $(window).width();
            var qrcode_width = 0;
            if(window_width >= 400)
            {
                qrcode_width = 400;
            }
            else
            {
                qrcode_width = window_width - 50 > 0 ? window_width - 50 : window_width;
            }

            $("#item-qrcode").qrcode({
                // render method: 'canvas', 'image' or 'div'
                render: 'canvas',

                // version range somewhere in 1 .. 40
                minVersion: 10,
                //maxVersion: 40,

                // error correction level: 'L', 'M', 'Q' or 'H'
                ecLevel: 'H',

                // offset in pixel if drawn onto existing canvas
                left: 0,
                top: 0,

                // size in pixel
                size: qrcode_width,

                // code color or image element
                fill: '{{ $customization_site_primary_color }}',

                // background color or image element, null for transparent background
                background: '#f8f9fa',

                // content
                text: '{{ route('page.item', ['item_slug' => $item->item_slug]) }}',

                // corner radius relative to module width: 0.0 .. 0.5
                radius: 0.5,

                // quiet zone in modules
                quiet: 4,

                // modes
                // 0: normal
                // 1: label strip
                // 2: label box
                // 3: image strip
                // 4: image box
                mode: 0,

                mSize: 0.1,
                mPosX: 0.5,
                mPosY: 0.5,

                label: 'no label',
                fontname: 'sans',
                fontcolor: '#000',

                image: null
            });
            /**
             * End initial QR code
             */
        });
    </script>

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP && $item->item_type == \App\Item::ITEM_TYPE_REGULAR)
    <script>
        // Initialize and add the map
        function initMap() {
            // The location of Uluru
            var uluru = {lat: <?php echo $item->item_lat; ?>, lng: <?php echo $item->item_lng; ?>};
            // The map, centered at Uluru
            var map = new google.maps.Map(document.getElementById('mapid-item'), {
                zoom: 14,
                center: uluru,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            // The marker, positioned at Uluru
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap"></script>
    @endif

@endsection
