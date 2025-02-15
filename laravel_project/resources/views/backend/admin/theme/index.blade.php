@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('theme_directory_hub.theme-index') }}</h1>
            <p class="mb-4">{{ __('theme_directory_hub.theme-index-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.themes.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('theme_directory_hub.theme-install') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">

                @foreach($all_themes as $all_themes_key => $theme)
                    <div class="col-12 col-sm-5 col-md-4 col-lg-3 border pl-0 pr-0 mr-3">

                        <div class="row">
                            <div class="col-12">
                                @if($theme->theme_system_default == \App\Theme::THEME_SYSTEM_DEFAULT_YES)
                                    <img src="{{ asset('frontend/images/placeholder/' . $theme->theme_preview_image) }}" alt="Image" class="img-fluid border">
                                @else
                                    @if(empty($theme->theme_preview_image))
                                        <img src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" alt="Image" class="img-fluid border">
                                    @else
                                        <img src="{{ asset(\App\Theme::THEME_ASSETS . '/' . \App\Theme::THEME_ASSETS_FRONTEND . '/' . $theme->theme_identifier . '/placeholder/' . $theme->theme_preview_image) }}" alt="Image" class="img-fluid border">
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="row mt-2 mb-2 pl-2 pr-2">
                            <div class="col-12">
                                @if($theme->theme_status == \App\Theme::THEME_STATUS_ACTIVE)
                                    <span class="text-success">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @endif
                                <span class="text-gray-800">{{ $theme->theme_name }}</span>

                                @if($theme->theme_system_default == \App\Theme::THEME_SYSTEM_DEFAULT_YES)
                                    <span class="bg-dark text-white ml-1 pl-2 pr-2">{{ __('theme_directory_hub.theme-system-default') }}</span>
                                @endif

                            </div>
                        </div>

                        <div class="row mt-2 mb-2 pl-2 pr-2">
                            <div class="col-12">
                                <a class="btn btn-info btn-sm btn-block text-white" href="#" data-toggle="modal" data-target="#themeDetailModal_{{ $theme->id  }}">
                                    <i class="far fa-window-restore"></i>
                                    {{ __('theme_directory_hub.theme-detail') }}
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>

        </div>
    </div>

    @foreach($all_themes as $all_themes_key => $theme)
        <div class="modal fade" id="themeDetailModal_{{ $theme->id  }}" tabindex="-1" role="dialog" aria-labelledby="themeDetailModal_{{ $theme->id  }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('theme_directory_hub.theme-detail') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-6">
                                @if($theme->theme_system_default == \App\Theme::THEME_SYSTEM_DEFAULT_YES)
                                    <img src="{{ asset('frontend/images/placeholder/' . $theme->theme_preview_image) }}" alt="Image" class="img-fluid border">
                                @else
                                    @if(empty($theme->theme_preview_image))
                                        <img src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" alt="Image" class="img-fluid border">
                                    @else
                                        <img src="{{ asset(\App\Theme::THEME_ASSETS . '/' . \App\Theme::THEME_ASSETS_FRONTEND . '/' . $theme->theme_identifier . '/placeholder/' . $theme->theme_preview_image) }}" alt="Image" class="img-fluid border">
                                    @endif
                                @endif
                            </div>

                            <div class="col-6">
                                @if($theme->theme_status == \App\Theme::THEME_STATUS_ACTIVE)
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <span class="bg-success pl-2 pr-2 text-white">{{ __('theme_directory_hub.theme-current') }}</span>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-12">
                                        <span class="text-gray-800">{{ $theme->theme_name }}</span>

                                        @if($theme->theme_system_default == \App\Theme::THEME_SYSTEM_DEFAULT_YES)
                                            <span class="bg-dark text-white ml-1 pl-2 pr-2">{{ __('theme_directory_hub.theme-system-default') }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($theme->theme_author))
                                    <div class="row">
                                        <div class="col-12">
                                            <span>{{ __('theme_directory_hub.theme-by-author') . ' ' . $theme->theme_author }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mt-2">
                                    <div class="col-12">
                                        <p>{{ $theme->theme_description }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">

                        @if($theme->theme_status == \App\Theme::THEME_STATUS_INACTIVE)
                            <form action="{{ route('admin.themes.active', ['theme' => $theme]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success">{{ __('theme_directory_hub.theme-active') }}</button>
                            </form>
                        @endif

                        <a class="btn btn-primary" href="{{ route('admin.themes.customization.color.edit', ['theme' => $theme]) }}">{{ __('theme_directory_hub.theme-edit-colors') }}</a>

                        <a class="btn btn-primary" href="{{ route('admin.themes.customization.header.edit', ['theme' => $theme]) }}">{{ __('theme_directory_hub.theme-edit-headers') }}</a>

                        @if($theme->theme_status == \App\Theme::THEME_STATUS_INACTIVE && $theme->theme_system_default == \App\Theme::THEME_SYSTEM_DEFAULT_NO)
                            <form action="{{ route('admin.themes.destroy', ['theme' => $theme]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return deleteConfirm()">{{ __('backend.shared.delete') }}</button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('scripts')
    <script>
        function deleteConfirm()
        {
            return confirm("{{ __('theme_directory_hub.alert.theme-delete-confirm') }}");
        }
    </script>
@endsection
