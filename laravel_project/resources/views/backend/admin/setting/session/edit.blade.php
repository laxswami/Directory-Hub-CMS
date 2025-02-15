@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('setting_session.edit') }}</h1>
            <p class="mb-4">{{ __('setting_session.edit-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <form method="POST" action="{{ route('admin.settings.session.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <p>
                                        <strong>
                                            <i class="far fa-question-circle"></i>
                                            {{ __('importer_csv.csv-file-upload-listing-instruction') }}
                                        </strong>
                                    </p>
                                    <p>
                                        {{ __('setting_session.session-intro-desc') }}
                                        <ul>
                                            <li>{{ __('setting_session.session-file-help') }}</li>
                                            <li>{{ __('setting_session.session-cookie-help') }}</li>
                                            <li>{{ __('setting_session.session-database-help') }}</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row form-group">
                            <div class="col-12 col-md-3">
                                {{ __('setting_session.session-driver') }}
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-check">
                                    <input {{ (old('setting_session') ? old('setting_session') : $setting_session) == 'file' ? 'checked' : '' }} class="form-check-input" type="radio" name="setting_session" id="setting_session_file" value="file" aria-describedby="setting_session_fileHelpBlock">
                                    <label class="form-check-label" for="setting_session_file">
                                        {{ __('setting_session.session-file') }}
                                    </label>
                                    <small id="setting_session_fileHelpBlock" class="form-text text-muted">
                                    </small>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-check">
                                    <input {{ (old('setting_session') ? old('setting_session') : $setting_session) == 'cookie' ? 'checked' : '' }} class="form-check-input" type="radio" name="setting_session" id="setting_session_cookie" value="cookie" aria-describedby="setting_session_cookieHelpBlock">
                                    <label class="form-check-label" for="setting_session_cookie">
                                        {{ __('setting_session.session-cookie') }}
                                    </label>
                                    <small id="setting_session_cookieHelpBlock" class="form-text text-muted">
                                    </small>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-check">
                                    <input {{ (old('setting_session') ? old('setting_session') : $setting_session) == 'database' ? 'checked' : '' }} class="form-check-input" type="radio" name="setting_session" id="setting_session_database" value="database" aria-describedby="setting_session_databaseHelpBlock">
                                    <label class="form-check-label" for="setting_session_database">
                                        {{ __('setting_session.session-database') }}
                                    </label>
                                    <small id="setting_session_databaseHelpBlock" class="form-text text-muted">
                                    </small>
                                </div>
                            </div>
                        </div>



                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                                <small class="form-text text-muted">
                                    {{ __('setting_session.update-help') }}
                                </small>
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
