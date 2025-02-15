@extends('backend.admin.layouts.app')

@section('styles')
    <!-- searchable selector -->
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('importer_csv.import-listing-edit') }}</h1>
            <p class="mb-4">{{ __('importer_csv.import-listing-edit-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.importer.item.data.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row border-left-info mb-3">
                <div class="col-12 col-md-4">
                    {{ __('importer_csv.import-listing-source') }}
                </div>
                <div class="col-12 col-md-8 text-right">
                    @if($import_item_data->import_item_data_source == \App\ImportItemData::SOURCE_CSV)
                        <span class="bg-info rounded pl-2 pr-2 pt-1 pb-1 text-white">{{ __('importer_csv.import-listing-source-csv') }}</span>
                    @endif
                </div>
            </div>

            <div class="row border-left-info mb-2">
                <div class="col-12 col-md-4">
                    {{ __('importer_csv.import-listing-status') }}
                </div>
                <div class="col-12 col-md-8 text-right">

                    @if($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_NOT_PROCESSED)
                        <span class="bg-warning rounded pl-2 pr-2 pt-1 pb-1 text-white">{{ __('importer_csv.import-listing-status-not-processed') }}</span>
                    @elseif($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
                        <span class="bg-success rounded pl-2 pr-2 pt-1 pb-1 text-white">{{ __('importer_csv.import-listing-status-success') }}</span>
                    @elseif($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_ERROR)
                        <span class="bg-danger rounded pl-2 pr-2 pt-1 pb-1 text-white">{{ __('importer_csv.import-listing-status-error') }}</span>
                    @endif

                </div>
            </div>

            @if($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_ERROR && !empty($import_item_data->import_item_data_process_error_log))
                <div class="row mt-3 mb-2">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <p class="mb-0">{{ __('importer_csv.import-listing-error-log') . ": " . $import_item_data->import_item_data_process_error_log }}</p>
                        </div>
                    </div>
                </div>
            @endif


            <hr>
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.importer.item.data.update', ['import_item_data' => $import_item_data]) }}" class="">
                        @csrf
                        @method('PUT')

                        <div class="row form-group">
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_item_title" class="text-black">{{ __('importer_csv.import-listing-title') }}</label>
                                <input id="import_item_data_item_title" type="text" class="form-control @error('import_item_data_item_title') is-invalid @enderror" name="import_item_data_item_title" value="{{ old('import_item_data_item_title') ? old('import_item_data_item_title') : $import_item_data->import_item_data_item_title }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_title')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_item_slug" class="text-black">{{ __('importer_csv.import-listing-slug') }}</label>
                                <input id="import_item_data_item_slug" type="text" class="form-control @error('import_item_data_item_slug') is-invalid @enderror" name="import_item_data_item_slug" value="{{ old('import_item_data_item_slug') ? old('import_item_data_item_slug') : $import_item_data->import_item_data_item_slug }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_slug')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_item_address" class="text-black">{{ __('importer_csv.import-listing-address') }}</label>
                                <input id="import_item_data_item_address" type="text" class="form-control @error('import_item_data_item_address') is-invalid @enderror" name="import_item_data_item_address" value="{{ old('import_item_data_item_address') ? old('import_item_data_item_address') : $import_item_data->import_item_data_item_address }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_address')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_city" class="text-black">{{ __('importer_csv.import-listing-city') }}</label>
                                <input id="import_item_data_city" type="text" class="form-control @error('import_item_data_city') is-invalid @enderror" name="import_item_data_city" value="{{ old('import_item_data_city') ? old('import_item_data_city') : $import_item_data->import_item_data_city }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_city')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_state" class="text-black">{{ __('importer_csv.import-listing-state') }}</label>
                                <input id="import_item_data_state" type="text" class="form-control @error('import_item_data_state') is-invalid @enderror" name="import_item_data_state" value="{{ old('import_item_data_state') ? old('import_item_data_state') : $import_item_data->import_item_data_state }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_state')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_country" class="text-black">{{ __('importer_csv.import-listing-country') }}</label>
                                <input id="import_item_data_country" type="text" class="form-control @error('import_item_data_country') is-invalid @enderror" name="import_item_data_country" value="{{ old('import_item_data_country') ? old('import_item_data_country') : $import_item_data->import_item_data_country }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_country')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_item_lat" class="text-black">{{ __('importer_csv.import-listing-lat') }}</label>
                                <input id="import_item_data_item_lat" type="text" class="form-control @error('import_item_data_item_lat') is-invalid @enderror" name="import_item_data_item_lat" value="{{ old('import_item_data_item_lat') ? old('import_item_data_item_lat') : $import_item_data->import_item_data_item_lat }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_lat')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_item_lng" class="text-black">{{ __('importer_csv.import-listing-lng') }}</label>
                                <input id="import_item_data_item_lng" type="text" class="form-control @error('import_item_data_item_lng') is-invalid @enderror" name="import_item_data_item_lng" value="{{ old('import_item_data_item_lng') ? old('import_item_data_item_lng') : $import_item_data->import_item_data_item_lng }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_lng')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="import_item_data_item_postal_code" class="text-black">{{ __('importer_csv.import-listing-postal-code') }}</label>
                                <input id="import_item_data_item_postal_code" type="text" class="form-control @error('import_item_data_item_postal_code') is-invalid @enderror" name="import_item_data_item_postal_code" value="{{ old('import_item_data_item_postal_code') ? old('import_item_data_item_postal_code') : $import_item_data->import_item_data_item_postal_code }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_postal_code')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <label for="import_item_data_item_description" class="text-black">{{ __('importer_csv.import-listing-description') }}</label>
                                <textarea class="form-control @error('import_item_data_item_description') is-invalid @enderror" id="import_item_data_item_description" rows="5" name="import_item_data_item_description" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>{{ old('import_item_data_item_description') ? old('import_item_data_item_description') : $import_item_data->import_item_data_item_description }}</textarea>
                                @error('import_item_data_item_description')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_phone" class="text-black">{{ __('importer_csv.import-listing-phone') }}</label>
                                <input id="import_item_data_item_phone" type="text" class="form-control @error('import_item_data_item_phone') is-invalid @enderror" name="import_item_data_item_phone" value="{{ old('import_item_data_item_phone') ? old('import_item_data_item_phone') : $import_item_data->import_item_data_item_phone }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_phone')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_website" class="text-black">{{ __('importer_csv.import-listing-website') }}</label>
                                <input id="import_item_data_item_website" type="text" class="form-control @error('import_item_data_item_website') is-invalid @enderror" name="import_item_data_item_website" value="{{ old('import_item_data_item_website') ? old('import_item_data_item_website') : $import_item_data->import_item_data_item_website }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                <small class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('import_item_data_item_website')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_social_facebook" class="text-black">{{ __('importer_csv.import-listing-facebook') }}</label>
                                <input id="import_item_data_item_social_facebook" type="text" class="form-control @error('import_item_data_item_social_facebook') is-invalid @enderror" name="import_item_data_item_social_facebook" value="{{ old('import_item_data_item_social_facebook') ? old('import_item_data_item_social_facebook') : $import_item_data->import_item_data_item_social_facebook }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                <small class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('import_item_data_item_social_facebook')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_social_twitter" class="text-black">{{ __('importer_csv.import-listing-twitter') }}</label>
                                <input id="import_item_data_item_social_twitter" type="text" class="form-control @error('import_item_data_item_social_twitter') is-invalid @enderror" name="import_item_data_item_social_twitter" value="{{ old('import_item_data_item_social_twitter') ? old('import_item_data_item_social_twitter') : $import_item_data->import_item_data_item_social_twitter }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                <small class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('import_item_data_item_social_twitter')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_social_linkedin" class="text-black">{{ __('importer_csv.import-listing-linkedin') }}</label>
                                <input id="import_item_data_item_social_linkedin" type="text" class="form-control @error('import_item_data_item_social_linkedin') is-invalid @enderror" name="import_item_data_item_social_linkedin" value="{{ old('import_item_data_item_social_linkedin') ? old('import_item_data_item_social_linkedin') : $import_item_data->import_item_data_item_social_linkedin }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                <small class="form-text text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('import_item_data_item_social_linkedin')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_social_instagram" class="text-black">{{ __('item_whatsapp_instagram.item-social-instagram') }}</label>
                                <input id="import_item_data_item_social_instagram" type="text" class="form-control @error('import_item_data_item_social_instagram') is-invalid @enderror" name="import_item_data_item_social_instagram" value="{{ old('import_item_data_item_social_instagram') ? old('import_item_data_item_social_instagram') : $import_item_data->import_item_data_item_social_instagram }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                <small class="form-text text-muted">
                                    {{ __('item_whatsapp_instagram.item-social-instagram-help') }}
                                </small>
                                @error('import_item_data_item_social_instagram')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_social_whatsapp" class="text-black">{{ __('item_whatsapp_instagram.item-social-whatsapp') }}</label>
                                <input id="import_item_data_item_social_whatsapp" type="text" class="form-control @error('import_item_data_item_social_whatsapp') is-invalid @enderror" name="import_item_data_item_social_whatsapp" value="{{ old('import_item_data_item_social_whatsapp') ? old('import_item_data_item_social_whatsapp') : $import_item_data->import_item_data_item_social_whatsapp }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                <small class="form-text text-muted">
                                    {{ __('item_whatsapp_instagram.item-social-whatsapp-help') }}
                                </small>
                                @error('import_item_data_item_social_whatsapp')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="import_item_data_item_youtube_id" class="text-black">{{ __('importer_csv.import-listing-youtube-id') }}</label>
                                <input id="import_item_data_item_youtube_id" type="text" class="form-control @error('import_item_data_item_youtube_id') is-invalid @enderror" name="import_item_data_item_youtube_id" value="{{ old('import_item_data_item_youtube_id') ? old('import_item_data_item_youtube_id') : $import_item_data->import_item_data_item_youtube_id }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                @error('import_item_data_item_youtube_id')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 col-md-6">
                                <label for="import_item_data_item_hour_time_zone" class="text-black">{{ __('item_hour.timezone') }}</label>
                                <select id="import_item_data_item_hour_time_zone" class="selectpicker form-control @error('import_item_data_item_hour_time_zone') is-invalid @enderror" name="import_item_data_item_hour_time_zone" data-live-search="true" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                    @foreach($time_zone_identifiers as $time_zone_identifiers_key => $time_zone_identifier)
                                        <option value="{{ $time_zone_identifier }}" {{ (old('import_item_data_item_hour_time_zone') ? old('import_item_data_item_hour_time_zone') : $import_item_data->import_item_data_item_hour_time_zone) == $time_zone_identifier ? 'selected' : '' }}>{{ $time_zone_identifier }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('item_hour.timezone-help') }}
                                </small>
                                @error('import_item_data_item_hour_time_zone')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="import_item_data_item_hour_show_hours" class="text-black">{{ __('item_hour.show-open-hour') }}</label>
                                <select id="import_item_data_item_hour_show_hours" class="selectpicker form-control @error('import_item_data_item_hour_show_hours') is-invalid @enderror" name="import_item_data_item_hour_show_hours" data-live-search="true" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                    <option value="{{ \App\Item::ITEM_HOUR_SHOW }}" {{ (old('import_item_data_item_hour_show_hours') ? old('import_item_data_item_hour_show_hours') : $import_item_data->import_item_data_item_hour_show_hours) == \App\Item::ITEM_HOUR_SHOW ? 'selected' : '' }}>{{ __('item_hour.show-hour') }}</option>
                                    <option value="{{ \App\Item::ITEM_HOUR_NOT_SHOW }}" {{ (old('import_item_data_item_hour_show_hours') ? old('import_item_data_item_hour_show_hours') : $import_item_data->import_item_data_item_hour_show_hours) == \App\Item::ITEM_HOUR_NOT_SHOW ? 'selected' : '' }}>{{ __('item_hour.not-show-hour') }}</option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('item_hour.show-open-hour-help') }}
                                </small>
                                @error('import_item_data_item_hour_show_hours')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <label for="import_item_data_item_hours" class="text-black">{{ __('import_csv_hours.import-item-data-item-hours') }}</label>
                                <textarea class="form-control @error('import_item_data_item_hours') is-invalid @enderror" id="import_item_data_item_hours" rows="5" name="import_item_data_item_hours" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>{{ old('import_item_data_item_hours') ? old('import_item_data_item_hours') : $import_item_data->import_item_data_item_hours }}</textarea>
                                <small class="form-text text-muted">
                                    {{ __('import_csv_hours.import-item-data-item-hours-help') }}
                                </small>
                                @error('import_item_data_item_hours')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <label for="import_item_data_item_image_hour_exceptions" class="text-black">{{ __('import_csv_hours.import-item-data-item-image-hour-exceptions') }}</label>
                                <textarea class="form-control @error('import_item_data_item_image_hour_exceptions') is-invalid @enderror" id="import_item_data_item_image_hour_exceptions" rows="5" name="import_item_data_item_image_hour_exceptions" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>{{ old('import_item_data_item_image_hour_exceptions') ? old('import_item_data_item_image_hour_exceptions') : $import_item_data->import_item_data_item_image_hour_exceptions }}</textarea>
                                <small class="form-text text-muted">
                                    {{ __('import_csv_hours.import-item-data-item-image-hour-exceptions-help') }}
                                </small>
                                @error('import_item_data_item_image_hour_exceptions')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <label for="import_item_data_item_image" class="text-black">{{ __('backend.item.feature-image') }}</label>
                                <input id="import_item_data_item_image" type="text" class="form-control @error('import_item_data_item_image') is-invalid @enderror" name="import_item_data_item_image" value="{{ old('import_item_data_item_image') ? old('import_item_data_item_image') : $import_item_data->import_item_data_item_image }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                <small class="form-text text-muted">
                                    {{ __('sitemap_import.item-feature-image-url-help') }}
                                </small>
                                @error('import_item_data_item_image')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <label for="import_item_data_item_image_galleries" class="text-black">{{ __('backend.item.gallery-images') }}</label>
                                <textarea class="form-control @error('import_item_data_item_image_galleries') is-invalid @enderror" id="import_item_data_item_image_galleries" rows="5" name="import_item_data_item_image_galleries" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>{{ old('import_item_data_item_image_galleries') ? old('import_item_data_item_image_galleries') : $import_item_data->import_item_data_item_image_galleries }}</textarea>
                                <small class="form-text text-muted">
                                    {{ __('sitemap_import.item-gallery-images-url-help') }}
                                </small>
                                @error('import_item_data_item_image_galleries')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <span class="text-lg text-gray-800">{{ __('backend.item.custom-fields') }}</span>
                                <small class="form-text text-muted">
                                    {{ __('theme_directory_hub.importer.import-custom-field-help') }}
                                </small>
                            </div>
                        </div>

                        <div class="row form-group">
                            @foreach($all_import_item_feature_data as $all_import_item_feature_data_key => $an_import_item_feature_data)
                                @php
                                    $custom_field_exist = \App\CustomField::find($an_import_item_feature_data->import_item_feature_data_custom_field_id);
                                @endphp

                                @if($custom_field_exist)
                                    <div class="col-12 col-md-4 mb-3">
                                        <label for="custom_field_{{ $an_import_item_feature_data->id }}" class="text-black">{{ $custom_field_exist->custom_field_name }}</label>
                                        <input id="custom_field_{{ $an_import_item_feature_data->id }}" type="text" class="form-control @error('custom_field_' . $an_import_item_feature_data->id) is-invalid @enderror" name="custom_field_{{ $an_import_item_feature_data->id }}" value="{{ old('custom_field_' . $an_import_item_feature_data->id) ? old('custom_field_' . $an_import_item_feature_data->id) : $an_import_item_feature_data->import_item_feature_data_item_feature_value }}" {{ $import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS ? 'disabled' : '' }}>
                                        @error('custom_field_' . $an_import_item_feature_data->id)
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                @endif

                            @endforeach
                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">

                                @if($import_item_data->import_item_data_process_status == \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
                                    @if(!empty($imported_item))
                                        <a target="_blank" href="{{ route('admin.items.edit', ['item' => $imported_item]) }}">
                                            <i class="fas fa-external-link-alt"></i>
                                            {{ __('products.edit-item-link') }}
                                        </a>
                                    @endif
                                @else
                                    <button type="submit" class="btn btn-success text-white">
                                        {{ __('backend.shared.update') }}
                                    </button>
                                @endif

                            </div>
                            <div class="col-4 text-right">
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    {{ __('backend.shared.delete') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <hr>

            @if($import_item_data->import_item_data_process_status != \App\ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
                <div class="row border-left-info">
                    <div class="col-12">

                        <div class="row mb-2">
                            <div class="col-12">
                                <span class="text-gray-800 text-lg">{{ __('importer_csv.choose-import-listing-preference') }}:</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <form method="POST" action="{{ route('admin.importer.item.data.import', ['import_item_data' => $import_item_data]) }}" class="">
                                    @csrf
                                    <div class="row form-group">
                                        <div class="col-12">

                                            <span>{{ __('importer_csv.choose-import-listing-categories') }}:</span><br>

                                            @foreach($all_categories as $all_categories_key => $category)
                                                <div class="form-check form-check-inline">
                                                    <input name="category[]" class="form-check-input" type="checkbox" id="category_{{ $category['category_id'] }}" value="{{ $category['category_id'] }}" {{ in_array($category['category_id'], (old('category') ? old('category') : array()) ) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category_{{ $category['category_id'] }}">{{ $category['category_name'] }}</label>
                                                </div>
                                            @endforeach
                                            @error('category')
                                            <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12 col-md-3">
                                            <label for="user_id" class="text-black">{{ __('importer_csv.choose-import-listing-owner') }}</label>
                                            <select id="user_id" class="custom-select @error('user_id') is-invalid @enderror" name="user_id">

                                                <option value="{{ $admin_user->id }}" {{ old('user_id') == $admin_user->id ? 'selected' : '' }}>{{ __('products.myself') }}</option>
                                                <option value="{{ \App\ImportItemData::IMPORT_RANDOM_USER }}" {{ old('user_id') == \App\ImportItemData::IMPORT_RANDOM_USER ? 'selected' : '' }}>{{ __('theme_directory_hub.importer.random-user') }}</option>

                                                @foreach($all_users as $key => $user)
                                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name . ' (' . $user->email . ')' }}</option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="item_type" class="text-black">{{ __('theme_directory_hub.online-listing.listing-type') }}</label>
                                            <select id="item_type" class="custom-select @error('item_type') is-invalid @enderror" name="item_type">

                                                <option value="{{ \App\Item::ITEM_TYPE_REGULAR }}" {{ old('item_type') == \App\Item::ITEM_TYPE_REGULAR ? 'selected' : '' }}>{{ __('theme_directory_hub.online-listing.regular-listing') }}</option>
                                                <option value="{{ \App\Item::ITEM_TYPE_ONLINE }}" {{ old('item_type') == \App\Item::ITEM_TYPE_ONLINE ? 'selected' : '' }}>{{ __('theme_directory_hub.online-listing.online-listing') }}</option>

                                            </select>
                                            @error('item_type')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="item_status" class="text-black">{{ __('importer_csv.choose-import-listing-status') }}</label>
                                            <select id="item_status" class="custom-select @error('item_status') is-invalid @enderror" name="item_status">
                                                <option value="{{ \App\Item::ITEM_SUBMITTED }}" {{ old('item_status') == \App\Item::ITEM_SUBMITTED ? 'selected' : '' }}>{{ __('backend.item.submitted') }}</option>
                                                <option value="{{ \App\Item::ITEM_PUBLISHED }}" {{ old('item_status') == \App\Item::ITEM_PUBLISHED ? 'selected' : '' }}>{{ __('backend.item.published') }}</option>
                                                <option value="{{ \App\Item::ITEM_SUSPENDED }}" {{ old('item_status') == \App\Item::ITEM_SUSPENDED ? 'selected' : '' }}>{{ __('backend.item.suspended') }}</option>
                                            </select>
                                            @error('item_status')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label for="item_featured" class="text-black">{{ __('importer_csv.choose-import-listing-featured') }}</label>
                                            <select id="item_featured" class="custom-select @error('item_featured') is-invalid @enderror" name="item_featured">
                                                <option value="{{ \App\Item::ITEM_NOT_FEATURED }}" {{ old('item_featured') == \App\Item::ITEM_NOT_FEATURED ? 'selected' : '' }}>{{ __('backend.shared.no') }}</option>
                                                <option value="{{ \App\Item::ITEM_FEATURED }}" {{ old('item_featured') == \App\Item::ITEM_FEATURED ? 'selected' : '' }}>{{ __('backend.shared.yes') }}</option>
                                            </select>
                                            @error('item_featured')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-12 text-right">
                                            <button type="submit" class="btn btn-info text-white">
                                                {{ __('importer_csv.import-listing-button') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Modal -->
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
                    {{ __('backend.shared.delete-message', ['record_type' => __('importer_csv.import-listing-information'), 'record_name' => $import_item_data->import_item_data_item_title]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.importer.item.data.destroy', ['import_item_data' => $import_item_data->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- searchable selector -->
    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.admin.partials.bootstrap-select-locale')
@endsection
