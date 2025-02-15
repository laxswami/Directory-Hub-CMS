@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('item_section.index') }}</h1>
            <p class="mb-4">{{ __('item_section.index-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.items.edit', ['item' => $item]) }}" class="btn btn-info btn-icon-split">
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

                    <a target="_blank" href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

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

                    @if($item_has_claimed)
                        <p>
                            <i class="fas fa-check-circle"></i>
                            {{ __('item_claim.item-claimed-by') . " " . $item_claimed_user->name }}
                        </p>
                    @else
                        <p>
                            <i class="fas fa-question-circle"></i>
                            {{ __('item_claim.unclaimed') . ", " . __('item_claim.item-posted-by') . " " . $item->user->name }}
                        </p>
                    @endif

                    <p>
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                        {{ $item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE ? $item->item_address . ', ' : '' }} {{ $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code }}
                        @else
                            <span class="bg-primary text-white pl-1 pr-1 rounded">{{ __('theme_directory_hub.online-listing.online-listing') }}</span>
                        @endif
                    </p>
                    <hr/>
                    <p>{{ $item->item_description }}</p>
                </div>
            </div>
            <hr>

            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.add-item-section') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.items.sections.store', ['item' => $item]) }}">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-4 col-sm-12">
                                <label for="item_section_title" class="text-black">{{ __('item_section.item-section-title') }}</label>
                                <input id="item_section_title" type="text" class="form-control @error('item_section_title') is-invalid @enderror" name="item_section_title" value="{{ old('item_section_title') }}">
                                @error('item_section_title')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label for="item_section_position" class="text-black">{{ __('item_section.item-section-position') }}</label>
                                <select class="custom-select" name="item_section_position">
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_BREADCRUMB }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_BREADCRUMB ? 'selected' : '' }}>{{ __('item_section.position-after-breadcrumb') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_GALLERY }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_GALLERY ? 'selected' : '' }}>{{ __('item_section.position-after-gallery') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_DESCRIPTION }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_DESCRIPTION ? 'selected' : '' }}>{{ __('item_section.position-after-description') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_LOCATION_MAP }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_LOCATION_MAP ? 'selected' : '' }}>{{ __('item_section.position-after-location-map') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_FEATURES }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_FEATURES ? 'selected' : '' }}>{{ __('item_section.position-after-features') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_REVIEWS }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_REVIEWS ? 'selected' : '' }}>{{ __('item_section.position-after-reviews') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_COMMENTS }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_COMMENTS ? 'selected' : '' }}>{{ __('item_section.position-after-comments') }}</option>
                                    <option value="{{ \App\ItemSection::POSITION_AFTER_SHARE }}" {{ old('item_section_position') == \App\ItemSection::POSITION_AFTER_SHARE ? 'selected' : '' }}>{{ __('item_section.position-after-share') }}</option>
                                </select>
                                @error('item_section_position')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label for="item_section_status" class="text-black">{{ __('item_section.item-section-status') }}</label>
                                <select class="custom-select" name="item_section_status">
                                    <option value="{{ \App\ItemSection::STATUS_DRAFT }}" {{ old('item_section_status') == \App\ItemSection::STATUS_DRAFT ? 'selected' : '' }}>{{ __('item_section.item-section-status-draft') }}</option>
                                    <option value="{{ \App\ItemSection::STATUS_PUBLISHED }}" {{ old('item_section_status') == \App\ItemSection::STATUS_PUBLISHED ? 'selected' : '' }}>{{ __('item_section.item-section-status-published') }}</option>
                                </select>
                                @error('item_section_status')
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
            <hr>

            <!-- Start after breadcrumb sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-breadcrumb') }}</span>
                </div>
            </div>
            @if($item_sections_after_breadcrumb->count() > 0)

                @foreach($item_sections_after_breadcrumb as $item_sections_after_breadcrumb_key => $after_breadcrumb_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_sections_after_breadcrumb_key%2 == 0 ? 'bg-light' : '' }} {{ $after_breadcrumb_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_breadcrumb_section->item_section_title }}</span>
                            |
                            @if($after_breadcrumb_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_breadcrumb_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_breadcrumb_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_breadcrumb_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_breadcrumb_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_breadcrumb_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_breadcrumb_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_breadcrumb_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_breadcrumb_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_breadcrumb_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_breadcrumb_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after breadcrumb sections -->

            <!-- Start after gallery sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-gallery') }}</span>
                </div>
            </div>
            @if($item_section_after_gallery->count() > 0)

                @foreach($item_section_after_gallery as $item_section_after_gallery_key => $after_gallery_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_section_after_gallery_key%2 == 0 ? 'bg-light' : '' }} {{ $after_gallery_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_gallery_section->item_section_title }}</span>
                            |
                            @if($after_gallery_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_gallery_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_gallery_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_gallery_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_gallery_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_gallery_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_gallery_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_gallery_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_gallery_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_gallery_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_gallery_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after gallery sections -->


            <!-- Start after description sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-description') }}</span>
                </div>
            </div>
            @if($item_section_after_description->count() > 0)

                @foreach($item_section_after_description as $item_section_after_description_key => $after_description_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_section_after_description_key%2 == 0 ? 'bg-light' : '' }} {{ $after_description_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_description_section->item_section_title }}</span>
                            |
                            @if($after_description_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_description_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_description_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_description_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_description_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_description_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_description_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_description_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_description_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_description_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_description_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after description sections -->


            <!-- Start after location map sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-location-map') }}</span>
                </div>
            </div>
            @if($item_section_after_location_map->count() > 0)

                @foreach($item_section_after_location_map as $item_section_after_location_map_key => $after_location_map_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_section_after_location_map_key%2 == 0 ? 'bg-light' : '' }} {{ $after_location_map_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_location_map_section->item_section_title }}</span>
                            |
                            @if($after_location_map_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_location_map_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_location_map_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_location_map_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_location_map_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_location_map_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_location_map_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_location_map_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_location_map_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_location_map_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_location_map_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after location map sections -->


            <!-- Start after features sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-features') }}</span>
                </div>
            </div>
            @if($item_section_after_features->count() > 0)

                @foreach($item_section_after_features as $item_section_after_features_key => $after_features_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_section_after_features_key%2 == 0 ? 'bg-light' : '' }} {{ $after_features_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_features_section->item_section_title }}</span>
                            |
                            @if($after_features_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_features_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_features_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_features_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_features_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_features_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_features_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_features_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_features_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_features_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_features_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after features sections -->


            <!-- Start after reviews sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-reviews') }}</span>
                </div>
            </div>
            @if($item_section_after_reviews->count() > 0)

                @foreach($item_section_after_reviews as $item_section_after_reviews_key => $after_reviews_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_section_after_reviews_key%2 == 0 ? 'bg-light' : '' }} {{ $after_reviews_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_reviews_section->item_section_title }}</span>
                            |
                            @if($after_reviews_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_reviews_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_reviews_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_reviews_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_reviews_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_reviews_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_reviews_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_reviews_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_reviews_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_reviews_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_reviews_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after reviews sections -->


            <!-- Start after comments sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-comments') }}</span>
                </div>
            </div>
            @if($item_section_after_comments->count() > 0)

                @foreach($item_section_after_comments as $item_section_after_comments_key => $after_comments_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_section_after_comments_key%2 == 0 ? 'bg-light' : '' }} {{ $after_comments_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_comments_section->item_section_title }}</span>
                            |
                            @if($after_comments_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_comments_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_comments_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_comments_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_comments_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_comments_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_comments_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_comments_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_comments_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_comments_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_comments_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after comments sections -->

            <!-- Start after share sections -->
            <div class="row mb-2">
                <div class="col-12">
                    <span class="text-gray-800">{{ __('item_section.position-after-share') }}</span>
                </div>
            </div>
            @if($item_section_after_share->count() > 0)

                @foreach($item_section_after_share as $item_section_after_share_key => $after_share_section)
                    <div class="row align-items-center pt-2 mb-2 {{ $item_section_after_share_key%2 == 0 ? 'bg-light' : '' }} {{ $after_share_section->item_section_status == \App\ItemSection::STATUS_DRAFT ? 'border-left-warning' : 'border-left-success' }}">
                        <div class="col-lg-8 col-md-7 col-sm-12">
                            <span class="text-gray-800">{{ $after_share_section->item_section_title }}</span>
                            |
                            @if($after_share_section->item_section_status == \App\ItemSection::STATUS_DRAFT)
                                <span class="bg-warning pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-draft') }}
                                </span>
                            @elseif($after_share_section->item_section_status == \App\ItemSection::STATUS_PUBLISHED)
                                <span class="bg-success pl-1 text-white text-sm rounded mr-1">
                                    {{ __('item_section.item-section-status-published') }}
                                </span>
                            @endif
                            |
                            <a class="ml-1" href="{{ route('admin.items.sections.edit', ['item' => $item, 'item_section' => $after_share_section->id]) }}">
                                <i class="fas fa-edit"></i>
                                {{ __('item_section.edit-section-link') }}
                            </a>
                            <ul>
                                <li>
                                    {{ $after_share_section->itemSectionCollections()->count() . ' ' . __('item_section.collections') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-12">
                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.up', ['item' => $item, 'item_section' => $after_share_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-up"></i>
                                </button>
                            </form>

                            <form class="float-left pr-1" action="{{ route('admin.items.sections.rank.down', ['item' => $item, 'item_section' => $after_share_section->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-arrow-down"></i>
                                </button>
                            </form>

                            <a class="btn btn-danger btn-sm text-white" href="#" data-toggle="modal" data-target="#item_section_delete_{{ $after_share_section->id }}">
                                <i class="far fa-trash-alt"></i>
                            </a>

                        </div>
                    </div>

                    <!-- Modal - product feature delete -->
                    <div class="modal fade" id="item_section_delete_{{ $after_share_section->id }}" tabindex="-1" role="dialog" aria-labelledby="item_section_delete_{{ $after_share_section->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    {{ __('backend.shared.delete-message', ['record_type' => __('item_section.item-section'), 'record_name' => $after_share_section->item_section_title]) }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                    <form action="{{ route('admin.items.sections.destroy', ['item' => $item, 'item_section' => $after_share_section->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="row">
                    <div class="col-12">
                        <span>{{ __('item_section.position-no-sections') }}</span>
                    </div>
                </div>
            @endif
            <hr>
            <!-- End after share sections -->


        </div>
    </div>

@endsection

@section('scripts')
@endsection
