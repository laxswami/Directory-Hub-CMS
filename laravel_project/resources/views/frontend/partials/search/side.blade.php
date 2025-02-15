<form action="{{ route('page.search') }}">
    <div class="mb-2">
        <h3 class="h5 text-black mb-3">
            {{ __('frontend.search.title-search') }}
        </h3>
        <div class="form-group">
            <input name="search_query" type="text" class="form-control rounded @error('search_query') is-invalid @enderror" value="{{ old('search_query') }}" placeholder="{{ __('frontend.search.what-are-you-looking-for') }}">
            @error('search_query')
            <div class="invalid-tooltip invalid-tooltip-side-search-query">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="mb-5">
        <input type="submit" class="btn btn-primary btn-block rounded text-white" value="{{ __('frontend.search.search') }}">
    </div>
</form>
