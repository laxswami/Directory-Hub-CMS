@extends('backend.admin.layouts.app')

@section('styles')

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <link href="{{ asset('backend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.item.add-item') }}</h1>
            <p class="mb-4">{{ __('backend.item.add-item-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row border-left-info mb-4 pt-3 pb-3">
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-lg text-gray-800">{{ __('backend.item.select-category') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('admin.items.create') }}" method="GET">
                                <div class="form-row">
                                    <div class="col-6 col-sm-8 col-md-9 col-lg-10">
                                        <select multiple size="{{ count($all_categories) }}" class="selectpicker form-control @error('category') is-invalid @enderror" name="category[]" onchange="$('#item-create-form').remove();" data-live-search="true" data-actions-box="true">
                                            @foreach($all_categories as $key => $category)
                                                <option value="{{ $category['category_id'] }}" {{ in_array($category['category_id'], empty($category_ids) ? array() : $category_ids) ? 'selected' : '' }}>{{ $category['category_name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                        <button type="submit" class="btn btn-primary">{{ __('backend.item.load-form') }}</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if(is_array($category_ids) && count($category_ids) > 0)
                    <form method="POST" action="{{ route('admin.items.store') }}" id="item-create-form">
                        @csrf

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                        <div class="form-check">
                                            <input checked class="form-check-input" type="radio" name="item_type" id="item_type_regular" value="{{ \App\Item::ITEM_TYPE_REGULAR }}" aria-describedby="item_type_regularHelpBlock">
                                            <label class="form-check-label" for="item_type_regular">
                                                {{ __('theme_directory_hub.online-listing.regular-listing') }}
                                            </label>
                                            <small id="item_type_regularHelpBlock" class="form-text text-muted">
                                                {{ __('theme_directory_hub.online-listing.regular-listing-help') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="item_type" id="item_type_online" value="{{ \App\Item::ITEM_TYPE_ONLINE }}" aria-describedby="item_type_onlineHelpBlock">
                                            <label class="form-check-label" for="item_type_online">
                                                {{ __('theme_directory_hub.online-listing.online-listing') }}
                                            </label>
                                            <small id="item_type_onlineHelpBlock" class="form-text text-muted">
                                                {{ __('theme_directory_hub.online-listing.online-listing-help') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                             <i class="fas fa-store"></i>
                                            {{ __('backend.item.general-info') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('item_hour.item-general-info-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">

                                    <div class="col-md-6">
                                        <label for="item_title" class="text-black">{{ __('backend.item.title') }}</label>
                                        <input id="item_title" type="text" class="form-control @error('item_title') is-invalid @enderror" name="item_title" value="{{ old('item_title') }}">
                                        @error('item_title')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror

                                        @foreach($category_ids as $key => $category_id)
                                            <input name="category[]" value="{{ $category_id }}" type="hidden" class="input_category_id">
                                        @endforeach
                                    </div>

                                    <div class="col-md-6">
                                        <label for="user_id" class="text-black">{{ __('role_permission.item.listing-owner') }}</label>
                                        <select id="user_id" class="selectpicker form-control @error('user_id') is-invalid @enderror" name="user_id" data-live-search="true">
                                            <option value="{{ $login_user->id }}" {{ old('user_id') == $login_user->id ? 'selected' : '' }}>{{ __('role_permission.item.myself') . ' (' . $login_user->email . ')' }}</option>
                                            @foreach($other_users as $other_users_key => $other_user)
                                                <option value="{{ $other_user->id }}" {{ old('user_id') == $other_user->id ? 'selected' : '' }}>{{ $other_user->name . ' (' . $other_user->email . ')' }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('role_permission.item.listing-owner-help') }}
                                        </small>
                                        @error('user_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-3">
                                        <label for="item_status" class="text-black">{{ __('backend.item.status') }}</label>
                                        <select class="selectpicker form-control @error('item_status') is-invalid @enderror" name="item_status">

                                            <option value="{{ \App\Item::ITEM_SUBMITTED }}">{{ __('backend.item.submitted') }}</option>
                                            <option value="{{ \App\Item::ITEM_PUBLISHED }}" selected>{{ __('backend.item.published') }}</option>
                                            <option value="{{ \App\Item::ITEM_SUSPENDED }}">{{ __('backend.item.suspended') }}</option>

                                        </select>
                                        @error('item_status')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="item_featured" class="text-black">{{ __('backend.item.featured') }}</label>
                                        <select class="selectpicker form-control @error('item_featured') is-invalid @enderror" name="item_featured">

                                            <option value="{{ \App\Item::ITEM_NOT_FEATURED }}" selected>{{ __('backend.shared.no') }}</option>
                                            <option value="{{ \App\Item::ITEM_FEATURED }}">{{ __('backend.shared.yes') }}</option>

                                        </select>
                                        @error('item_featured')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="item_address" class="text-black">{{ __('backend.item.address') }}</label>
                                        <input id="item_address" type="text" class="form-control @error('item_address') is-invalid @enderror" name="item_address" value="{{ old('item_address') }}">
                                        @error('item_address')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">

                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input {{ old('item_address_hide') == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="item_address_hide" name="item_address_hide" value="1">
                                            <label class="form-check-label" for="item_address_hide">
                                                {{ __('backend.item.hide-address') }}
                                                <small class="text-muted">
                                                    {{ __('backend.item.hide-address-help') }}
                                                </small>
                                            </label>
                                        </div>
                                        @error('item_address_hide')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">

                                    <div class="col-md-3">
                                        <label for="select_country_id" class="text-black">{{ __('backend.setting.country') }}</label>
                                        <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="true">
                                            <option selected value="0">{{ __('prefer_country.select-country') }}</option>
                                            @foreach($all_countries as $all_countries_key => $country)
                                                @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                                                    <option value="{{ $country->id }}" {{ $country->id == old('country_id') ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="select_state_id" class="text-black">{{ __('backend.state.state') }}</label>
                                        <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true">
                                            <option selected value="0">{{ __('backend.item.select-state') }}</option>
                                        </select>
                                        @error('state_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="select_city_id" class="text-black">{{ __('backend.city.city') }}</label>
                                        <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true">
                                            <option selected value="0">{{ __('backend.item.select-city') }}</option>
                                        </select>
                                        @error('city_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="item_postal_code" class="text-black">{{ __('backend.item.postal-code') }}</label>
                                        <input id="item_postal_code" type="text" class="form-control @error('item_postal_code') is-invalid @enderror" name="item_postal_code" value="{{ old('item_postal_code') }}">
                                        @error('item_postal_code')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-row mb-3">

                                    <div class="col-md-3">
                                        <label for="item_lat" class="text-black">{{ __('backend.item.lat') }}</label>
                                        <input id="item_lat" type="text" class="form-control @error('item_lat') is-invalid @enderror" name="item_lat" value="{{ old('item_lat') }}" aria-describedby="latHelpBlock">
                                        <small id="latHelpBlock" class="form-text text-muted">
                                            <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                                        </small>
                                        @error('item_lat')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="item_lng" class="text-black">{{ __('backend.item.lng') }}</label>
                                        <input id="item_lng" type="text" class="form-control @error('item_lng') is-invalid @enderror" name="item_lng" value="{{ old('item_lng') }}" aria-describedby="lngHelpBlock">
                                        <small id="lngHelpBlock" class="form-text text-muted">
                                            <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                                        </small>
                                        @error('item_lng')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="item_phone" class="text-black">{{ __('backend.item.phone') }}</label>
                                        <input id="item_phone" type="text" class="form-control @error('item_phone') is-invalid @enderror" name="item_phone" value="{{ old('item_phone') }}">
                                        @error('item_phone')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="item_youtube_id" class="text-black">{{ __('customization.item.youtube-id') }}</label>
                                        <input id="item_youtube_id" type="text" class="form-control @error('item_youtube_id') is-invalid @enderror" name="item_youtube_id" value="{{ old('item_youtube_id') }}">
                                        @error('item_youtube_id')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-row mb-3">

                                    <div class="col-md-12">
                                        <label for="item_description" class="text-black">{{ __('backend.item.description') }}</label>
                                        <textarea class="form-control @error('item_description') is-invalid @enderror" id="item_description" rows="5" name="item_description">{{ old('item_description') }}</textarea>
                                        @error('item_description')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Start web & social media -->
                                <div class="form-row mb-3">
                                    <div class="col-md-4">
                                        <label for="item_website" class="text-black">
                                            <i class="fa-solid fa-globe"></i>
                                            {{ __('backend.item.website') }}
                                        </label>
                                        <input id="item_website" type="text" class="form-control @error('item_website') is-invalid @enderror" name="item_website" value="{{ old('item_website') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('item_website')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_facebook" class="text-black">
                                            <i class="fa-brands fa-facebook-square"></i>
                                            {{ __('backend.item.facebook') }}
                                        </label>
                                        <input id="item_social_facebook" type="text" class="form-control @error('item_social_facebook') is-invalid @enderror" name="item_social_facebook" value="{{ old('item_social_facebook') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('item_social_facebook')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_twitter" class="text-black">
                                            <i class="fa-brands fa-twitter-square"></i>
                                            {{ __('backend.item.twitter') }}
                                        </label>
                                        <input id="item_social_twitter" type="text" class="form-control @error('item_social_twitter') is-invalid @enderror" name="item_social_twitter" value="{{ old('item_social_twitter') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('item_social_twitter')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-4">
                                        <label for="item_social_linkedin" class="text-black">
                                            <i class="fa-brands fa-linkedin"></i>
                                            {{ __('backend.item.linkedin') }}
                                        </label>
                                        <input id="item_social_linkedin" type="text" class="form-control @error('item_social_linkedin') is-invalid @enderror" name="item_social_linkedin" value="{{ old('item_social_linkedin') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('item_social_linkedin')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_instagram" class="text-black">
                                            <i class="fa-brands fa-instagram-square"></i>
                                            {{ __('item_whatsapp_instagram.item-social-instagram') }}
                                        </label>
                                        <input id="item_social_instagram" type="text" class="form-control @error('item_social_instagram') is-invalid @enderror" name="item_social_instagram" value="{{ old('item_social_instagram') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('item_whatsapp_instagram.item-social-instagram-help') }}
                                        </small>
                                        @error('item_social_instagram')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_whatsapp" class="text-black">
                                            <i class="fa-brands fa-whatsapp-square"></i>
                                            {{ __('item_whatsapp_instagram.item-social-whatsapp') }}
                                        </label>
                                        <input id="item_social_whatsapp" type="text" class="form-control @error('item_social_whatsapp') is-invalid @enderror" name="item_social_whatsapp" value="{{ old('item_social_whatsapp') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('item_whatsapp_instagram.item-social-whatsapp-help') }}
                                        </small>
                                        @error('item_social_whatsapp')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                                <!-- End web & social media -->
                            </div>
                        </div>

                        <!-- start opening hour section -->
                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-clock"></i>
                                            {{ __('item_hour.open-hour') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('item_hour.open-hour-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="item_hour_time_zone" class="text-black">{{ __('item_hour.timezone') }}</label>
                                        <select id="item_hour_time_zone" class="selectpicker form-control @error('item_hour_time_zone') is-invalid @enderror" name="item_hour_time_zone" data-live-search="true">
                                            @foreach($time_zone_identifiers as $time_zone_identifiers_key => $time_zone_identifier)
                                                <option value="{{ $time_zone_identifier }}" {{ old('item_hour_time_zone') == $time_zone_identifier ? 'selected' : '' }}>{{ $time_zone_identifier }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.timezone-help') }}
                                        </small>
                                        @error('item_hour_time_zone')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="item_hour_show_hours" class="text-black">{{ __('item_hour.show-open-hour') }}</label>
                                        <select id="item_hour_show_hours" class="selectpicker form-control @error('item_hour_show_hours') is-invalid @enderror" name="item_hour_show_hours" data-live-search="true">
                                            <option value="{{ \App\Item::ITEM_HOUR_SHOW }}" {{ old('item_hour_show_hours') == \App\Item::ITEM_HOUR_SHOW ? 'selected' : '' }}>{{ __('item_hour.show-hour') }}</option>
                                            <option value="{{ \App\Item::ITEM_HOUR_NOT_SHOW }}" {{ old('item_hour_show_hours') == \App\Item::ITEM_HOUR_NOT_SHOW ? 'selected' : '' }}>{{ __('item_hour.not-show-hour') }}</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.show-open-hour-help') }}
                                        </small>
                                        @error('item_hour_show_hours')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <span class="text-gray-800">{{ __('item_hour.open-hour-hours') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.open-hour-hours-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3 align-items-end">
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_day_of_week" class="text-black">{{ __('item_hour.day-of-week') }}</label>
                                        <select id="item_hour_day_of_week" class="selectpicker form-control" name="item_hour_day_of_week" data-live-search="true">
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_MONDAY }}">{{ __('item_hour.monday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_TUESDAY }}">{{ __('item_hour.tuesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_WEDNESDAY }}">{{ __('item_hour.wednesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_THURSDAY }}">{{ __('item_hour.thursday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_FRIDAY }}">{{ __('item_hour.friday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SATURDAY }}">{{ __('item_hour.saturday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SUNDAY }}">{{ __('item_hour.sunday') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_open_time_open_hour" class="text-black">{{ __('item_hour.item-hour-open-hour') }}</label>
                                        <select id="item_hour_open_time_open_hour" class="selectpicker form-control" name="item_hour_open_time_open_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_open_time_open_minute" class="text-black">{{ __('item_hour.item-hour-open-minute') }}</label>
                                        <select id="item_hour_open_time_open_minute" class="selectpicker form-control" name="item_hour_open_time_open_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_open_time_close_hour" class="text-black">{{ __('item_hour.item-hour-close-hour') }}</label>
                                        <select id="item_hour_open_time_close_hour" class="selectpicker form-control" name="item_hour_open_time_close_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_open_time_close_minute" class="text-black">{{ __('item_hour.item-hour-close-minute') }}</label>
                                        <select id="item_hour_open_time_close_minute" class="selectpicker form-control" name="item_hour_open_time_close_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <a class="btn btn-sm btn-block btn-primary rounded text-white" id="item_hour_create_button">
                                            <i class="fas fa-plus"></i>
                                            {{ __('item_hour.add-open-hour') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-row mb-3" id="open_hour_added_hours">
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <span class="text-gray-800">{{ __('item_hour.open-hour-exceptions') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.open-hour-exceptions-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3 align-items-end">
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_exception_date" class="text-black">{{ __('item_hour.open-hour-exception-date') }}</label>
                                        <input id="item_hour_exception_date" type="text" class="form-control" name="item_hour_exception_date" value="" placeholder="{{ __('item_hour.open-hour-exception-date-placeholder') }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_exception_open_time_open_hour" class="text-black">{{ __('item_hour.item-hour-open-hour') }}</label>
                                        <select id="item_hour_exception_open_time_open_hour" class="selectpicker form-control" name="item_hour_exception_open_time_open_hour" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_exception_open_time_open_minute" class="text-black">{{ __('item_hour.item-hour-open-minute') }}</label>
                                        <select id="item_hour_exception_open_time_open_minute" class="selectpicker form-control" name="item_hour_exception_open_time_open_minute" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_exception_open_time_close_hour" class="text-black">{{ __('item_hour.item-hour-close-hour') }}</label>
                                        <select id="item_hour_exception_open_time_close_hour" class="selectpicker form-control" name="item_hour_exception_open_time_close_hour" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="item_hour_exception_open_time_close_minute" class="text-black">{{ __('item_hour.item-hour-close-minute') }}</label>
                                        <select id="item_hour_exception_open_time_close_minute" class="selectpicker form-control" name="item_hour_exception_open_time_close_minute" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <a class="btn btn-sm btn-block btn-primary rounded text-white" id="item_hour_exception_create_button">
                                            <i class="fas fa-plus"></i>
                                            {{ __('item_hour.add-open-hour-exception') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-row mb-3" id="open_hour_added_exceptions">
                                </div>
                            </div>
                        </div>
                        <!-- end opening hour section -->

                        <!-- Start custom field section -->
                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-tasks"></i>
                                            {{ __('backend.item.custom-fields') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('backend.item.custom-field-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    @foreach($all_customFields as $key => $customField)
                                        <div class="col-md-4 mb-3">
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_TEXT)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <textarea class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror" id="{{ str_slug($customField->custom_field_name . $customField->id) }}" rows="5" name="{{ str_slug($customField->custom_field_name . $customField->id) }}">{{ old(str_slug($customField->custom_field_name . $customField->id)) }}</textarea>
                                                @error(str_slug($customField->custom_field_name . $customField->id))
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_SELECT)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <select class="selectpicker form-control" name="{{ str_slug($customField->custom_field_name . $customField->id) }}" id="{{ str_slug($customField->custom_field_name . $customField->id) }}" data-live-search="true">
                                                    @foreach(explode(',', $customField->custom_field_seed_value) as $key => $custom_field_value)
                                                        <option value="{{ $custom_field_value }}" {{ old(str_slug($customField->custom_field_name . $customField->id)) == $custom_field_value ? 'selected' : '' }}>{{ $custom_field_value }}</option>
                                                    @endforeach
                                                </select>
                                                @error(str_slug($customField->custom_field_name . $customField->id))
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <select multiple class="selectpicker form-control" name="{{ str_slug($customField->custom_field_name . $customField->id) }}[]" id="{{ str_slug($customField->custom_field_name . $customField->id) }}" data-live-search="true" data-actions-box="true">
                                                    @foreach(explode(',', $customField->custom_field_seed_value) as $key => $custom_field_value)
                                                        <option value="{{ $custom_field_value }}" {{ old(str_slug($customField->custom_field_name . $customField->id)) == $custom_field_value ? 'selected' : '' }}>{{ $custom_field_value }}</option>
                                                    @endforeach
                                                </select>
                                                @error($customField->custom_field_name . $customField->id)
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_LINK)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <input id="{{ str_slug($customField->custom_field_name . $customField->id) }}" type="text" class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror" name="{{ str_slug($customField->custom_field_name . $customField->id) }}" value="{{ old(str_slug($customField->custom_field_name . $customField->id)) }}" aria-describedby="linkHelpBlock">
                                                <small id="linkHelpBlock" class="form-text text-muted">
                                                    {{ __('backend.shared.url-help') }}
                                                </small>
                                                @error(str_slug($customField->custom_field_name . $customField->id))
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- End custom field section -->

                        <!-- Start feature image and gallery image -->
                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-images"></i>
                                            {{ __('item_hour.item-photos') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('item_hour.item-photos-help') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-6">
                                        <span class="text-lg text-gray-800">{{ __('backend.item.feature-image') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('backend.item.feature-image-help') }}
                                        </small>
                                        @error('feature_image')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-8">
                                                <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-image') }}</button>
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive">
                                                <input id="feature_image" type="hidden" name="feature_image">
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-8">
                                                <a class="btn btn-danger btn-block text-white" id="delete_feature_image_button">
                                                    <i class="fas fa-trash-alt"></i>
                                                    {{ __('role_permission.item.delete-feature-image') }}
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-lg text-gray-800">{{ __('backend.item.gallery-images') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('theme_directory_hub.listing.gallery-upload-help', ['gallery_photos_count' => $setting_item_max_gallery_photos]) }}
                                        </small>
                                        @error('image_gallery')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button id="upload_gallery" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-images') }}</button>
                                                <div class="row" id="selected-images">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End feature image and gallery image -->

                        <hr/>
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.create') }}
                                </button>
                            </div>
                        </div>

                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - feature image -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.item.crop-feature-image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div id="image_demo"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="custom-file">
                                <input id="upload_image_input" type="file" class="custom-file-input">
                                <label class="custom-file-label" for="upload_image_input">{{ __('backend.item.choose-image') }}</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.item.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - map -->
    <div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="map-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.item.select-map-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="map-modal-body"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span id="lat_lng_span"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="lat_lng_confirm" type="button" class="btn btn-primary">{{ __('backend.shared.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="{{ asset('backend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    <!-- Image Crop Plugin Js -->
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>

    <!-- Bootstrap Fd Plugin Js-->
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.admin.partials.bootstrap-select-locale')

    <script src="{{ asset('backend/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>

        $(document).ready(function() {

            "use strict";

            @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
            /**
             * Start map modal
             */
            var map = L.map('map-modal-body', {
                //center: [37.0902, -95.7129],
                center: [{{ $setting_site_location_lat }}, {{ $setting_site_location_lng }}],
                zoom: 5,
            });

            var layerGroup = L.layerGroup().addTo(map);
            var current_lat = 0;
            var current_lng = 0;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.on('click', function(e) {

                // remove all the markers in one go
                layerGroup.clearLayers();
                L.marker([e.latlng.lat, e.latlng.lng]).addTo(layerGroup);

                current_lat = e.latlng.lat;
                current_lng = e.latlng.lng;

                $('#lat_lng_span').text("Lat, Lng : " + e.latlng.lat + ", " + e.latlng.lng);
            });

            $('#lat_lng_confirm').on('click', function(){

                $('#item_lat').val(current_lat);
                $('#item_lng').val(current_lng);
                $('#map-modal').modal('hide')
            });
            $('.lat_lng_select_button').on('click', function(){
                $('#map-modal').modal('show');
                setTimeout(function(){ map.invalidateSize()}, 500);
            });
            /**
             * End map modal
             */
            @endif


            /**
             * Start country, state, city selector
             */
            $('#select_country_id').on('change', function() {

                $('#select_state_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#select_state_id').selectpicker('refresh');

                if(this.value > 0)
                {
                    var ajax_url = '/ajax/states/' + this.value;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        data: {
                        },
                        success: function(result){

                            $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var state_id = value.id;
                                var state_name = value.state_name;
                                $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                            });
                            $('#select_state_id').selectpicker('refresh');
                        }});
                }

            });


            $('#select_state_id').on('change', function() {

                $('#select_city_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#select_city_id').selectpicker('refresh');

                if(this.value > 0)
                {
                    var ajax_url = '/ajax/cities/' + this.value;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        data: {
                        },
                        success: function(result){

                            $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            $('#select_city_id').selectpicker('refresh');
                    }});
                }

            });

            @if(old('country_id'))
                var ajax_url_initial_states = '/ajax/states/{{ old('country_id') }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: ajax_url_initial_states,
                    method: 'get',
                    data: {
                    },
                    success: function(result){

                        $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var state_id = value.id;
                            var state_name = value.state_name;

                            if(state_id === {{ old('state_id') }})
                            {
                                $('#select_state_id').append('<option value="'+ state_id +'" selected>' + state_name + '</option>');
                            }
                            else
                            {
                                $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                            }

                        });
                        $('#select_state_id').selectpicker('refresh');
                    }});
            @endif

            @if(old('state_id'))
                var ajax_url_initial_cities = '/ajax/cities/{{ old('state_id') }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: ajax_url_initial_cities,
                    method: 'get',
                    data: {
                    },
                    success: function(result){

                        $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var city_id = value.id;
                            var city_name = value.city_name;

                            if(city_id === {{ old('city_id') }})
                            {
                                $('#select_city_id').append('<option value="'+ city_id +'" selected>' + city_name + '</option>');
                            }
                            else
                            {
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            }
                        });
                        $('#select_city_id').selectpicker('refresh');
                    }});
            @endif
            /**
             * End country, state, city selector
             */

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
                        html += "<div class='col-3 mb-2' id='item_image_gallery_" + a + "'>" +
                            "<img style='max-width: 120px;' src='" + event.files[a].content + "'>" +
                            "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#item_image_gallery_" + a + "\").remove();'>Delete</button>" +
                            "<input type='hidden' value='" + event.files[a].content + "' name='image_gallery[]'>" +
                            "</div>";
                    }
                    document.getElementById("selected-images").innerHTML += html;
                });
            });
            /**
             * End image gallery uplaod
             */

            /**
             * Start the croppie image plugin
             */
            var image_crop = null;

            $('#upload_image').on('click', function(){

                $('#image-crop-modal').modal('show');
            });

            var window_height = $(window).height();
            var window_width = $(window).width();
            var viewport_height = 0;
            var viewport_width = 0;

            if(window_width >= 800)
            {
                viewport_width = 800;
                viewport_height = 687;
            }
            else
            {
                viewport_width = window_width * 0.8;
                viewport_height = (viewport_width * 687) / 800;
            }

            $('#upload_image_input').on('change', function(){

                if(!image_crop)
                {
                    image_crop = $('#image_demo').croppie({
                        enableExif: true,
                        mouseWheelZoom: false,
                        viewport: {
                            width:viewport_width,
                            height:viewport_height,
                            type:'square',
                        },
                        boundary:{
                            width:viewport_width + 5,
                            height:viewport_width + 5,
                        }
                    });

                    $('#image-crop-modal .modal-dialog').css({
                        'max-width':'100%'
                    });
                }

                var reader = new FileReader();

                reader.onload = function (event) {

                    image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });

                };
                reader.readAsDataURL(this.files[0]);
            });

            $('#crop_image').on("click", function(event){

                image_crop.croppie('result', {
                    type: 'base64',
                    size: 'viewport'
                }).then(function(response){
                    $('#feature_image').val(response);
                    $('#image_preview').attr("src", response);
                });

                $('#image-crop-modal').modal('hide')
            });
            /**
             * End the croppie image plugin
             */

            /**
             * Start listing type radio button select
             */
            $('input:radio[name="item_type"]').change(
                function(){
                    if ($(this).is(':checked') && $(this).val() == '{{ \App\Item::ITEM_TYPE_REGULAR }}') {

                        // enable all location related input
                        $( "#item_address" ).prop( "disabled", false );
                        $( "#item_address_hide" ).prop( "disabled", false );
                        $( "#select_country_id" ).prop( "disabled", false );
                        $( "#select_state_id" ).prop( "disabled", false );
                        $( "#select_city_id" ).prop( "disabled", false );
                        $( "#item_postal_code" ).prop( "disabled", false );
                        $( "#item_lat" ).prop( "disabled", false );
                        $( "#item_lng" ).prop( "disabled", false );

                        $('#select_country_id').selectpicker('refresh');
                        $('#select_state_id').selectpicker('refresh');
                        $('#select_city_id').selectpicker('refresh');
                    }
                    else
                    {
                        // disable all location related input
                        $( "#item_address" ).prop( "disabled", true );
                        $( "#item_address_hide" ).prop( "disabled", true );
                        $( "#select_country_id" ).prop( "disabled", true );
                        $( "#select_state_id" ).prop( "disabled", true );
                        $( "#select_city_id" ).prop( "disabled", true );
                        $( "#item_postal_code" ).prop( "disabled", true );
                        $( "#item_lat" ).prop( "disabled", true );
                        $( "#item_lng" ).prop( "disabled", true );

                        $('#select_country_id').selectpicker('refresh');
                        $('#select_state_id').selectpicker('refresh');
                        $('#select_city_id').selectpicker('refresh');

                    }
                });
            /**
             * End listing type radio button select
             */


            /**
             * Start delete feature image button
             */
            $('#delete_feature_image_button').on('click', function(){

                $('#delete_feature_image_button').attr("disabled", true);

                $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}");
                $('#feature_image').val("");

                $('#delete_feature_image_button').attr("disabled", false);
            });
            /**
             * End delete feature image button
             */

            /**
             * Start open hour add button
             */
            $('#item_hour_create_button').on('click', function(){

                var item_hour_day_of_week_text = $("#item_hour_day_of_week option:selected").text();
                var item_hour_day_of_week_value = $("#item_hour_day_of_week").val();
                var item_hour_open_time_open_hour = $("#item_hour_open_time_open_hour").val();
                var item_hour_open_time_open_minute = $("#item_hour_open_time_open_minute").val();
                var item_hour_open_time_close_hour = $("#item_hour_open_time_close_hour").val();
                var item_hour_open_time_close_minute = $("#item_hour_open_time_close_minute").val();

                var item_hours_value = item_hour_day_of_week_value + ' ' + item_hour_open_time_open_hour + ':' + item_hour_open_time_open_minute + ' ' + item_hour_open_time_close_hour + ':' + item_hour_open_time_close_minute;
                var item_hour_span_text = item_hour_day_of_week_text + ' ' + item_hour_open_time_open_hour + ':' + item_hour_open_time_open_minute + '-' + item_hour_open_time_close_hour + ':' + item_hour_open_time_close_minute;

                $( "#open_hour_added_hours" ).append("<div class='col-12 col-md-3'><input type='hidden' name='item_hours[]' value='" + item_hours_value + "'>"+item_hour_span_text+"<a class='btn btn-sm text-danger bg-white' onclick='$(this).parent().remove();'><i class='far fa-trash-alt'></i></a></div>");
            });
            /**
             * End open hour add button
             */

            /**
             * Start open hour exception add button
             */
            $('#item_hour_exception_date').datepicker({
                format: 'yyyy-mm-dd',
            });

            $('#item_hour_exception_create_button').on('click', function(){

                var item_hour_exception_date = $("#item_hour_exception_date").val();
                var item_hour_exception_open_time_open_hour = $("#item_hour_exception_open_time_open_hour").val();
                var item_hour_exception_open_time_open_minute = $("#item_hour_exception_open_time_open_minute").val();
                var item_hour_exception_open_time_close_hour = $("#item_hour_exception_open_time_close_hour").val();
                var item_hour_exception_open_time_close_minute = $("#item_hour_exception_open_time_close_minute").val();

                var item_hours_exception_value = item_hour_exception_date;
                var item_hours_exception_span_text = item_hour_exception_date;

                if(item_hour_exception_open_time_open_hour !== "" && item_hour_exception_open_time_open_minute !== "" && item_hour_exception_open_time_close_hour !== "" && item_hour_exception_open_time_close_minute !== "")
                {
                    item_hours_exception_value += ' ' + item_hour_exception_open_time_open_hour + ':' + item_hour_exception_open_time_open_minute + ' ' + item_hour_exception_open_time_close_hour + ':' + item_hour_exception_open_time_close_minute;
                    item_hours_exception_span_text += ' ' + item_hour_exception_open_time_open_hour + ':' + item_hour_exception_open_time_open_minute + '-' + item_hour_exception_open_time_close_hour + ':' + item_hour_exception_open_time_close_minute;
                }
                else
                {
                    item_hours_exception_span_text += " {{ __('item_hour.open-hour-exception-close-all-day') }}";
                }

                $( "#open_hour_added_exceptions" ).append("<div class='col-12 col-md-3'><input type='hidden' name='item_hour_exceptions[]' value='" + item_hours_exception_value + "'>" + item_hours_exception_span_text + "<a class='btn btn-sm text-danger bg-white' onclick='$(this).parent().remove();'><i class='far fa-trash-alt'></i></a></div>");

            });
            /**
             * End open hour exception add button
             */

        });
    </script>

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP)

        <script>
            function initMap()
            {
                const myLatlng = { lat: {{ $site_global_settings->setting_site_location_lat }}, lng: {{ $site_global_settings->setting_site_location_lng }} };
                const map = new google.maps.Map(document.getElementById('map-modal-body'), {
                    zoom: 4,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                let infoWindow = new google.maps.InfoWindow({
                    content: "{{ __('google_map.select-lat-lng-on-map') }}",
                    position: myLatlng,
                });
                infoWindow.open(map);

                var current_lat = 0;
                var current_lng = 0;

                google.maps.event.addListener(map, 'click', function( event ){

                    // Close the current InfoWindow.
                    infoWindow.close();
                    // Create a new InfoWindow.
                    infoWindow = new google.maps.InfoWindow({
                        position: event.latLng,
                    });
                    infoWindow.setContent(
                        JSON.stringify(event.latLng.toJSON(), null, 2)
                    );
                    infoWindow.open(map);

                    current_lat = event.latLng.lat();
                    current_lng = event.latLng.lng();
                    console.log( "Latitude: "+current_lat+" "+", longitude: "+current_lng );
                    $('#lat_lng_span').text("Lat, Lng : " + current_lat + ", " + current_lng);
                });

                $('#lat_lng_confirm').on('click', function(){

                    $('#item_lat').val(current_lat);
                    $('#item_lng').val(current_lng);
                    $('#map-modal').modal('hide');
                });
                $('.lat_lng_select_button').on('click', function(){
                    $('#map-modal').modal('show');
                    //setTimeout(function(){ map.invalidateSize()}, 500);
                });
            }
        </script>

        <script async defer src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap"></script>
    @endif

@endsection
