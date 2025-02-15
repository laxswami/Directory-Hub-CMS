@extends('backend.admin.layouts.app')

@section('styles')
    <!-- searchable selector -->
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.custom-field.custom-field') }}</h1>
            <p class="mb-4">{{ __('backend.custom-field.custom-field-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.custom-fields.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.custom-field.add-custom-field') }}</span>
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
                                {{ number_format($custom_fields_count) . ' ' . __('category_description.records') }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr class="bg-info text-white">
                                        <th>{{ __('backend.custom-field.name') }}</th>
                                        <th>{{ __('backend.custom-field.type') }}</th>
                                        <th>{{ __('backend.custom-field.seed-value') }}</th>
                                        <th>{{ __('backend.custom-field.order') }}</th>
                                        <th>{{ __('backend.category.category') }}</th>
                                        <th>{{ __('backend.shared.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($custom_fields as $custom_fields_key => $custom_field)
                                        <tr>
                                            <td>{{ $custom_field->custom_field_name }}</td>
                                            <td>
                                                @if($custom_field->custom_field_type == \App\CustomField::TYPE_TEXT)
                                                    {{ __('backend.custom-field.text') }}
                                                @elseif($custom_field->custom_field_type == \App\CustomField::TYPE_SELECT)
                                                    {{ __('backend.custom-field.select') }}
                                                @elseif($custom_field->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
                                                    {{ __('backend.custom-field.multi-select') }}
                                                @elseif($custom_field->custom_field_type == \App\CustomField::TYPE_LINK)
                                                    {{ __('backend.custom-field.link') }}
                                                @endif
                                            </td>
                                            <td>{{ $custom_field->custom_field_seed_value }}</td>
                                            <td>{{ $custom_field->custom_field_order }}</td>
                                            <td>
                                                @php
                                                    $custom_field_categories = $custom_field->allCategories()->get();
                                                    $custom_field_categories_count = $custom_field_categories->count();
                                                @endphp

                                                @foreach($custom_field_categories as $custom_field_categories_key => $custom_field_category)
                                                    @if($custom_field_categories_count == $custom_field_categories_key + 1)
                                                        {{ $custom_field_category->category_name }}
                                                    @else
                                                        {{ $custom_field_category->category_name . ", " }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ route('admin.custom-fields.edit', ['custom_field' => $custom_field]) }}" class="btn btn-primary btn-circle">
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
                            <form method="GET" action="{{ route('admin.custom-fields.index') }}">

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
                                        <label class="text-gray-800">{{ __('backend.custom-field.type') }}</label>

                                        <div class="form-check">
                                            <input {{ in_array(\App\CustomField::TYPE_TEXT, $filter_custom_field_type) ? 'checked' : '' }} name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="{{ \App\CustomField::TYPE_TEXT }}" id="filter_custom_field_type_{{ \App\CustomField::TYPE_TEXT }}">
                                            <label class="form-check-label" for="filter_custom_field_type_{{ \App\CustomField::TYPE_TEXT }}">
                                                {{ __('backend.custom-field.text') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input {{ in_array(\App\CustomField::TYPE_SELECT, $filter_custom_field_type) ? 'checked' : '' }} name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="{{ \App\CustomField::TYPE_SELECT }}" id="filter_custom_field_type_{{ \App\CustomField::TYPE_SELECT }}">
                                            <label class="form-check-label" for="filter_custom_field_type_{{ \App\CustomField::TYPE_SELECT }}">
                                                {{ __('backend.custom-field.select') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input {{ in_array(\App\CustomField::TYPE_MULTI_SELECT, $filter_custom_field_type) ? 'checked' : '' }} name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="{{ \App\CustomField::TYPE_MULTI_SELECT }}" id="filter_custom_field_type_{{ \App\CustomField::TYPE_MULTI_SELECT }}">
                                            <label class="form-check-label" for="filter_custom_field_type_{{ \App\CustomField::TYPE_MULTI_SELECT }}">
                                                {{ __('backend.custom-field.multi-select') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input {{ in_array(\App\CustomField::TYPE_LINK, $filter_custom_field_type) ? 'checked' : '' }} name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="{{ \App\CustomField::TYPE_LINK }}" id="filter_custom_field_type_{{ \App\CustomField::TYPE_LINK }}">
                                            <label class="form-check-label" for="filter_custom_field_type_{{ \App\CustomField::TYPE_LINK }}">
                                                {{ __('backend.custom-field.link') }}
                                            </label>
                                        </div>
                                        @error('filter_custom_field_type')
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
                                            <option value="{{ \App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_CREATED }}" {{ $filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_CREATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-newest-created') }}</option>
                                            <option value="{{ \App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_CREATED }}" {{ $filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_CREATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-oldest-created') }}</option>

                                            <option value="{{ \App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_UPDATED }}" {{ $filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_UPDATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-newest-updated') }}</option>
                                            <option value="{{ \App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_UPDATED }}" {{ $filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_UPDATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-oldest-updated') }}</option>

                                            <option value="{{ \App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_A_Z }}" {{ $filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_A_Z ? 'selected' : '' }}>{{ __('category_index.custom-field-name-a-z') }}</option>
                                            <option value="{{ \App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_Z_A }}" {{ $filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_Z_A ? 'selected' : '' }}>{{ __('category_index.custom-field-name-z-a') }}</option>

                                            <option value="{{ \App\CustomField::CUSTOM_FIELDS_SORT_BY_MOST_RELEVANT }}" {{ $filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_MOST_RELEVANT ? 'selected' : '' }}>{{ __('item_search.most-relevant') }}</option>
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
                                            <option value="{{ \App\CustomField::COUNT_PER_PAGE_10  }}" {{ $filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_10 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-10') }}</option>
                                            <option value="{{ \App\CustomField::COUNT_PER_PAGE_25 }}" {{ $filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_25 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-25') }}</option>
                                            <option value="{{ \App\CustomField::COUNT_PER_PAGE_50 }}" {{ $filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_50 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-50') }}</option>
                                            <option value="{{ \App\CustomField::COUNT_PER_PAGE_100 }}" {{ $filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_100 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-100') }}</option>
                                            <option value="{{ \App\CustomField::COUNT_PER_PAGE_250 }}" {{ $filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_250 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-250') }}</option>
                                            <option value="{{ \App\CustomField::COUNT_PER_PAGE_500 }}" {{ $filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_500 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-500') }}</option>
                                            <option value="{{ \App\CustomField::COUNT_PER_PAGE_1000 }}" {{ $filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_1000 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-1000') }}</option>
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
                                        <a class="btn btn-outline-primary btn-block" href="{{ route('admin.custom-fields.index') }}">
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

@endsection

@section('scripts')

    <!-- searchable selector -->
    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.admin.partials.bootstrap-select-locale')

    <script>
        $(document).ready(function() {

            "use strict";

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
        });
    </script>
@endsection
