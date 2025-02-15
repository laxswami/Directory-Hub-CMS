@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('theme_directory_hub.setting.item-setting') }}</h1>
            <p class="mb-4">{{ __('theme_directory_hub.setting.item-setting-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <form method="POST" action="{{ route('admin.settings.item.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label for="setting_item_auto_approval_enable" class="text-black">{{ __('theme_directory_hub.setting.item-setting-auto-approval') }}</label>
                                <select id="setting_item_auto_approval_enable" class="custom-select @error('setting_item_auto_approval_enable') is-invalid @enderror" name="setting_item_auto_approval_enable">
                                    <option value="{{ \App\SettingItem::SITE_ITEM_AUTO_APPROVAL_DISABLED }}" {{ $settings->settingItem->setting_item_auto_approval_enable == \App\SettingItem::SITE_ITEM_AUTO_APPROVAL_DISABLED ? 'selected' : '' }}>{{ __('products.disabled') }}</option>
                                    <option value="{{ \App\SettingItem::SITE_ITEM_AUTO_APPROVAL_ENABLED }}" {{ $settings->settingItem->setting_item_auto_approval_enable == \App\SettingItem::SITE_ITEM_AUTO_APPROVAL_ENABLED ? 'selected' : '' }}>{{ __('products.enabled') }}</option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('theme_directory_hub.setting.item-setting-auto-approval-help') }}
                                </small>
                                @error('setting_item_auto_approval_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="setting_item_max_gallery_photos" class="text-black">{{ __('theme_directory_hub.setting.item-setting-max-gallery-photos') }}</label>
                                <input id="setting_item_max_gallery_photos" type="number" class="form-control @error('setting_item_max_gallery_photos') is-invalid @enderror" name="setting_item_max_gallery_photos" value="{{ $settings->settingItem->setting_item_max_gallery_photos }}">
                                <small class="form-text text-muted">
                                    {{ __('theme_directory_hub.setting.item-setting-max-gallery-photos-help') }}
                                </small>
                                @error('setting_item_max_gallery_photos')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>



                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
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
