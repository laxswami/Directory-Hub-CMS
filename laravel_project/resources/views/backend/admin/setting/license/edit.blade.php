@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('license_verify.license-setting') }}</h1>
            <p class="mb-4">
                {{ __('license_verify.license-setting-desc') }}
            </p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <p>
                            <strong>
                                <i class="far fa-question-circle"></i>
                                {{ __('importer_csv.csv-file-upload-listing-instruction') }}
                            </strong>
                        </p>
                        <ul>
                            <li>
                                {{ __('license_verify.license-instruction-guide') }}
                                <a href="https://alphastir.com/how-to-verify-purchase-code-domain/" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    {{ __('license_verify.license-terms-guide-link') }}
                                </a>
                            </li>
                            <li>
                                {{ __('license_verify.license-instruction-terms') }}
                                <a href="https://alphastir.com/directoryhub/license/" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    {{ __('license_verify.license-terms-link') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if($verify_response_status)
                        @if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_ERROR)
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $verify_response_body->status_message }}
                            </div>
                        @else
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle"></i>
                                {{ $verify_response_body->status_message }}
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $verify_response_message }}
                        </div>
                    @endif
                </div>
            </div>

            @if($verify_response_status)
                @if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS)
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-sm-12">



                        <div class="row mb-2">
                            <div class="col-12 text-gray-800">
                                {{ __('license_verify.license-information') }}
                            </div>
                        </div>

                        <div class="row p-2 bg-light">
                            <div class="col-6">
                                {{ __('license_verify.license-type') }}
                            </div>
                            <div class="col-6 text-right">
                                {{ $verify_response_body->license->license_type }}
                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-6">
                                {{ __('license_verify.license-supported-until') }}
                            </div>
                            <div class="col-6 text-right">
                                {{ $verify_response_body->license->supported_until }}
                            </div>
                        </div>

                        <div class="row p-2 bg-light">
                            <div class="col-6">
                                {{ __('license_verify.license-last-check') }}
                            </div>
                            <div class="col-6 text-right">
                                {{ date('Y-m-d', $verify_response_body->license->last_check_timestamp) }}
                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-6">
                                {{ __('license_verify.license-last-check-status') }}
                            </div>
                            <div class="col-6 text-right">
                                @if($verify_response_body->license->license_last_check_status == \App\SettingLicense::LICENSE_API_LICENSE_VALID)
                                    <span class="pl-2 pr-2 bg-success text-white rounded">
                                    {{ __('license_verify.license-valid') }}
                                    </span>
                                @else
                                    <span class="pl-2 pr-2 bg-danger text-white rounded">
                                    {{ __('license_verify.license-invalid') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row p-2 bg-light">
                            <div class="col-6">
                                {{ __('license_verify.license-domains') }}
                                (
                                {{ __('license_verify.up-to') . ' ' . $verify_response_body->license->domain_quota . ' ' . __('license_verify.up-to-domains') }}
                                )
                            </div>
                            <div class="col-6 text-right">
                                @php
                                    $license_domains = $verify_response_body->license->domains;
                                @endphp

                                @foreach($license_domains as $license_domains_key => $license_domain)
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            {{ $license_domain->domain_host }}
                                            |
                                            @if($license_domain->domain_status == \App\SettingLicense::LICENSE_API_DOMAIN_VERIFIED)
                                                <span class="pl-2 pr-2 bg-success text-white rounded">
                                                    {{ __('license_verify.domain-verified') }}
                                                </span>
                                            @else
                                                <span class="pl-2 pr-2 bg-warning text-white rounded">
                                                    {{ __('license_verify.domain-unverified') }}
                                                </span>
                                            @endif
                                            |
                                            <a class="text-danger" href="#" data-toggle="modal" data-target="#revokeDomainModal_{{ $license_domains_key }}">
                                                {{ __('license_verify.license-revoke-domain') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @endif
            @endif

            <div class="row border-left-primary mb-4">
                <div class="col-12">

                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                {{ __('license_verify.verify-step-1') }}
                            </span>
                            <small class="form-text text-white">
                                {{ __('license_verify.verify-step-1-desc') }}
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="{{ route('admin.settings.license.update') }}" class="">
                                @csrf

                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="settings_license_purchase_code" class="text-black">{{ __('installer_messages.environment.wizard.form.app_purchase_code_label') }}</label>
                                        <input id="settings_license_purchase_code" type="text" class="form-control @error('settings_license_purchase_code') is-invalid @enderror" name="settings_license_purchase_code" value="{{ is_demo_mode() ? '******-****-****-****-**********' : $settings->settingLicense->settings_license_purchase_code }}">
                                        <small class="form-text text-muted">
                                            {{ __('installer_messages.environment.wizard.form.app_purchase_code_placeholder') }}
                                        </small>
                                        @error('settings_license_purchase_code')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="settings_license_codecanyon_username" class="text-black">{{ __('installer_messages.environment.wizard.form.to_verify_codecanyon_username_label') }}</label>
                                        <input id="settings_license_codecanyon_username" type="text" class="form-control @error('settings_license_codecanyon_username') is-invalid @enderror" name="settings_license_codecanyon_username" value="{{ is_demo_mode() ? '********' : $settings->settingLicense->settings_license_codecanyon_username }}">
                                        <small class="form-text text-muted">
                                            {{ __('installer_messages.environment.wizard.form.to_verify_codecanyon_username_placeholder') }}
                                        </small>
                                        @error('settings_license_codecanyon_username')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-sm btn-success text-white">
                                            <i class="fas fa-shield-alt"></i>
                                            {{ __('license_verify.register-purchase-code') }}
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row border-left-primary mb-4">
                <div class="col-12">
                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                {{ __('license_verify.verify-step-2') }}
                            </span>
                            <small class="form-text text-white">
                                {{ __('license_verify.verify-step-2-desc') }}
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-sm btn-success text-white" target="_blank" href="{{ is_demo_mode() ? '' : get_domain_verify_token_url($settings->settingLicense->settings_license_purchase_code, $settings->settingLicense->settings_license_codecanyon_username) }}">
                                <i class="fas fa-external-link-alt"></i>
                                {{ __('license_verify.get-domain-verify-token') }}
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row border-left-primary mb-4">
                <div class="col-12">
                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                {{ __('license_verify.verify-step-3') }}
                            </span>
                            <small class="form-text text-white">
                                {{ __('license_verify.verify-step-3-desc') }}
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <form method="POST" action="{{ route('admin.settings.license.domain-verify-token.update') }}" class="">
                                @csrf

                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="settings_license_domain_verify_token" class="text-black">{{ __('license_verify.domain-verify-token') }}</label>
                                        <input id="settings_license_domain_verify_token" type="text" class="form-control @error('settings_license_domain_verify_token') is-invalid @enderror" name="settings_license_domain_verify_token" value="{{ is_demo_mode() ? '*************' : $settings->settingLicense->settings_license_domain_verify_token }}">
                                        @error('settings_license_domain_verify_token')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-sm btn-success text-white">
                                            <i class="far fa-save"></i>
                                            {{ __('license_verify.domain-verify-token-button') }}
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row border-left-primary mb-4">
                <div class="col-12">
                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                {{ __('license_verify.verify-step-4') }}
                            </span>
                            <small class="form-text text-white">
                                {{ __('license_verify.verify-step-4-desc') }}
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-sm btn-success text-white" target="_blank" href="{{ is_demo_mode() ? '' : get_domain_verify_do_url($settings->settingLicense->settings_license_purchase_code, $settings->settingLicense->settings_license_codecanyon_username) }}">
                                <i class="fas fa-external-link-alt"></i>
                                {{ __('license_verify.domain-verify-button') }}
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@if($verify_response_status && $verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS)
    @php
        $license_domains = $verify_response_body->license->domains;
    @endphp

    @foreach($license_domains as $license_domains_key => $license_domain)
        <div class="modal fade" id="revokeDomainModal_{{ $license_domains_key }}" tabindex="-1" role="dialog" aria-labelledby="revokeDomainModal_{{ $license_domains_key }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('license_verify.license-revoke-domain-modal-title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('license_verify.license-revoke-domain-modal-desc-1') }}</p>
                        <p>{{ __('license_verify.license-revoke-domain-modal-desc-2') }}</p>
                        <p>{{ __('license_verify.license-revoke-domain-modal-desc-3') }}</p>

                        <p>
                            <span class="pl-2 pr-2 bg-primary text-white rounded">
                            {{ $license_domain->domain_host }}
                            </span>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <form action="{{ route('admin.settings.license.revoke', ['domain_host' => $license_domain->domain_host]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">{{ __('license_verify.license-revoke-domain-modal-button-confirm') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection

@section('scripts')
@endsection
