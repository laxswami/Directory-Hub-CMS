@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('stripe.edit-stripe-setting') }}</h1>
            <p class="mb-4">{{ __('stripe.edit-stripe-setting-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.payment.stripe.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-12">
                                @if($all_stripe_settings->setting_site_stripe_enable == \App\Setting::SITE_PAYMENT_STRIPE_ENABLE)
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-success text-white rounded">{{ __('stripe.stripe-enabled') }}</span>
                                @else
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-warning text-white rounded">{{ __('stripe.stripe-disabled') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <span>{{ __('stripe.stripe-webhook') }}: {{ route('user.stripe.notify') }}</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <span>{{ __('stripe.stripe-webhook-events') }}: {{ __('stripe.stripe-webhook-event-code') }}</span>
                            </div>
                        </div>
                        <hr>

                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_PAYMENT_STRIPE_ENABLE }}" name="setting_site_stripe_enable" type="checkbox" class="custom-control-input" id="setting_site_stripe_enable" {{ (old('setting_site_stripe_enable') ? old('setting_site_stripe_enable') : $all_stripe_settings->setting_site_stripe_enable) == \App\Setting::SITE_PAYMENT_STRIPE_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_stripe_enable">{{ __('stripe.enable-stripe') }}</label>
                                </div>
                                @error('setting_site_stripe_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_stripe_publishable_key">{{ __('stripe.stripe-publishable-key') }}</label>
                                <input id="setting_site_stripe_publishable_key" type="text" class="form-control @error('setting_site_stripe_publishable_key') is-invalid @enderror" name="setting_site_stripe_publishable_key" value="{{ old('setting_site_stripe_publishable_key') ? old('setting_site_stripe_publishable_key') : $all_stripe_settings->setting_site_stripe_publishable_key }}">
                                @error('setting_site_stripe_publishable_key')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_stripe_secret_key">{{ __('stripe.stripe-secret-key') }}</label>
                                <input id="setting_site_stripe_secret_key" type="text" class="form-control @error('setting_site_stripe_secret_key') is-invalid @enderror" name="setting_site_stripe_secret_key" value="{{ old('setting_site_stripe_secret_key') ? old('setting_site_stripe_secret_key') : $all_stripe_settings->setting_site_stripe_secret_key }}">
                                @error('setting_site_stripe_secret_key')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-6 col-sm-12">

                                <label class="text-black" for="setting_site_stripe_webhook_signing_secret">{{ __('stripe.stripe-webhook-signing-secret') }}</label>
                                <input id="setting_site_stripe_webhook_signing_secret" type="text" class="form-control @error('setting_site_stripe_webhook_signing_secret') is-invalid @enderror" name="setting_site_stripe_webhook_signing_secret" value="{{ old('setting_site_stripe_webhook_signing_secret') ? old('setting_site_stripe_webhook_signing_secret') : $all_stripe_settings->setting_site_stripe_webhook_signing_secret }}">
                                @error('setting_site_stripe_webhook_signing_secret')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label class="text-black" for="setting_site_stripe_currency">{{ __('backend.setting.currency') }}</label>
                                <select class="custom-select @error('setting_site_stripe_currency') is-invalid @enderror" name="setting_site_stripe_currency">
                                    <option value="usd" {{ $all_stripe_settings->setting_site_stripe_currency == 'usd' ? 'selected' : '' }}>usd</option>
                                    <option value="aed" {{ $all_stripe_settings->setting_site_stripe_currency == 'aed' ? 'selected' : '' }}>aed</option>
                                    <option value="afn" {{ $all_stripe_settings->setting_site_stripe_currency == 'afn' ? 'selected' : '' }}>afn</option>
                                    <option value="all" {{ $all_stripe_settings->setting_site_stripe_currency == 'all' ? 'selected' : '' }}>all</option>
                                    <option value="amd" {{ $all_stripe_settings->setting_site_stripe_currency == 'amd' ? 'selected' : '' }}>amd</option>
                                    <option value="ang" {{ $all_stripe_settings->setting_site_stripe_currency == 'ang' ? 'selected' : '' }}>ang</option>
                                    <option value="aoa" {{ $all_stripe_settings->setting_site_stripe_currency == 'aoa' ? 'selected' : '' }}>aoa</option>
                                    <option value="ars" {{ $all_stripe_settings->setting_site_stripe_currency == 'ars' ? 'selected' : '' }}>ars</option>
                                    <option value="aud" {{ $all_stripe_settings->setting_site_stripe_currency == 'aud' ? 'selected' : '' }}>aud</option>
                                    <option value="awg" {{ $all_stripe_settings->setting_site_stripe_currency == 'awg' ? 'selected' : '' }}>awg</option>
                                    <option value="azn" {{ $all_stripe_settings->setting_site_stripe_currency == 'azn' ? 'selected' : '' }}>azn</option>
                                    <option value="bam" {{ $all_stripe_settings->setting_site_stripe_currency == 'bam' ? 'selected' : '' }}>bam</option>
                                    <option value="bbd" {{ $all_stripe_settings->setting_site_stripe_currency == 'bbd' ? 'selected' : '' }}>bbd</option>
                                    <option value="bdt" {{ $all_stripe_settings->setting_site_stripe_currency == 'bdt' ? 'selected' : '' }}>bdt</option>
                                    <option value="bgn" {{ $all_stripe_settings->setting_site_stripe_currency == 'bgn' ? 'selected' : '' }}>bgn</option>
                                    <option value="bif" {{ $all_stripe_settings->setting_site_stripe_currency == 'bif' ? 'selected' : '' }}>bif</option>
                                    <option value="bmd" {{ $all_stripe_settings->setting_site_stripe_currency == 'bmd' ? 'selected' : '' }}>bmd</option>
                                    <option value="bnd" {{ $all_stripe_settings->setting_site_stripe_currency == 'bnd' ? 'selected' : '' }}>bnd</option>
                                    <option value="bob" {{ $all_stripe_settings->setting_site_stripe_currency == 'bob' ? 'selected' : '' }}>bob</option>
                                    <option value="brl" {{ $all_stripe_settings->setting_site_stripe_currency == 'brl' ? 'selected' : '' }}>brl</option>
                                    <option value="bsd" {{ $all_stripe_settings->setting_site_stripe_currency == 'bsd' ? 'selected' : '' }}>bsd</option>
                                    <option value="bwp" {{ $all_stripe_settings->setting_site_stripe_currency == 'bwp' ? 'selected' : '' }}>bwp</option>
                                    <option value="bzd" {{ $all_stripe_settings->setting_site_stripe_currency == 'bzd' ? 'selected' : '' }}>bzd</option>
                                    <option value="cad" {{ $all_stripe_settings->setting_site_stripe_currency == 'cad' ? 'selected' : '' }}>cad</option>
                                    <option value="cdf" {{ $all_stripe_settings->setting_site_stripe_currency == 'cdf' ? 'selected' : '' }}>cdf</option>
                                    <option value="chf" {{ $all_stripe_settings->setting_site_stripe_currency == 'chf' ? 'selected' : '' }}>chf</option>
                                    <option value="clp" {{ $all_stripe_settings->setting_site_stripe_currency == 'clp' ? 'selected' : '' }}>clp</option>
                                    <option value="cny" {{ $all_stripe_settings->setting_site_stripe_currency == 'cny' ? 'selected' : '' }}>cny</option>
                                    <option value="cop" {{ $all_stripe_settings->setting_site_stripe_currency == 'cop' ? 'selected' : '' }}>cop</option>
                                    <option value="crc" {{ $all_stripe_settings->setting_site_stripe_currency == 'crc' ? 'selected' : '' }}>crc</option>
                                    <option value="cve" {{ $all_stripe_settings->setting_site_stripe_currency == 'cve' ? 'selected' : '' }}>cve</option>
                                    <option value="czk" {{ $all_stripe_settings->setting_site_stripe_currency == 'czk' ? 'selected' : '' }}>czk</option>
                                    <option value="djf" {{ $all_stripe_settings->setting_site_stripe_currency == 'djf' ? 'selected' : '' }}>djf</option>
                                    <option value="dkk" {{ $all_stripe_settings->setting_site_stripe_currency == 'dkk' ? 'selected' : '' }}>dkk</option>
                                    <option value="dop" {{ $all_stripe_settings->setting_site_stripe_currency == 'dop' ? 'selected' : '' }}>dop</option>
                                    <option value="dzd" {{ $all_stripe_settings->setting_site_stripe_currency == 'dzd' ? 'selected' : '' }}>dzd</option>
                                    <option value="egp" {{ $all_stripe_settings->setting_site_stripe_currency == 'egp' ? 'selected' : '' }}>egp</option>
                                    <option value="etb" {{ $all_stripe_settings->setting_site_stripe_currency == 'etb' ? 'selected' : '' }}>etb</option>
                                    <option value="eur" {{ $all_stripe_settings->setting_site_stripe_currency == 'eur' ? 'selected' : '' }}>eur</option>
                                    <option value="fjd" {{ $all_stripe_settings->setting_site_stripe_currency == 'fjd' ? 'selected' : '' }}>fjd</option>
                                    <option value="fkp" {{ $all_stripe_settings->setting_site_stripe_currency == 'fkp' ? 'selected' : '' }}>fkp</option>
                                    <option value="gbp" {{ $all_stripe_settings->setting_site_stripe_currency == 'gbp' ? 'selected' : '' }}>gbp</option>
                                    <option value="gel" {{ $all_stripe_settings->setting_site_stripe_currency == 'gel' ? 'selected' : '' }}>gel</option>
                                    <option value="gip" {{ $all_stripe_settings->setting_site_stripe_currency == 'gip' ? 'selected' : '' }}>gip</option>
                                    <option value="gmd" {{ $all_stripe_settings->setting_site_stripe_currency == 'gmd' ? 'selected' : '' }}>gmd</option>
                                    <option value="gnf" {{ $all_stripe_settings->setting_site_stripe_currency == 'gnf' ? 'selected' : '' }}>gnf</option>
                                    <option value="gtq" {{ $all_stripe_settings->setting_site_stripe_currency == 'gtq' ? 'selected' : '' }}>gtq</option>
                                    <option value="gyd" {{ $all_stripe_settings->setting_site_stripe_currency == 'gyd' ? 'selected' : '' }}>gyd</option>
                                    <option value="hkd" {{ $all_stripe_settings->setting_site_stripe_currency == 'hkd' ? 'selected' : '' }}>hkd</option>
                                    <option value="hnl" {{ $all_stripe_settings->setting_site_stripe_currency == 'hnl' ? 'selected' : '' }}>hnl</option>
                                    <option value="hrk" {{ $all_stripe_settings->setting_site_stripe_currency == 'hrk' ? 'selected' : '' }}>hrk</option>
                                    <option value="htg" {{ $all_stripe_settings->setting_site_stripe_currency == 'htg' ? 'selected' : '' }}>htg</option>
                                    <option value="huf" {{ $all_stripe_settings->setting_site_stripe_currency == 'huf' ? 'selected' : '' }}>huf</option>
                                    <option value="idr" {{ $all_stripe_settings->setting_site_stripe_currency == 'idr' ? 'selected' : '' }}>idr</option>
                                    <option value="ils" {{ $all_stripe_settings->setting_site_stripe_currency == 'ils' ? 'selected' : '' }}>ils</option>
                                    <option value="inr" {{ $all_stripe_settings->setting_site_stripe_currency == 'inr' ? 'selected' : '' }}>inr</option>
                                    <option value="isk" {{ $all_stripe_settings->setting_site_stripe_currency == 'isk' ? 'selected' : '' }}>isk</option>
                                    <option value="jmd" {{ $all_stripe_settings->setting_site_stripe_currency == 'jmd' ? 'selected' : '' }}>jmd</option>
                                    <option value="jpy" {{ $all_stripe_settings->setting_site_stripe_currency == 'jpy' ? 'selected' : '' }}>jpy</option>
                                    <option value="kes" {{ $all_stripe_settings->setting_site_stripe_currency == 'kes' ? 'selected' : '' }}>kes</option>
                                    <option value="kgs" {{ $all_stripe_settings->setting_site_stripe_currency == 'kgs' ? 'selected' : '' }}>kgs</option>
                                    <option value="khr" {{ $all_stripe_settings->setting_site_stripe_currency == 'khr' ? 'selected' : '' }}>khr</option>
                                    <option value="kmf" {{ $all_stripe_settings->setting_site_stripe_currency == 'kmf' ? 'selected' : '' }}>kmf</option>
                                    <option value="krw" {{ $all_stripe_settings->setting_site_stripe_currency == 'krw' ? 'selected' : '' }}>krw</option>
                                    <option value="kyd" {{ $all_stripe_settings->setting_site_stripe_currency == 'kyd' ? 'selected' : '' }}>kyd</option>
                                    <option value="kzt" {{ $all_stripe_settings->setting_site_stripe_currency == 'kzt' ? 'selected' : '' }}>kzt</option>
                                    <option value="lak" {{ $all_stripe_settings->setting_site_stripe_currency == 'lak' ? 'selected' : '' }}>lak</option>
                                    <option value="lbp" {{ $all_stripe_settings->setting_site_stripe_currency == 'lbp' ? 'selected' : '' }}>lbp</option>
                                    <option value="lkr" {{ $all_stripe_settings->setting_site_stripe_currency == 'lkr' ? 'selected' : '' }}>lkr</option>
                                    <option value="lrd" {{ $all_stripe_settings->setting_site_stripe_currency == 'lrd' ? 'selected' : '' }}>lrd</option>
                                    <option value="lsl" {{ $all_stripe_settings->setting_site_stripe_currency == 'lsl' ? 'selected' : '' }}>lsl</option>
                                    <option value="mad" {{ $all_stripe_settings->setting_site_stripe_currency == 'mad' ? 'selected' : '' }}>mad</option>
                                    <option value="mdl" {{ $all_stripe_settings->setting_site_stripe_currency == 'mdl' ? 'selected' : '' }}>mdl</option>
                                    <option value="mga" {{ $all_stripe_settings->setting_site_stripe_currency == 'mga' ? 'selected' : '' }}>mga</option>
                                    <option value="mkd" {{ $all_stripe_settings->setting_site_stripe_currency == 'mkd' ? 'selected' : '' }}>mkd</option>
                                    <option value="mmk" {{ $all_stripe_settings->setting_site_stripe_currency == 'mmk' ? 'selected' : '' }}>mmk</option>
                                    <option value="mnt" {{ $all_stripe_settings->setting_site_stripe_currency == 'mnt' ? 'selected' : '' }}>mnt</option>
                                    <option value="mop" {{ $all_stripe_settings->setting_site_stripe_currency == 'mop' ? 'selected' : '' }}>mop</option>
                                    <option value="mro" {{ $all_stripe_settings->setting_site_stripe_currency == 'mro' ? 'selected' : '' }}>mro</option>
                                    <option value="mur" {{ $all_stripe_settings->setting_site_stripe_currency == 'mur' ? 'selected' : '' }}>mur</option>
                                    <option value="mvr" {{ $all_stripe_settings->setting_site_stripe_currency == 'mvr' ? 'selected' : '' }}>mvr</option>
                                    <option value="mwk" {{ $all_stripe_settings->setting_site_stripe_currency == 'mwk' ? 'selected' : '' }}>mwk</option>
                                    <option value="mxn" {{ $all_stripe_settings->setting_site_stripe_currency == 'mxn' ? 'selected' : '' }}>mxn</option>
                                    <option value="myr" {{ $all_stripe_settings->setting_site_stripe_currency == 'myr' ? 'selected' : '' }}>myr</option>
                                    <option value="mzn" {{ $all_stripe_settings->setting_site_stripe_currency == 'mzn' ? 'selected' : '' }}>mzn</option>
                                    <option value="nad" {{ $all_stripe_settings->setting_site_stripe_currency == 'nad' ? 'selected' : '' }}>nad</option>
                                    <option value="ngn" {{ $all_stripe_settings->setting_site_stripe_currency == 'ngn' ? 'selected' : '' }}>ngn</option>
                                    <option value="nio" {{ $all_stripe_settings->setting_site_stripe_currency == 'nio' ? 'selected' : '' }}>nio</option>
                                    <option value="nok" {{ $all_stripe_settings->setting_site_stripe_currency == 'nok' ? 'selected' : '' }}>nok</option>
                                    <option value="npr" {{ $all_stripe_settings->setting_site_stripe_currency == 'npr' ? 'selected' : '' }}>npr</option>
                                    <option value="nzd" {{ $all_stripe_settings->setting_site_stripe_currency == 'nzd' ? 'selected' : '' }}>nzd</option>
                                    <option value="pab" {{ $all_stripe_settings->setting_site_stripe_currency == 'pab' ? 'selected' : '' }}>pab</option>
                                    <option value="pen" {{ $all_stripe_settings->setting_site_stripe_currency == 'pen' ? 'selected' : '' }}>pen</option>
                                    <option value="pgk" {{ $all_stripe_settings->setting_site_stripe_currency == 'pgk' ? 'selected' : '' }}>pgk</option>
                                    <option value="php" {{ $all_stripe_settings->setting_site_stripe_currency == 'php' ? 'selected' : '' }}>php</option>
                                    <option value="pkr" {{ $all_stripe_settings->setting_site_stripe_currency == 'pkr' ? 'selected' : '' }}>pkr</option>
                                    <option value="pln" {{ $all_stripe_settings->setting_site_stripe_currency == 'pln' ? 'selected' : '' }}>pln</option>
                                    <option value="pyg" {{ $all_stripe_settings->setting_site_stripe_currency == 'pyg' ? 'selected' : '' }}>pyg</option>
                                    <option value="qar" {{ $all_stripe_settings->setting_site_stripe_currency == 'qar' ? 'selected' : '' }}>qar</option>
                                    <option value="ron" {{ $all_stripe_settings->setting_site_stripe_currency == 'ron' ? 'selected' : '' }}>ron</option>
                                    <option value="rsd" {{ $all_stripe_settings->setting_site_stripe_currency == 'rsd' ? 'selected' : '' }}>rsd</option>
                                    <option value="rub" {{ $all_stripe_settings->setting_site_stripe_currency == 'rub' ? 'selected' : '' }}>rub</option>
                                    <option value="rwf" {{ $all_stripe_settings->setting_site_stripe_currency == 'rwf' ? 'selected' : '' }}>rwf</option>
                                    <option value="sar" {{ $all_stripe_settings->setting_site_stripe_currency == 'sar' ? 'selected' : '' }}>sar</option>
                                    <option value="sbd" {{ $all_stripe_settings->setting_site_stripe_currency == 'sbd' ? 'selected' : '' }}>sbd</option>
                                    <option value="scr" {{ $all_stripe_settings->setting_site_stripe_currency == 'scr' ? 'selected' : '' }}>scr</option>
                                    <option value="sek" {{ $all_stripe_settings->setting_site_stripe_currency == 'sek' ? 'selected' : '' }}>sek</option>
                                    <option value="sgd" {{ $all_stripe_settings->setting_site_stripe_currency == 'sgd' ? 'selected' : '' }}>sgd</option>
                                    <option value="shp" {{ $all_stripe_settings->setting_site_stripe_currency == 'shp' ? 'selected' : '' }}>shp</option>
                                    <option value="sll" {{ $all_stripe_settings->setting_site_stripe_currency == 'sll' ? 'selected' : '' }}>sll</option>
                                    <option value="sos" {{ $all_stripe_settings->setting_site_stripe_currency == 'sos' ? 'selected' : '' }}>sos</option>
                                    <option value="srd" {{ $all_stripe_settings->setting_site_stripe_currency == 'srd' ? 'selected' : '' }}>srd</option>
                                    <option value="std" {{ $all_stripe_settings->setting_site_stripe_currency == 'std' ? 'selected' : '' }}>std</option>
                                    <option value="szl" {{ $all_stripe_settings->setting_site_stripe_currency == 'szl' ? 'selected' : '' }}>szl</option>
                                    <option value="thb" {{ $all_stripe_settings->setting_site_stripe_currency == 'thb' ? 'selected' : '' }}>thb</option>
                                    <option value="tjs" {{ $all_stripe_settings->setting_site_stripe_currency == 'tjs' ? 'selected' : '' }}>tjs</option>
                                    <option value="top" {{ $all_stripe_settings->setting_site_stripe_currency == 'top' ? 'selected' : '' }}>top</option>
                                    <option value="try" {{ $all_stripe_settings->setting_site_stripe_currency == 'try' ? 'selected' : '' }}>try</option>
                                    <option value="ttd" {{ $all_stripe_settings->setting_site_stripe_currency == 'ttd' ? 'selected' : '' }}>ttd</option>
                                    <option value="twd" {{ $all_stripe_settings->setting_site_stripe_currency == 'twd' ? 'selected' : '' }}>twd</option>
                                    <option value="tzs" {{ $all_stripe_settings->setting_site_stripe_currency == 'tzs' ? 'selected' : '' }}>tzs</option>
                                    <option value="uah" {{ $all_stripe_settings->setting_site_stripe_currency == 'uah' ? 'selected' : '' }}>uah</option>
                                    <option value="ugx" {{ $all_stripe_settings->setting_site_stripe_currency == 'ugx' ? 'selected' : '' }}>ugx</option>
                                    <option value="uyu" {{ $all_stripe_settings->setting_site_stripe_currency == 'uyu' ? 'selected' : '' }}>uyu</option>
                                    <option value="uzs" {{ $all_stripe_settings->setting_site_stripe_currency == 'uzs' ? 'selected' : '' }}>uzs</option>
                                    <option value="vnd" {{ $all_stripe_settings->setting_site_stripe_currency == 'vnd' ? 'selected' : '' }}>vnd</option>
                                    <option value="vuv" {{ $all_stripe_settings->setting_site_stripe_currency == 'vuv' ? 'selected' : '' }}>vuv</option>
                                    <option value="wst" {{ $all_stripe_settings->setting_site_stripe_currency == 'wst' ? 'selected' : '' }}>wst</option>
                                    <option value="xaf" {{ $all_stripe_settings->setting_site_stripe_currency == 'xaf' ? 'selected' : '' }}>xaf</option>
                                    <option value="xcd" {{ $all_stripe_settings->setting_site_stripe_currency == 'xcd' ? 'selected' : '' }}>xcd</option>
                                    <option value="xof" {{ $all_stripe_settings->setting_site_stripe_currency == 'xof' ? 'selected' : '' }}>xof</option>
                                    <option value="xpf" {{ $all_stripe_settings->setting_site_stripe_currency == 'xpf' ? 'selected' : '' }}>xpf</option>
                                    <option value="yer" {{ $all_stripe_settings->setting_site_stripe_currency == 'yer' ? 'selected' : '' }}>yer</option>
                                    <option value="zar" {{ $all_stripe_settings->setting_site_stripe_currency == 'zar' ? 'selected' : '' }}>zar</option>
                                    <option value="zmw" {{ $all_stripe_settings->setting_site_stripe_currency == 'zmw' ? 'selected' : '' }}>zmw</option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('stripe.stripe-currency-help') }}
                                </small>
                                @error('setting_site_stripe_currency')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-success text-white">
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
