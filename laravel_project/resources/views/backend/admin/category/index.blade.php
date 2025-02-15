@extends('backend.admin.layouts.app')

@section('styles')
    <!-- searchable selector -->
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.category.category') }}</h1>
            <p class="mb-4">{{ __('backend.category.category-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.category.add-category') }}</span>
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
                                {{ number_format($categories_count) . ' ' . __('category_description.records') }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr class="bg-info text-white">
                                        <th>{{ __('backend.category.name') }}</th>
                                        <th>{{ __('backend.category.slug') }}</th>
                                        <th>{{ __('category_image_option.index-table-icon-image') }}</th>
                                        <th>{{ __('categories.parent-cat') }}</th>
                                        <th>{{ __('backend.shared.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $categories_key => $category)
                                        <tr>
                                            <td>{{ $category->category_name }}</td>
                                            <td>{{ $category->category_slug }}</td>
                                            <td>
                                                @if(!empty($category->category_icon))
                                                    <i class="{{ $category->category_icon }}"></i>
                                                    {{ $category->category_icon }}
                                                @endif

                                                @if(!empty($category->category_image))
                                                    <hr>
                                                    <img class="img-responsive category-img-preview" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image) }}">
                                                @endif
                                                <hr>
                                                @if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON)
                                                    <span class="text-sm border border-info text-info pl-1 pr-1 rounded">
                                                        <i class="fa-solid fa-font-awesome"></i>
                                                        {{ __('category_image_option.form-category-icon-image-select-icon') }}
                                                    </span>
                                                @else
                                                    <span class="text-sm border border-info text-info pl-1 pr-1 rounded">
                                                        <i class="fa-solid fa-image"></i>
                                                        {{ __('category_image_option.form-category-icon-image-select-image') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($category->category_parent_id))
                                                    {{ \App\Category::find($category->category_parent_id)->category_name }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.categories.edit', ['category' => $category]) }}" class="btn btn-primary btn-circle">
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
                            <form method="GET" action="{{ route('admin.categories.index') }}">

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

                                <hr>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_sort_by">{{ __('listings_filter.sort-by') }}</label>
                                        <select class="selectpicker form-control @error('filter_sort_by') is-invalid @enderror" name="filter_sort_by" id="filter_sort_by">
                                            <option value="{{ \App\Category::CATEGORIES_SORT_BY_NEWEST_CREATED }}" {{ $filter_sort_by == \App\Category::CATEGORIES_SORT_BY_NEWEST_CREATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-newest-created') }}</option>
                                            <option value="{{ \App\Category::CATEGORIES_SORT_BY_OLDEST_CREATED }}" {{ $filter_sort_by == \App\Category::CATEGORIES_SORT_BY_OLDEST_CREATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-oldest-created') }}</option>

                                            <option value="{{ \App\Category::CATEGORIES_SORT_BY_NEWEST_UPDATED }}" {{ $filter_sort_by == \App\Category::CATEGORIES_SORT_BY_NEWEST_UPDATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-newest-updated') }}</option>
                                            <option value="{{ \App\Category::CATEGORIES_SORT_BY_OLDEST_UPDATED }}" {{ $filter_sort_by == \App\Category::CATEGORIES_SORT_BY_OLDEST_UPDATED ? 'selected' : '' }}>{{ __('prefer_country.item-sort-by-oldest-updated') }}</option>

                                            <option value="{{ \App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_A_Z }}" {{ $filter_sort_by == \App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_A_Z ? 'selected' : '' }}>{{ __('category_index.category-name-a-z') }}</option>
                                            <option value="{{ \App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_Z_A }}" {{ $filter_sort_by == \App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_Z_A ? 'selected' : '' }}>{{ __('category_index.category-name-z-a') }}</option>

                                            <option value="{{ \App\Category::CATEGORIES_SORT_BY_MOST_RELEVANT }}" {{ $filter_sort_by == \App\Category::CATEGORIES_SORT_BY_MOST_RELEVANT ? 'selected' : '' }}>{{ __('item_search.most-relevant') }}</option>
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
                                            <option value="{{ \App\Category::COUNT_PER_PAGE_10  }}" {{ $filter_count_per_page == \App\Category::COUNT_PER_PAGE_10 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-10') }}</option>
                                            <option value="{{ \App\Category::COUNT_PER_PAGE_25 }}" {{ $filter_count_per_page == \App\Category::COUNT_PER_PAGE_25 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-25') }}</option>
                                            <option value="{{ \App\Category::COUNT_PER_PAGE_50 }}" {{ $filter_count_per_page == \App\Category::COUNT_PER_PAGE_50 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-50') }}</option>
                                            <option value="{{ \App\Category::COUNT_PER_PAGE_100 }}" {{ $filter_count_per_page == \App\Category::COUNT_PER_PAGE_100 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-100') }}</option>
                                            <option value="{{ \App\Category::COUNT_PER_PAGE_250 }}" {{ $filter_count_per_page == \App\Category::COUNT_PER_PAGE_250 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-250') }}</option>
                                            <option value="{{ \App\Category::COUNT_PER_PAGE_500 }}" {{ $filter_count_per_page == \App\Category::COUNT_PER_PAGE_500 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-500') }}</option>
                                            <option value="{{ \App\Category::COUNT_PER_PAGE_1000 }}" {{ $filter_count_per_page == \App\Category::COUNT_PER_PAGE_1000 ? 'selected' : '' }}>{{ __('importer_csv.import-listing-per-page-1000') }}</option>
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
                                        <a class="btn btn-outline-primary btn-block" href="{{ route('admin.categories.index') }}">
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
            // "use strict";
        });
    </script>
@endsection
