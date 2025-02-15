@extends('frontend.layouts.app')

@section('styles')
    <link rel="preload" href="{{ asset('frontend/images/placeholder/header-1.webp') }}" as="image">
@endsection

@section('content')

    @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-1.webp') }});">

    @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover overlay" style="background-color: {{ $site_homepage_header_background_color }};">

    @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_homepage_header_background_image) }});">

    @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover overlay" style="background-color: #333333;">
    @endif

            @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_homepage_header_background_youtube_video }}"></div>
            @endif

    <div class="container">

        <!-- Start hero section desktop view-->
        <div class="row align-items-center justify-content-center text-center d-none d-md-flex">

            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-12 text-center">
                        <h1 class="" data-aos="fade-up" style="color: {{ $site_homepage_header_title_font_color }};">{{ __('frontend.homepage.title') }}</h1>
                        <p data-aos="fade-up" data-aos-delay="100" style="color: {{ $site_homepage_header_paragraph_font_color }};">{{ __('frontend.homepage.description') }}</p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    @include('frontend.partials.search.head')
                </div>
            </div>

        </div>
        <!-- End hero section desktop view-->

        <!-- Start hero section mobile view-->
        <div class="row align-items-center justify-content-center text-center d-md-none mt-5">

            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-10 text-center">
                        <h1 class="" data-aos="fade-up">{{ __('frontend.homepage.title') }}</h1>
                        <p data-aos="fade-up" data-aos-delay="100">{{ __('frontend.homepage.description') }}</p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    @include('frontend.partials.search.head')
                </div>
            </div>

        </div>
        <!-- End hero section mobile view-->

    </div>
</div>

