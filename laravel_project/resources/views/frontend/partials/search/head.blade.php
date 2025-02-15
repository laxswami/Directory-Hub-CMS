<form action="{{ route('page.search') }}">
    <div class="row align-items-center">
        <div class="col-lg-12 mb-4 mb-xl-0 col-xl-10 pr-xl-0">

            <div class="input-group">
                <div class="input-group-prepend" id="search-box-query-icon-div">
                    <div class="input-group-text" id="search-box-query-icon"><i class="fas fa-search"></i></div>
                </div>
                <input id="search_query" name="search_query" type="text" class="form-control rounded @error('search_query') is-invalid @enderror" value="{{ old('search_query') ? old('search_query') : (isset($last_search_query) ? $last_search_query : '') }}" placeholder="{{ __('categories.search-query-placeholder') }}">
                @error('search_query')
                <div class="invalid-tooltip">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>

        <div class="col-lg-12 col-xl-2 ml-auto">
            <input type="submit" class="btn btn-primary btn-block rounded text-white" value="{{ __('frontend.search.search') }}">
        </div>

    </div>
</form>
