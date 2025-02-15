@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.message.add-message') }}</h1>
            <p class="mb-4">{{ __('backend.message.add-message-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-info btn-icon-split">
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
            <div class="row mb-5">
                <div class="col-3">
                    @if(empty($item->item_image))
                        <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive rounded">
                    @else
                        <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $item->item_image) }}" class="img-responsive rounded">
                    @endif

                        <a href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

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
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.messages.store') }}" class="">
                        @csrf

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="subject" class="text-black">{{ __('backend.message.subject') }}</label>
                                <input id="subject" type="text" class="form-control @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}" autofocus>
                                @error('subject')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-12">
                                <label class="text-black" for="message">{{ __('backend.message.message-txt') }}</label>
                                <textarea rows="6" id="message" type="text" class="form-control @error('message') is-invalid @enderror" name="message">{{ old('message') }}</textarea>
                                @error('message')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <input type="hidden" name="recipient" value="{{ $item->user_id }}">
                                <input type="hidden" name="item" value="{{ $item->id }}">
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.message.send') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection
