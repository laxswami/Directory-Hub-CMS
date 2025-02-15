@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('social_login.edit-login') }}</h1>
            <p class="mb-4">{{ __('social_login.edit-login-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.social-logins.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row mb-4">
                <div class="col-12">

                    <span class="text-lg text-gray-800">{{ $social_login->social_login_provider_name }}</span>

                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">

                    <span>{{ __('social_login_callback.oauth-redirect-uri') }}:</span>

                    @if($social_login->social_login_provider_name == \App\SocialLogin::SOCIAL_LOGIN_FACEBOOK)
                        <span>{{ route('auth.login.facebook.callback') }}</span>
                    @elseif($social_login->social_login_provider_name == \App\SocialLogin::SOCIAL_LOGIN_TWITTER)
                        <span>{{ route('auth.login.twitter.callback') }}</span>
                    @elseif($social_login->social_login_provider_name == \App\SocialLogin::SOCIAL_LOGIN_GOOGLE)
                        <span>{{ route('auth.login.google.callback') }}</span>
                    @elseif($social_login->social_login_provider_name == \App\SocialLogin::SOCIAL_LOGIN_LINKEDIN)
                        <span>{{ route('auth.login.linkedin.callback') }}</span>
                    @elseif($social_login->social_login_provider_name == \App\SocialLogin::SOCIAL_LOGIN_GITHUB)
                        <span>{{ route('auth.login.github.callback') }}</span>
                    @endif

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-10 col-lg-6">
                    <form method="POST" action="{{ route('admin.social-logins.update', $social_login) }}">
                        @csrf
                        @method('PUT')

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="social_login_provider_client_id" class="text-black">{{ __('social_login.login-client-id') }}</label>
                                <input id="social_login_provider_client_id" type="text" class="form-control @error('social_login_provider_client_id') is-invalid @enderror" name="social_login_provider_client_id" value="{{ old('social_login_provider_client_id') ? old('social_login_provider_client_id') : $social_login->social_login_provider_client_id }}" autofocus>
                                @error('social_login_provider_client_id')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="social_login_provider_client_secret" class="text-black">{{ __('social_login.login-client-secret') }}</label>
                                <input id="social_login_provider_client_secret" type="text" class="form-control @error('social_login_provider_client_secret') is-invalid @enderror" name="social_login_provider_client_secret" value="{{ old('social_login_provider_client_secret') ? old('social_login_provider_client_secret') : $social_login->social_login_provider_client_secret }}">
                                @error('social_login_provider_client_secret')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-6">
                                <label class="text-black" for="social_login_enabled">{{ __('social_login.login-status') }}</label>

                                <select class="custom-select @error('social_login_enabled') is-invalid @enderror" name="social_login_enabled">
                                    <option value="{{ \App\SocialLogin::SOCIAL_LOGIN_ENABLED }}" {{ (old('social_login_enabled') ? old('social_login_enabled') : $social_login->social_login_enabled) == \App\SocialLogin::SOCIAL_LOGIN_ENABLED ? 'selected' : '' }}>{{ __('social_login.login-enabled') }}</option>
                                    <option value="{{ \App\SocialLogin::SOCIAL_LOGIN_DISABLED }}" {{ (old('social_login_enabled') ? old('social_login_enabled') : $social_login->social_login_enabled) == \App\SocialLogin::SOCIAL_LOGIN_DISABLED ? 'selected' : '' }}>{{ __('social_login.login-disabled') }}</option>
                                </select>
                                @error('social_login_enabled')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('social_login.update-login') }}
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
