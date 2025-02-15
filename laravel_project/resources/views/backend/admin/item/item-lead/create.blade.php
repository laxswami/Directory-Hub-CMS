@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('role_permission.item-leads.admin-create') }}</h1>
            <p class="mb-4">{{ __('role_permission.item-leads.admin-create-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.item-leads.index') }}" class="btn btn-info btn-icon-split">
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
            <div class="row">

                <div class="col-12 col-md-8 col-lg-6">
                    <form method="POST" action="{{ route('admin.item-leads.store') }}" class="">
                        @csrf
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="item_slug" class="text-black">{{ __('role_permission.item-leads.listing') }}</label>
                                <select id="item_slug" class="selectpicker form-control @error('item_slug') is-invalid @enderror" name="item_slug" data-live-search="true">
                                    <option value="0">{{ __('role_permission.item-leads.listing-default') }}</option>
                                    @foreach($items as $items_key => $item)
                                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                            <option value="{{ $item->item_slug }}" {{ $item->item_slug == old('item_slug') ? 'selected' : '' }}>{{ $item->item_title . ' | ' . $item->city->city_name . ', ' . $item->state->state_name . ', ' . $item->country->country_name }}</option>
                                        @elseif($item->item_type == \App\Item::ITEM_TYPE_ONLINE)
                                            <option value="{{ $item->item_slug }}" {{ $item->item_slug == old('item_slug') ? 'selected' : '' }}>{{ $item->item_title . ' | ' . __('theme_directory_hub.online-listing.online-listing') }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('role_permission.item-leads.listing-help') }}
                                </small>
                                @error('item_slug')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="item_lead_name" class="text-black">{{ __('role_permission.item-leads.item-lead-name') }}</label>
                                <input id="item_lead_name" type="text" class="form-control @error('item_lead_name') is-invalid @enderror" name="item_lead_name" value="{{ old('item_lead_name') }}">
                                @error('item_lead_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="item_lead_email" class="text-black">{{ __('role_permission.item-leads.item-lead-email') }}</label>
                                <input id="item_lead_email" type="text" class="form-control @error('item_lead_email') is-invalid @enderror" name="item_lead_email" value="{{ old('item_lead_email') }}">
                                @error('item_lead_email')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="item_lead_phone" class="text-black">{{ __('role_permission.item-leads.item-lead-phone') }}</label>
                                <input id="item_lead_phone" type="text" class="form-control @error('item_lead_phone') is-invalid @enderror" name="item_lead_phone" value="{{ old('item_lead_phone') }}">
                                @error('item_lead_phone')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="item_lead_subject" class="text-black">{{ __('role_permission.item-leads.item-lead-subject') }}</label>
                                <input id="item_lead_subject" type="text" class="form-control @error('item_lead_subject') is-invalid @enderror" name="item_lead_subject" value="{{ old('item_lead_subject') }}">
                                @error('item_lead_subject')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="item_lead_message">{{ __('role_permission.item-leads.item-lead-message') }}</label>
                                <textarea rows="6" id="item_lead_message" type="text" class="form-control @error('item_lead_message') is-invalid @enderror" name="item_lead_message">{{ old('item_lead_message') }}</textarea>
                                @error('item_lead_message')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success text-white">
                                    {{ __('backend.shared.create') }}
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

    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.admin.partials.bootstrap-select-locale')

@endsection
