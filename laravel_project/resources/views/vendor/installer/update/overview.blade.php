@extends('vendor.installer.layouts.master-update')

@section('title', trans('installer_messages.updater.welcome.title'))
@section('container')
    <p class="paragraph text-center">
        {{ trans_choice('installer_messages.updater.overview.message', $numberOfUpdatesPending, ['number' => $numberOfUpdatesPending]) }}
    </p>

    <div class="row">
        <div class="col-12">
            <form method="post" action="{{ route('LaravelUpdater::database') }}" class="tabs-wrap">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <div class="alert alert-info" role="alert">
                        {{ trans('installer_messages.environment.wizard.form.update_message') }}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('app_purchase_code') ? ' has-error ' : '' }}">
                    <label for="app_purchase_code">
                        {{ trans('installer_messages.environment.wizard.form.app_purchase_code_label') }}
                    </label>
                    <input type="text" name="app_purchase_code" id="app_purchase_code" value="{{ $settings_license_purchase_code }}" placeholder="{{ trans('installer_messages.environment.wizard.form.app_purchase_code_placeholder') }}" />
                    @if ($errors->has('app_purchase_code'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_purchase_code') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('to_verify_codecanyon_username') ? ' has-error ' : '' }}">
                    <label for="to_verify_codecanyon_username">
                        {{ trans('installer_messages.environment.wizard.form.to_verify_codecanyon_username_label') }}
                    </label>
                    <input type="text" name="to_verify_codecanyon_username" id="to_verify_codecanyon_username" value="{{ $settings_license_codecanyon_username }}" placeholder="{{ trans('installer_messages.environment.wizard.form.to_verify_codecanyon_username_placeholder') }}" />
                    @if ($errors->has('to_verify_codecanyon_username'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('to_verify_codecanyon_username') }}
                        </span>
                    @endif
                </div>

                <div class="form-group text-center">
                    <button class="button" type="submit">
                        {{ trans('installer_messages.updater.overview.install_updates') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
@stop
