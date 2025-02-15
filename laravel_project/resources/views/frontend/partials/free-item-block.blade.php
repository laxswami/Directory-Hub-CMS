@php
    $get_count_rating = $item->getCountRating();
    $get_all_categories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY, isset($parent_category_id) ? $parent_category_id : null);
    $all_categories_count = $item->allCategories()->count();
@endphp
<div class="d-block d-md-flex listing vertical">
    <a href="{{ route('page.item', ['item_slug' => $item->item_slug]) }}" class="img d-block listing_for_map_hover" style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})" data-map-lat="{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lat : '' }}" data-map-lng="{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lng : '' }}" data-map-title="{{ $item->item_title }}" data-map-address="{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? ($item->item_address_hide ? $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code : $item->item_address . ', ' . $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code) : '' }}" data-map-rating="{{ $item->item_average_rating }}" data-map-reviews="{{ $get_count_rating }}" data-map-link="{{ route('page.item', $item->item_slug) }}" data-map-feature-image-link="{{ !empty($item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}">
    </a>
    <div class="lh-content">

        @foreach($get_all_categories as $free_item_categories_key => $category)
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

        @if($all_categories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
            <span class="category">{{ __('categories.and') . " " . strval($all_categories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " " . __('categories.more') }}</span>
        @endif

        @if(!empty($item->item_price))
            <span class="category">${{ number_format($item->item_price) }}</span>
        @endif

        <h3 class="pt-2 listing_for_map_hover" data-map-lat="{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lat : '' }}" data-map-lng="{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lng : '' }}" data-map-title="{{ $item->item_title }}" data-map-address="{{ $item->item_type == \App\Item::ITEM_TYPE_REGULAR ? ($item->item_address_hide ? $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code : $item->item_address . ', ' . $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code) : '' }}" data-map-rating="{{ $item->item_average_rating }}" data-map-reviews="{{ $get_count_rating }}" data-map-link="{{ route('page.item', $item->item_slug) }}" data-map-feature-image-link="{{ !empty($item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}"><a href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a></h3>

        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
            <address>
                {{ $item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE ? $item->item_address . ',' : '' }}
                <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                {{ $item->item_postal_code }}
            </address>
        @endif

        @if($get_count_rating > 0)
        <div class="row">
            <div class="col-12">
                <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->item_average_rating }}"></div>
                <address class="mt-1">
                    @if($get_count_rating == 1)
                        {{ '(' . $get_count_rating . ' ' . __('review.frontend.review') . ')' }}
                    @else
                        {{ '(' . $get_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
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
