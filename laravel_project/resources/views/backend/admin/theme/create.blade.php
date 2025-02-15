@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('theme_directory_hub.theme-create') }}</h1>
            <p class="mb-4">{{ __('theme_directory_hub.theme-create-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.themes.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-12 col-md-6">
                    <form method="POST" action="{{ route('admin.themes.store') }}" class="" enctype="multipart/form-data">
                        @csrf

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label class="text-black" for="theme_install_zip">{{ __('theme_directory_hub.theme-install-label') }}</label>
                                <input id="theme_install_zip" type="file" class="form-control @error('theme_install_zip') is-invalid @enderror" name="theme_install_zip">
                                <small class="form-text text-muted">
                                    {{ __('theme_directory_hub.theme-install-label-help') }}
                                </small>
                                @error('theme_install_zip')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success text-white">
                                    {{ __('theme_directory_hub.theme-install-button') }}
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
