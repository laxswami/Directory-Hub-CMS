@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.state.edit-state') }}</h1>
            <p class="mb-4">{{ __('backend.state.edit-state-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.states.index') }}" class="btn btn-info btn-icon-split">
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
                    <form method="POST" action="{{ route('admin.states.update', $state) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                Country: {{ $state->country->country_name }}
                                <input id="country_id" type="hidden" class="form-control" name="country_id" value="{{ $state->country->id }}">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="state_name" class="text-black">{{ __('backend.state.state-name') }}</label>
                                <input id="state_name" type="text" class="form-control @error('state_name') is-invalid @enderror" name="state_name" value="{{ old('state_name') ? old('state_name') : $state->state_name }}" autofocus>
                                @error('state_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="state_abbr">{{ __('backend.state.state-abbr') }}</label>
                                <input id="state_abbr" type="text" class="form-control @error('state_abbr') is-invalid @enderror" name="state_abbr" value="{{ old('state_abbr') ? old('state_abbr') : $state->state_abbr }}">
                                @error('state_abbr')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="state_slug">{{ __('setting_language.location.url-slug') }}</label>
                                <input id="state_slug" type="text" class="form-control @error('state_slug') is-invalid @enderror" name="state_slug" value="{{ old('state_slug') ? old('state_slug') : $state->state_slug }}">
                                <small class="form-text text-muted">
                                    {{ __('setting_language.location.url-slug-help') }}
                                </small>
                                @error('state_slug')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success text-white">
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
                    <p>{{ __('country_delete.delete-state-warning') }}</p>
                    <p>{{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.state'), 'record_name' => $state->state_name]) }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.states.destroy', $state) }}" method="POST">
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
