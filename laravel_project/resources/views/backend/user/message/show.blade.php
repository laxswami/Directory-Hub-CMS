@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.message.show-message') }}</h1>
            <p class="mb-4">{{ __('backend.message.show-message-desc-user') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.messages.index') }}" class="btn btn-info btn-icon-split">
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

                    <a href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2" target="_blank">{{ __('backend.message.view-listing') }}</a>

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

            @if($thread->hasParticipant(Auth::user()->id))
            <div class="row mb-4">
                    <div class="col-12">
                        <form method="POST" action="{{ route('user.messages.update', $thread->id) }}" class="">
                            @csrf
                            @method('PUT')

                            <div class="form-row mb-3">

                                <div class="col-md-12">
                                    <label class="text-black" for="message">{{ __('backend.message.reply-message') }}</label>
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
                                    <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                        {{ __('backend.message.reply') }}
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            @endif

            <span class="text-lg text-gray-800">{{ __('backend.message.subject') }}: {{ $thread->subject }}</span>
            <hr/>
            @foreach($thread->messages as $key => $message)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="media">
                        <div class="media-body">
                            <span class="text-gray-800">{{ __('backend.message.from') }}:</span> <span>{{ $message->user->name }}</span><br/>
                            <span class="text-gray-800">{{ __('backend.message.to') }}:</span> <span> {{ $thread->participantsString($message->user->id) }}</span><br/>
                            <p class="mt-3 mb-3">{!! clean(nl2br($message->body), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                            <small>{{ __('backend.message.posted') }} {{ $message->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div><hr/>
            @endforeach

        </div>
    </div>

@endsection

@section('scripts')
@endsection