<div class="site-section bg-light">
    <div class="container">

        <!-- Start categories section desktop view-->
        <div class="overlap-category mb-4 d-none d-md-block">
            <div class="row align-items-stretch no-gutters">

                @php
                    $categories_count = $categories->count();
                @endphp

                @if($categories_count > 0)
                    @foreach($categories as $categories_key => $category)
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">

                            @if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100">
                                    <span class="icon">
                                        <span>
                                            @if($category->category_icon)
                                                <i class="{{ $category->category_icon }}"></i>
                                            @else
                                                <i class="fa-solid fa-heart"></i>
                                            @endif
                                        </span>
                                    </span>

                                    <span class="caption d-block">{{ $category->category_name }}</span>
                                </a>
                            @elseif($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100 image-category">
                                    <span class="icon image-category-span">
                                        <span>
                                            @if($category->category_image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image) }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @else
                                                <img src="{{ asset('frontend/images/placeholder/category-image.webp') }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @endif
                                        </span>
                                    </span>
                                    <span class="caption d-block image-category-caption">{{ $category->category_name }}</span>
                                </a>
                            @endif

                        </div>
                    @endforeach
                @else
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                        <p>{{ __('frontend.homepage.no-categories') }}</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- End categories section desktop view-->

        <!-- Start categories section mobile view-->
        <div class="overlap-category-sm mb-4 d-md-none">
            <div class="row align-items-stretch no-gutters">

                @if($categories_count > 0)
                    @foreach($categories as $categories_key => $category)
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                            @if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100">
                                    <span class="icon">
                                        <span>
                                            @if($category->category_icon)
                                                <i class="{{ $category->category_icon }}"></i>
                                            @else
                                                <i class="fa-solid fa-heart"></i>
                                            @endif
                                        </span>
                                    </span>

                                    <span class="caption d-block">{{ $category->category_name }}</span>
                                </a>
                            @elseif($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100 image-category">
                                    <span class="icon image-category-span">
                                        <span>
                                            @if($category->category_image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image) }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @else
                                                <img src="{{ asset('frontend/images/placeholder/category-image.webp') }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @endif
                                        </span>
                                    </span>
                                    <span class="caption d-block image-category-caption">{{ $category->category_name }}</span>
                                </a>
                            @endif
                        </div>
                    @endforeach

                @else
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                        <p>{{ __('frontend.homepage.no-categories') }}</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- End categories section mobile view-->

        <div class="row mt-5 mb-5">
            <div class="col-12 text-center">
                <a href="{{ route('page.categories') }}" class="btn btn-primary rounded text-white">
                    <i class="fas fa-th"></i>
                    {{ __('frontend.homepage.all-categories') }}
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.featured-ads') }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12  block-13">
                <div class="owl-carousel nonloop-block-13">

                    @if($paid_items->count() > 0)
                        @foreach($paid_items as $paid_items_key => $item)
                            <div class="d-block d-md-flex listing vertical">
                                <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                                <div class="lh-content">

                                    @php
                                    $paid_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                    @endphp
                                    @foreach($paid_item_getAllCategories as $item_all_categories_key => $category)
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

                                    @php
                                    $paid_item_allCategories_count = $item->allCategories()->count();
                                    @endphp
                                    @if($paid_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                        <span class="category">{{ __('categories.and') . " " . strval($paid_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more') }}</span>
                                    @endif

                                    <h3 class="pt-2"><a href="{{ route('page.item', $item->item_slug) }}">{{ str_limit($item->item_title, 44, '...') }}</a></h3>

                                    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                    <address>
                                        <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                        <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                    </address>
                                    @endif

                                    @php
                                    $paid_item_getCountRating = $item->getCountRating();
                                    @endphp
                                    @if($paid_item_getCountRating > 0)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->item_average_rating }}"></div>
                                                <address class="mt-1">
                                                    @if($paid_item_getCountRating == 1)
                                                        {{ '(' . $paid_item_getCountRating . ' ' . __('review.frontend.review') . ')' }}
                                                    @else
                                                        {{ '(' . $paid_item_getCountRating . ' ' . __('review.frontend.reviews') . ')' }}
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
                                                    @if(empty($item->user->user_image))
                                                        <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                    @else
                                                        <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                                    @endif
                                                </div>
                                                <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                    <div class="row pb-1">
                                                        <div class="col-12">
                                                            <span class="font-size-13">{{ str_limit($item->user->name, 12, '.') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row line-height-1-0">
                                                        <div class="col-12">
                                                            <span class="review">{{ $item->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-7 col-md-5 pl-0 text-right">
                                            @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                                @if($item->hasOpened())
                                                    <span class="item-box-hour-span-opened">{{ __('item_hour.frontend-item-box-hour-opened') }}</span>
                                                @else
                                                    <span class="item-box-hour-span-closed">{{ __('item_hour.frontend-item-box-hour-closed') }}</span>
                                                @endif
                                            @endif
                                        </div>

                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="d-block d-md-flex listing vertical">
                        </div>
                    @endif

                </div>
            </div>


        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.nearby-listings') }}</h2>
                <p class="color-black-opacity-5">{{ __('frontend.homepage.popular-listings') }}</p>
            </div>
        </div>

        <div class="row">

            @if($popular_items->count() > 0)
                @foreach($popular_items as $popular_items_key => $item)
                    <div class="col-md-6 mb-4 mb-lg-4 col-lg-4">

                        <div class="listing-item listing">
                            <div class="listing-image">
                                <img src="{{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }}" alt="Image" class="img-fluid">
                            </div>
                            <div class="listing-item-content">

                                @php
                                    $popular_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                @endphp
                                @foreach($popular_item_getAllCategories as $item_all_categories_key => $category)
                                    <a class="px-3 mb-3 category" href="{{ route('page.category', $category->category_slug) }}">
                                        @if(!empty($category->category_icon))
                                            <i class="{{ $category->category_icon }}"></i>
                                        @else
                                            <i class="fa-solid fa-heart"></i>
                                        @endif
                                        {{ $category->category_name }}
                                    </a>
                                @endforeach

                                @php
                                    $popular_item_allCategories_count = $item->allCategories()->count();
                                @endphp
                                @if($popular_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                    <span class="category">{{ __('categories.and') . " " . strval($popular_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more') }}</span>
                                @endif

                                <h2 class="mb-1 pt-2"><a href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a></h2>

                                @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                <span class="address">
                                    <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                    <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                </span>
                                @endif

                                @php
                                    $popular_item_getCountRating = $item->getCountRating();
                                @endphp
                                @if($popular_item_getCountRating > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->item_average_rating }}"></div>
                                            <address class="mt-1">
                                                @if($popular_item_getCountRating == 1)
                                                    <span>{{ '(' . $popular_item_getCountRating . ' ' . __('review.frontend.review') . ')' }}</span>
                                                @else
                                                    <span>{{ '(' . $popular_item_getCountRating . ' ' . __('review.frontend.reviews') . ')' }}</span>
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                @endif

                                <hr class="item-box-hr item-box-index-nearby-hr">

                                <div class="row mt-1 align-items-center">

                                    <div class="col-5 col-md-7 pr-0">
                                        <div class="row align-items-center item-box-user-div">
                                            <div class="col-3 item-box-user-img-div">
                                                @if(empty($item->user->user_image))
                                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                @else
                                                    <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                                @endif
                                            </div>
                                            <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                <div class="row pb-1">
                                                    <div class="col-12">
                                                        <span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span>
                                                    </div>
                                                </div>
                                                <div class="row line-height-1-0">
                                                    <div class="col-12">
                                                        <span class="review">{{ $item->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-7 col-md-5 pl-0 text-right">
                                        @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                            @if($item->hasOpened())
                                                <span class="item-box-index-nearby-hour-span-opened">{{ __('item_hour.frontend-item-box-hour-opened') }}</span>
                                            @else
                                                <span class="item-box-index-nearby-hour-span-closed">{{ __('item_hour.frontend-item-box-hour-closed') }}</span>
                                            @endif
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach
            @endif

        </div>
    </div>
</div>


<div class="site-section bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.recent-listings') }}</h2>
            </div>
        </div>
        <div class="row mt-5">

            @if($latest_items->count() > 0)
                @foreach($latest_items as $latest_items_key => $item)
                    <div class="col-lg-6">
                        <div class="d-block d-md-flex listing">
                            <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                            <div class="lh-content">

                                @php
                                    $latest_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                @endphp
                                @foreach($latest_item_getAllCategories as $item_all_categories_key => $category)
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

                                @php
                                    $latest_item_allCategories_count = $item->allCategories()->count();
                                @endphp
                                @if($latest_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                    <span class="category">{{ __('categories.and') . " " . strval($latest_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more') }}</span>
                                @endif

                                <h3 class="pt-2"><a href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a></h3>

                                @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                <address>
                                    <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                    <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                </address>
                                @endif

                                @php
                                    $latest_item_getCountRating = $item->getCountRating();
                                @endphp
                                @if($latest_item_getCountRating > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->item_average_rating }}"></div>
                                            <address class="mt-1">
                                                @if($latest_item_getCountRating == 1)
                                                    {{ '(' . $latest_item_getCountRating . ' ' . __('review.frontend.review') . ')' }}
                                                @else
                                                    {{ '(' . $latest_item_getCountRating . ' ' . __('review.frontend.reviews') . ')' }}
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
                                                @if(empty($item->user->user_image))
                                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                @else
                                                    <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                                @endif
                                            </div>
                                            <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                <div class="row pb-1">
                                                    <div class="col-12">
                                                        <span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span>
                                                    </div>
                                                </div>
                                                <div class="row line-height-1-0">
                                                    <div class="col-12">
                                                        <span class="review">{{ $item->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-7 col-md-5 pl-0 text-right">
                                        @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                            @if($item->hasOpened())
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
            @endif
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="{{ route('page.categories') }}" class="btn btn-primary rounded text-white">
                    {{ __('all_latest_listings.view-all-latest') }}
                </a>
            </div>
        </div>
    </div>
</div>

@if($all_testimonials->count() > 0)
<div class="site-section bg-white">
    <div class="container">

        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.testimonials') }}</h2>
            </div>
        </div>

        <div class="slide-one-item home-slider owl-carousel">

                @foreach($all_testimonials as $key => $testimonial)
                    <div>
                        <div class="testimonial">
                            <figure class="mb-4">
                                @if(empty($testimonial->testimonial_image))
                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($testimonial->id % 10) . '.webp') }}" alt="Image" class="img-fluid mb-3">
                                @else
                                    <img src="{{ Storage::disk('public')->url('testimonial/' . $testimonial->testimonial_image) }}" alt="Image" class="img-fluid mb-3">
                                @endif
                                <p>
                                    {{ $testimonial->testimonial_name }}
                                    @if($testimonial->testimonial_job_title)
                                        {{ '• ' . $testimonial->testimonial_job_title }}
                                    @endif
                                    @if($testimonial->testimonial_company)
                                        {{ '• ' . $testimonial->testimonial_company }}
                                    @endif
                                </p>
                            </figure>
                            <blockquote>
                                <p>{{ $testimonial->testimonial_description }}</p>
                            </blockquote>
                        </div>
                    </div>
                @endforeach

        </div>
    </div>
</div>
@endif


@if($recent_blog->count() > 0)
<div class="site-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.our-blog') }}</h2>
                <p class="color-black-opacity-5">{{ __('frontend.homepage.our-blog-decr') }}</p>
            </div>
        </div>
        <div class="row mb-3 align-items-stretch">

                @foreach($recent_blog as $recent_blog_key => $post)
                    <div class="col-md-6 col-lg-4 mb-4 mb-lg-4">
                        <div class="h-entry">
                            @if(empty($post->featured_image))
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url({{ asset('frontend/images/placeholder/full_item_feature_image_medium.webp') }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            @else
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url({{ url('laravel_project/public' . $post->featured_image) }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            @endif
                            <h2 class="font-size-regular"><a href="{{ route('page.blog.show', $post->slug) }}" class="text-black">{{ $post->title }}</a></h2>
                            <div class="meta mb-3">
                                by {{ $post->user()->first()->name }}<span class="mx-1">&bullet;</span> {{ $post->updated_at->diffForHumans() }} <span class="mx-1">&bullet;</span>
                                @if($post->topic()->count() != 0)
                                <a href="{{ route('page.blog.topic', $post->topic()->first()->slug) }}">{{ $post->topic()->first()->name }}</a>
                                @else
                                    {{ __('frontend.blog.uncategorized') }}
                                @endif

                            </div>
                            <p>{{ str_limit(preg_replace("/&#?[a-z0-9]{2,8};/i"," ", strip_tags($post->body)), 200) }}</p>
                        </div>
                    </div>
                @endforeach

            <div class="col-12 text-center mt-4">
                <a href="{{ route('page.blog') }}" class="btn btn-primary rounded py-2 px-4 text-white">{{ __('frontend.homepage.all-posts') }}</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal -->
<div class="modal fade" id="reload_page_modal" tabindex="-1" role="dialog" aria-labelledby="reload_page_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reload_page_modal_label">{{ __('category_image_option.geolocation.modal-title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('category_image_option.geolocation.modal-description') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('category_image_option.geolocation.modal-button-no') }}</button>
                <a href="{{ route('page.home') }}" class="btn btn-primary text-white rounded">{{ __('category_image_option.geolocation.modal-button-reload') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
    <!-- Youtube Background for Header -->
    <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script>
        $(document).ready(function(){

            "use strict";

            /**
             * Start get user lat & lng location
             */
            function success(position) {
                const latitude  = position.coords.latitude;
                const longitude = position.coords.longitude;

                console.log("Latitude: " + latitude + ", Longitude: " + longitude);

                var ajax_url = '/ajax/location/save/' + latitude + '/' + longitude;

                console.log(ajax_url);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: ajax_url,
                    method: 'post',
                    data: {
                    },
                    success: function(result){
                        console.log(result);

                        if(result.reload)
                        {
                            $('#reload_page_modal').modal('show');
                        }
                    }});
            }

            function error() {
                console.log("Unable to retrieve your geolocation");
            }

            if(!navigator.geolocation) {

                console.log("Geolocation is not supported by your browser");
            } else {

                console.log("Locating geolocation ...");
                navigator.geolocation.getCurrentPosition(success, error);
            }
            /**
             * End get user lat & lng location
             */

            @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif

        });

    </script>

@endsection
