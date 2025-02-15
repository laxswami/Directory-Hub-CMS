@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.social-media.edit-social-media') }}</h1>
            <p class="mb-4">{{ __('backend.social-media.edit-social-media-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.social-medias.index') }}" class="btn btn-info btn-icon-split">
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
                    <form method="POST" action="{{ route('admin.social-medias.update', $socialMedia->id) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="social_media_name" class="text-black">{{ __('backend.social-media.name') }}</label>
                                <input id="social_media_name" type="text" class="form-control @error('social_media_name') is-invalid @enderror" name="social_media_name" value="{{ old('social_media_name') ? old('social_media_name') : $socialMedia->social_media_name }}" autofocus>
                                @error('social_media_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="social_media_icon">{{ __('backend.social-media.icon') }}</label>
                                <input id="social_media_icon" type="text" class="form-control @error('social_media_icon') is-invalid @enderror" name="social_media_icon" value="{{ old('social_media_icon') ? old('social_media_icon') : $socialMedia->social_media_icon }}">
                                <small class="text-muted">
                                    {!! __('backend.category.category-icon-help') !!}
                                </small>
                                @error('social_media_icon')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="social_media_link">{{ __('backend.social-media.link') }}</label>
                                <input id="social_media_link" type="text" class="form-control @error('social_media_link') is-invalid @enderror" name="social_media_link" value="{{ old('social_media_link') ? old('social_media_link') : $socialMedia->social_media_link }}">
                                <small class="text-muted">
                                    {{ __('backend.shared.url-help') }}
                                </small>
                                @error('social_media_link')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="social_media_order">{{ __('backend.social-media.order') }}</label>
                                <input id="social_media_order" type="text" class="form-control @error('social_media_order') is-invalid @enderror" name="social_media_order" value="{{ old('social_media_order') ? old('social_media_order') : $socialMedia->social_media_order }}">
                                @error('social_media_order')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    Update
                                </button>
                            </div>
                            <div class="col-4 text-right">
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    Delete
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
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
                    {{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.social-media'), 'record_name' => $socialMedia->social_media_name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.social-medias.destroy', $socialMedia->id) }}" method="POST">
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
@endsection
