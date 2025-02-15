@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/rateyo/jquery.rateyo.min.css') }}" rel="stylesheet" />

    <!-- searchable selector -->
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.item.item') }}</h1>
            <p class="mb-4">{{ __('backend.item.item-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.item.add-item') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-12 col-md-10">

                    <div class="row pb-2">
                        <div class="col-12">
                            <span class="text-gray-800">
                                {{ number_format($items_count) . ' ' . __('category_description.records') }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr class="bg-info text-white">
                                        <th>{{ __('importer_csv.select') }}</th>
                                        <th>{{ __('backend.item.item') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $items_key => $item)
                                        <tr>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input items_table_index_checkbox" type="checkbox" id="item_index_data_checkbox_{{ $item->id }}" value="{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td>

                                                <div class="row">
                                                    <div class="col-12 col-md-3">
                                                        @if(!empty($item->item_image_tiny))
                                                            <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image" class="img-fluid rounded">
                                                        @elseif(!empty($item->item_image))
                                                            <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                                                        @else
                                                            <img src="{{ asset('backend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                        @endif
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        @if($item->item_status == \App\Item::ITEM_SUBMITTED)
                                                            <span class="text-warning"><i class="fas fa-exclamation-circle"></i></span>
                                                        @elseif($item->item_status == \App\Item::ITEM_PUBLISHED)
                                                            <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                        @elseif($item->item_status == \App\Item::ITEM_SUSPENDED)
                                                            <span class="text-danger"><i class="fas fa-ban"></i></span>
                                                        @endif
                                                        <span class="text-gray-800">{{ $item->item_title }}</span>
                                                        @if($item->item_featured == \App\Item::ITEM_FEATURED)
                                                            <span class="text-white bg-info pl-1 pr-1 rounded">{{ __('prefer_country.featured') }}</span>
                                                        @endif
                                                            <div class="pt-1 pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ empty($item->item_average_rating) ? 0 : $item->item_average_rating }}"></div>
                                                            <span>
                                                                {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                                            </span>

                                                            <br>
                                                            @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                                                <i class="fas fa-map-marker-alt"></i>
                                                                {{ $item->item_address }},
                                                                {{ $item->city->city_name }},
                                                                {{ $item->state->state_name }},
                                                                {{ $item->country->country_name }}
                                                                {{ $item->item_postal_code }}
                                                            @else
                                                                <span class="bg-primary text-white pl-1 pr-1 rounded">{{ __('theme_directory_hub.online-listing.online-listing') }}</span>
                                                            @endif

                                                            <hr class="mt-2 mb-2">
                                                            <div class="row align-items-center">
                                                                <div class="col-3 col-sm-2 col-md-3 col-lg-2 col-xl-1">
                                                                    @if(empty($item->user->user_image))
                                                                        <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval($item->user->id % 10) . '.webp') }}" class="img-fluid rounded-circle">
                                                                    @else
                                                                        <img id="image_preview" src="{{ Storage::disk('public')->url('user/'. $item->user->user_image) }}" class="img-fluid rounded-circle">
                                                                    @endif
                                                                </div>
                                                                <div class="col-9 col-sm-10 col-md-9 col-lg-10 col-xl-11">
                                                                    <span>
                                                                        {{ $item->user->name . ' - ' . $item->user->email }}
                                                                        |
                                                                        <a href="{{ route('admin.users.edit', ['user' => $item->user->id]) }}" class="text-info" target="_blank">
                                                                            <i class="far fa-address-card"></i>
                                                                            {{ __('backend.sidebar.profile') }}
                                                                        </a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <hr class="mt-2 mb-2">

                                                            <div class="pt-2">
                                                            @foreach($item->allCategories()->get() as $categories_key => $category)
                                                                <span class="border border-info text-info pl-1 pr-1 rounded">{{ $category->category_name }}</span>
                                                            @endforeach
                                                            </div>

                                                            <hr class="mt-3 mb-2">
                                                            @if($item->item_status == \App\Item::ITEM_PUBLISHED)
                                                            <a href="{{ route('page.item', $item->item_slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-external-link-alt"></i>
                                                                {{ __('prefer_country.view-item') }}
                                                            </a>
                                                            @endif
                                                            <a href="{{ route('admin.items.edit', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="far fa-edit"></i>
                                                                {{ __('backend.shared.edit') }}
                                                            </a>
                                                            <hr class="mt-2 mb-2">
                                                            <span class="text-info">
                                                                <i class="far fa-plus-square"></i>
                                                                {{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                                            </span>
                                                            @if($item->created_at != $item->updated_at)
                                                                <span class="text-info">
                                                                    |
                                                                    <i class="far fa-edit"></i>
                                                                    {{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}
                                                                </span>
                                                            @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-8">
                            <button id="select_all_button" class="btn btn-sm btn-primary text-white">
                                <i class="far fa-check-square"></i>
                                {{ __('admin_users_table.shared.select-all') }}
                            </button>
                            <button id="un_select_all_button" class="btn btn-sm btn-primary text-white">
                                <i class="far fa-square"></i>
                                {{ __('admin_users_table.shared.un-select-all') }}
                            </button>
                        </div>
                        <div class="col-12 col-md-4 text-right">
                            <div class="dropdown">
                                <button class="btn btn-info btn-sm dropdown-toggle text-white" type="button" id="table_option_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-tasks"></i>
                                    {{ __('admin_users_table.shared.options') }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="table_option_dropdown">
                                    <button class="dropdown-item" type="button" id="approve_selected_button">
                                        <i class="fas fa-check-circle"></i>
                                        {{ __('item_index.approve-selected') }}
                                    </button>
                                    <button class="dropdown-item" type="button" id="disapprove_selected_button">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ __('item_index.disapprove-selected') }}
                                    </button>
                                    <button class="dropdown-item" type="button" id="suspend_selected_button">
                                        <i class="fas fa-ban"></i>
                                        {{ __('item_index.suspend-selected') }}
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteModal">
                                        <i class="far fa-trash-alt"></i>
                                        {{ __('item_index.delete-selected') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{ $pagination->links() }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-2 pt-3 border-left-info">

                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800">
                                <i class="fas fa-filter"></i>
                                {{ __('listings_filter.filters') }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <form method="GET" action="{{ route('admin.items.index') }}">

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label for="search_query" class="text-black">{{ __('frontend.search.search') }}</label>
                                        <input id="search_query" type="text" class="form-control @error('search_query') is-invalid @enderror" name="search_query" value="{{ $search_query }}">
                                        @error('search_query')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label for="filter_categories" class="text-gray-800">{{ __('listings_filter.categories') }}</label>

                                        @foreach($all_printable_categories as $key => $all_printable_category)
                                            <div class="form-check filter_category_div">
                                                <input {{ in_array($all_printable_category['category_id'], $filter_categories) ? 'checked' : '' }} name="filter_categories[]" class="form-check-input" type="checkbox" value="{{ $all_printable_category['category_id'] }}" id="filter_categories_{{ $all_printable_category['category_id'] }}">
                                                <label class="form-check-label" for="filter_categories_{{ $all_printable_category['category_id'] }}">
                                                    {{ $all_printable_category['category_name'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                        <a href="javascript:;" class="show_more">{{ __('listings_filter.show-more') }}</a>
                                        @error('filter_categories')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_country">{{ __('backend.setting.country') }}</label>
                                        <select class="selectpicker form-control @error('filter_country') is-invalid @enderror" name="filter_country" id="filter_country" data-live-search="true">
                                            <option value="" {{ empty($filter_country) ? 'selected' : '' }}>{{ __('prefer_country.all-country') }}</option>
                                            @foreach($all_countries as $all_countries_key => $country)
                                                <option value="{{ $country->id }}" {{ $filter_country == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('filter_country')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_state">{{ __('backend.state.state') }}</label>
                                        <select class="selectpicker form-control @error('filter_state') is-invalid @enderror" name="filter_state" id="filter_state" data-live-search="true">
                                            <option value="" {{ empty($filter_state) ? 'selected' : '' }}>{{ __('prefer_country.all-state') }}</option>
                                            @foreach($all_states as $all_states_key => $state)
                                                <option value="{{ $state->id }}" {{ $filter_state == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('filter_state')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_city">{{ __('backend.city.city') }}</label>
                                        <select class="selectpicker form-control @error('filter_city') is-invalid @enderror" name="filter_city" id="filter_city" data-live-search="true">
                                            <option value="" {{ empty($filter_city) ? 'selected' : '' }}>{{ __('prefer_country.all-city') }}</option>
                                            @foreach($all_cities as $all_cities_key => $city)
                                                <option value="{{ $city->id }}" {{ $filter_city == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('filter_city')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800">{{ __('backend.item.item') }}</label>

                                        <div class="form-check">
                                            <input {{ in_array(\App\Item::ITEM_SUBMITTED, $filter_item_status) ? 'checked' : '' }} name="filter_item_status[]" class="form-check-input" type="checkbox" value="{{ \App\Item::ITEM_SUBMITTED }}" id="filter_item_status_{{ \App\Item::ITEM_SUBMITTED }}">
                                            <label class="form-check-label" for="filter_item_status_{{ \App\Item::ITEM_SUBMITTED }}">
                                                {{ __('backend.item.submitted') }}
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input {{ in_array(\App\Item::ITEM_PUBLISHED, $filter_item_status) ? 'checked' : '' }} name="filter_item_status[]" class="form-check-input" type="checkbox" value="{{ \App\Item::ITEM_PUBLISHED }}" id="filter_item_status_{{ \App\Item::ITEM_PUBLISHED }}">
                                            <label class="form-check-label" for="filter_item_status_{{ \App\Item::ITEM_PUBLISHED }}">
                                                {{ __('backend.item.published') }}
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input {{ in_array(\App\Item::ITEM_SUSPENDED, $filter_item_status) ? 'checked' : '' }} name="filter_item_status[]" class="form-check-input" type="checkbox" value="{{ \App\Item::ITEM_SUSPENDED }}" id="filter_item_status_{{ \App\Item::ITEM_SUSPENDED }}">
                                            <label class="form-check-label" for="filter_item_status_{{ \App\Item::ITEM_SUSPENDED }}">
                                                {{ __('backend.item.suspended') }}
                                            </label>
                                        </div>
                                        @error('filter_item_status')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                        <div class="form-check">
                                            <input {{ in_array(\App\Item::ITEM_FEATURED, $filter_item_featured) ? 'checked' : '' }} name="filter_item_featured[]" class="form-check-input" type="checkbox" value="{{ \App\Item::ITEM_FEATURED }}" id="filter_item_featured_{{ \App\Item::ITEM_FEATURED }}">
                                            <label class="form-check-label" for="filter_item_featured_{{ \App\Item::ITEM_FEATURED }}">
                                                {{ __('prefer_country.featured') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input {{ in_array(\App\Item::ITEM_NOT_FEATURED, $filter_item_featured) ? 'checked' : '' }} name="filter_item_featured[]" class="form-check-input" type="checkbox" value="{{ \App\Item::ITEM_NOT_FEATURED }}" id="filter_item_featured_{{ \App\Item::ITEM_NOT_FEATURED }}">
                                            <label class="form-check-label" for="filter_item_featured_{{ \App\Item::ITEM_NOT_FEATURED }}">
                                                {{ __('prefer_country.not-featured') }}
                                            </label>
                                        </div>
                                        @error('filter_item_featured')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                        <div class="form-check">
                                            <input {{ in_array(\App\Item::ITEM_TYPE_REGULAR, $filter_item_type) ? 'checked' : '' }} name="filter_item_type[]" class="form-check-input" type="checkbox" value="{{ \App\Item::ITEM_TYPE_REGULAR }}" id="filter_item_type_{{ \App\Item::ITEM_TYPE_REGULAR }}">
                                            <label class="form-check-label" for="filter_item_type_{{ \App\Item::ITEM_TYPE_REGULAR }}">
                                                {{ __('theme_directory_hub.online-listing.regular-listing') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input {{ in_array(\App\Item::ITEM_TYPE_ONLINE, $filter_item_type) ? 'checked' : '' }} name="filter_item_type[]" class="form-check-input" type="checkbox" value="{{ \App\Item::ITEM_TYPE_ONLINE }}" id="filter_item_type_{{ \App\Item::ITEM_TYPE_ONLINE }}">
                                            <label class="form-check-label" for="filter_item_type_{{ \App\Item::ITEM_TYPE_ONLINE }}">
                                                {{ __('theme_directory_hub.online-listing.online-listing') }}
                                            </label>
                                        </div>
                                        @error('filter_item_type')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                                <hr>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_sort_by">{{ __('listings_filter.sort-by') }}</label>
                                        <select class="selectpicker form-control @error('filter_sort_by') is-invalid @enderror" name="filter_sort_by" id="filter_sort_by">
                                            <option value="{{ \App\Item::ITEMS_SORT_BY_NEWEST_CREATED }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_NEWEST_CREATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-newest-created') }}</option>
                                            <option value="{{ \App\Item::ITEMS_SORT_BY_OLDEST_CREATED }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_OLDEST_CREATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-oldest-created') }}</option>

                                            <option value="{{ \App\Item::ITEMS_SORT_BY_NEWEST_UPDATED }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_NEWEST_UPDATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-newest-updated') }}</option>
                                            <option value="{{ \App\Item::ITEMS_SORT_BY_OLDEST_UPDATED }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_OLDEST_UPDATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-oldest-updated') }}</option>

                                            <option value="{{ \App\Item::ITEMS_SORT_BY_HIGHEST_RATING }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_HIGHEST_RATING ? 'selected' : '' }}>{{ __('listings_filter.sort-by-highest') }}</option>
                                            <option value="{{ \App\Item::ITEMS_SORT_BY_LOWEST_RATING }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_LOWEST_RATING ? 'selected' : '' }}>{{ __('listings_filter.sort-by-lowest') }}</option>

                                            <option value="{{ \App\Item::ITEMS_SORT_BY_MOST_RELEVANT }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_MOST_RELEVANT ? 'selected' : '' }}>{{ __('item_search.most-relevant') }}</option>
                                        </select>
                                        @error('filter_sort_by')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_count_per_page">{{ __('prefer_country.rows-per-page') }}</label>
                                        <select class="selectpicker form-control @error('filter_count_per_page') is-invalid @enderror" name="filter_count_per_page" id="filter_count_per_page">
                                            <option value="{{ \App\Item::COUNT_PER_PAGE_10  }}" {{ $filter_count_per_page == \App\Item::COUNT_PER_PAGE_10 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-10') }}</option>
                                            <option value="{{ \App\Item::COUNT_PER_PAGE_25 }}" {{ $filter_count_per_page == \App\Item::COUNT_PER_PAGE_25 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-25') }}</option>
                                            <option value="{{ \App\Item::COUNT_PER_PAGE_50 }}" {{ $filter_count_per_page == \App\Item::COUNT_PER_PAGE_50 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-50') }}</option>
                                            <option value="{{ \App\Item::COUNT_PER_PAGE_100 }}" {{ $filter_count_per_page == \App\Item::COUNT_PER_PAGE_100 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-100') }}</option>
                                            <option value="{{ \App\Item::COUNT_PER_PAGE_250 }}" {{ $filter_count_per_page == \App\Item::COUNT_PER_PAGE_250 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-250') }}</option>
                                            <option value="{{ \App\Item::COUNT_PER_PAGE_500 }}" {{ $filter_count_per_page == \App\Item::COUNT_PER_PAGE_500 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-500') }}</option>
                                            <option value="{{ \App\Item::COUNT_PER_PAGE_1000 }}" {{ $filter_count_per_page == \App\Item::COUNT_PER_PAGE_1000 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-1000') }}</option>
                                        </select>
                                        @error('filter_count_per_page')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-block">{{ __('backend.shared.update') }}</button>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-12">
                                        <a class="btn btn-outline-primary btn-block" href="{{ route('admin.items.index') }}">
                                            {{ __('theme_directory_hub.filter-link-reset-all') }}
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Start forms for selected buttons -->
    <form action="{{ route('admin.items.bulk.approve', $request_query_array) }}" method="POST" id="form_approve_selected">
        @csrf
    </form>

    <form action="{{ route('admin.items.bulk.disapprove', $request_query_array) }}" method="POST" id="form_disapprove_selected">
        @csrf
    </form>

    <form action="{{ route('admin.items.bulk.suspend', $request_query_array) }}" method="POST" id="form_suspend_selected">
        @csrf
    </form>
    <!-- End forms for selected buttons -->

    <!-- Modal Delete Item -->
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
                    {{ __('item_index.delete-selected-items-confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>

                    <form action="{{ route('admin.items.bulk.delete', $request_query_array) }}" method="POST" id="form_delete_selected">
                        @csrf
                        <button id="delete_selected_button"  class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script src="{{ asset('backend/vendor/rateyo/jquery.rateyo.min.js') }}"></script>

    <!-- searchable selector -->
    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.admin.partials.bootstrap-select-locale')

    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {

            "use strict";

            /**
             * Start select all button
             */
            $('#select_all_button').on('click', function () {
                $(".items_table_index_checkbox").each(function (index) {
                    $(this).prop('checked', true);
                });
            });
            /**
             * End select all button
             */

            /**
             * Start un-select all button
             */
            $('#un_select_all_button').on('click', function () {
                $(".items_table_index_checkbox").each(function (index) {
                    $(this).prop('checked', false);
                });
            });
            /**
             * End un-select all button
             */

            /**
             * Start approve selected button action
             */
            $('#approve_selected_button').on('click', function () {

                $(".items_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'item_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_approve_selected');

                });

                $("#form_approve_selected").submit();
            });
            /**
             * End approve selected button action
             */

            /**
             * Start disapprove selected button action
             */
            $('#disapprove_selected_button').on('click', function () {

                $(".items_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'item_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_disapprove_selected');

                });

                $("#form_disapprove_selected").submit();
            });
            /**
             * End disapprove selected button action
             */

            /**
             * Start suspend selected button action
             */
            $('#suspend_selected_button').on('click', function () {

                $(".items_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'item_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_suspend_selected');

                });

                $("#form_suspend_selected").submit();
            });
            /**
             * End suspend selected button action
             */

            /**
             * Start delete selected button action
             */
            $('#delete_selected_button').on('click', function () {

                $(".items_table_index_checkbox:checked").each(function (index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'item_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_delete_selected');

                });

                $("#form_delete_selected").submit();
            });
            /**
             * End delete selected button action
             */

            /**
             * Start show more/less
             */
            //this will execute on page load(to be more specific when document ready event occurs)
            if ($(".filter_category_div").length > 5)
            {
                $(".filter_category_div:gt(5)").hide();
                $(".show_more").show();
            }

            $(".show_more").on('click', function() {
                //toggle elements with class .ty-compact-list that their index is bigger than 2
                $(".filter_category_div:gt(5)").toggle();
                //change text of show more element just for demonstration purposes to this demo
                $(this).text() === "{{ __('listings_filter.show-more') }}" ? $(this).text("{{ __('listings_filter.show-less') }}") : $(this).text("{{ __('listings_filter.show-more') }}");
            });
            /**
             * End show more/less
             */

            /**
             * Start country, state, city selector
             */

            $('#filter_country').on('change', function() {

                if(this.value > 0)
                {
                    $('#filter_state').html("<option selected>{{ __('prefer_country.loading-wait') }}</option>");
                    $('#filter_state').selectpicker('refresh');

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
                            console.log(result);
                            $('#filter_state').html("<option value=''>{{ __('prefer_country.all-state') }}</option>");
                            $('#filter_city').html("<option value=''>{{ __('prefer_country.all-city') }}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var state_id = value.id;
                                var state_name = value.state_name;
                                $('#filter_state').append('<option value="'+ state_id +'">' + state_name + '</option>');
                            });
                            $('#filter_state').selectpicker('refresh');
                        }});
                }
                else
                {
                    $('#filter_state').html("<option value=''>{{ __('prefer_country.all-state') }}</option>");
                    $('#filter_city').html("<option value=''>{{ __('prefer_country.all-city') }}</option>");
                    $('#filter_state').selectpicker('refresh');
                    $('#filter_city').selectpicker('refresh');
                }

            });


            $('#filter_state').on('change', function() {

                if(this.value > 0)
                {
                    $('#filter_city').html("<option selected>{{ __('prefer_country.loading-wait') }}</option>");
                    $('#filter_city').selectpicker('refresh');

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
                            console.log(result);
                            $('#filter_city').html("<option value=''>{{ __('prefer_country.all-city') }}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#filter_city').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            $('#filter_city').selectpicker('refresh');
                        }});
                }
                else
                {
                    $('#filter_city').html("<option value=''>{{ __('prefer_country.all-city') }}</option>");
                    $('#filter_city').selectpicker('refresh');
                }

            });
            /**
             * End country, state, city selector
             */


            /**
             * Start initial rating stars for listing box elements
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
             * End initial rating stars for listing box elements
             */
        });
    </script>
@endsection
