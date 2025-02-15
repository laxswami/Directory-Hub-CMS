@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('razorpay_setting.edit-razorpay-setting') }}</h1>
            <p class="mb-4">{{ __('razorpay_setting.edit-razorpay-setting-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.payment.razorpay.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-12">
                                @if($all_razorpay_settings->setting_site_razorpay_enable == \App\Setting::SITE_PAYMENT_RAZORPAY_ENABLE)
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-success text-white rounded">{{ __('razorpay_setting.razorpay-enabled') }}</span>
                                @else
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-warning text-white rounded">{{ __('razorpay_setting.razorpay-disabled') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12">
                                <span>{{ __('razorpay.webhook') }}: {{ route('user.razorpay.notify') }}</span>
                            </div>
                        </div>
                        <hr>

                        <div class="row form-group">
                            <div class="col-12">

                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\Setting::SITE_PAYMENT_RAZORPAY_ENABLE }}" name="setting_site_razorpay_enable" type="checkbox" class="custom-control-input" id="setting_site_razorpay_enable" {{ (old('setting_site_razorpay_enable') ? old('setting_site_razorpay_enable') : $all_razorpay_settings->setting_site_razorpay_enable) == \App\Setting::SITE_PAYMENT_RAZORPAY_ENABLE ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_site_razorpay_enable">{{ __('razorpay_setting.enable-razorpay') }}</label>
                                </div>
                                @error('setting_site_razorpay_enable')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-4 col-sm-12">

                                <label class="text-black" for="setting_site_razorpay_api_key">{{ __('razorpay.api-key') }}</label>
                                <input id="setting_site_razorpay_api_key" type="text" class="form-control @error('setting_site_razorpay_api_key') is-invalid @enderror" name="setting_site_razorpay_api_key" value="{{ old('setting_site_razorpay_api_key') ? old('setting_site_razorpay_api_key') : $all_razorpay_settings->setting_site_razorpay_api_key }}">
                                @error('setting_site_razorpay_api_key')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">

                                <label class="text-black" for="setting_site_razorpay_api_secret">{{ __('razorpay.api-secret') }}</label>
                                <input id="setting_site_razorpay_api_secret" type="text" class="form-control @error('setting_site_razorpay_api_secret') is-invalid @enderror" name="setting_site_razorpay_api_secret" value="{{ old('setting_site_razorpay_api_secret') ? old('setting_site_razorpay_api_secret') : $all_razorpay_settings->setting_site_razorpay_api_secret }}">
                                @error('setting_site_razorpay_api_secret')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label class="text-black" for="setting_site_razorpay_currency">{{ __('razorpay.currency') }}</label>
                                <select class="custom-select @error('setting_site_razorpay_currency') is-invalid @enderror" name="setting_site_razorpay_currency">
                                    <option value="INR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'INR' ? 'selected' : '' }}>Indian rupee</option>

                                    <option value="AED" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'AED' ? 'selected' : '' }}>United Arab Emirates Dirham</option>
                                    <option value="ALL" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'ALL' ? 'selected' : '' }}>Albanian lek</option>
                                    <option value="AMD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'AMD' ? 'selected' : '' }}>Armenian dram</option>
                                    <option value="ARS" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'ARS' ? 'selected' : '' }}>Argentine peso</option>
                                    <option value="AUD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'AUD' ? 'selected' : '' }}>Australian dollar</option>
                                    <option value="AWG" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'AWG' ? 'selected' : '' }}>Aruban florin</option>
                                    <option value="BBD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BBD' ? 'selected' : '' }}>Barbadian dollar</option>
                                    <option value="BDT" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BDT' ? 'selected' : '' }}>Bangladeshi taka</option>
                                    <option value="BMD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BMD' ? 'selected' : '' }}>Bermudian dollar</option>
                                    <option value="BND" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BND' ? 'selected' : '' }}>Brunei dollar</option>
                                    <option value="BOB" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BOB' ? 'selected' : '' }}>Bolivian boliviano</option>
                                    <option value="BSD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BSD' ? 'selected' : '' }}>Bahamian dollar</option>
                                    <option value="BWP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BWP' ? 'selected' : '' }}>Botswana pula</option>
                                    <option value="BZD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'BZD' ? 'selected' : '' }}>Belize dollar</option>
                                    <option value="CAD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'CAD' ? 'selected' : '' }}>Canadian dollar</option>
                                    <option value="CHF" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'CHF' ? 'selected' : '' }}>Swiss franc</option>
                                    <option value="CNY" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'CNY' ? 'selected' : '' }}>Chinese yuan renminbi</option>
                                    <option value="COP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'COP' ? 'selected' : '' }}>Colombian peso</option>
                                    <option value="CRC" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'CRC' ? 'selected' : '' }}>Costa Rican colon</option>
                                    <option value="CUP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'CUP' ? 'selected' : '' }}>Cuban peso</option>
                                    <option value="CZK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'CZK' ? 'selected' : '' }}>Czech koruna</option>
                                    <option value="DKK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'DKK' ? 'selected' : '' }}>Danish krone</option>
                                    <option value="DOP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'DOP' ? 'selected' : '' }}>Dominican peso</option>
                                    <option value="DZD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'DZD' ? 'selected' : '' }}>Algerian dinar</option>
                                    <option value="EGP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'EGP' ? 'selected' : '' }}>Egyptian pound</option>
                                    <option value="ETB" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'ETB' ? 'selected' : '' }}>Ethiopian birr</option>
                                    <option value="EUR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'EUR' ? 'selected' : '' }}>European euro</option>
                                    <option value="FJD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'FJD' ? 'selected' : '' }}>Fijian dollar</option>
                                    <option value="GBP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'GBP' ? 'selected' : '' }}>Pound sterling</option>
                                    <option value="GIP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'GIP' ? 'selected' : '' }}>Gibraltar pound</option>
                                    <option value="GHS" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'GHS' ? 'selected' : '' }}>Ghanian Cedi</option>
                                    <option value="GMD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'GMD' ? 'selected' : '' }}>Gambian dalasi</option>
                                    <option value="GTQ" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'GTQ' ? 'selected' : '' }}>Guatemalan quetzal</option>
                                    <option value="GYD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'GYD' ? 'selected' : '' }}>Guyanese dollar</option>
                                    <option value="HKD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'HKD' ? 'selected' : '' }}>Hong Kong dollar</option>
                                    <option value="HNL" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'HNL' ? 'selected' : '' }}>Honduran lempira</option>
                                    <option value="HRK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'HRK' ? 'selected' : '' }}>Croatian kuna</option>
                                    <option value="HTG" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'HTG' ? 'selected' : '' }}>Haitian gourde</option>
                                    <option value="HUF" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'HUF' ? 'selected' : '' }}>Hungarian forint</option>
                                    <option value="IDR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'IDR' ? 'selected' : '' }}>Indonesian rupiah</option>
                                    <option value="ILS" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'ILS' ? 'selected' : '' }}>Israeli new shekel</option>
                                    <option value="JMD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'JMD' ? 'selected' : '' }}>Jamaican dollar</option>
                                    <option value="KES" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'KES' ? 'selected' : '' }}>Kenyan shilling</option>
                                    <option value="KGS" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'KGS' ? 'selected' : '' }}>Kyrgyzstani som</option>
                                    <option value="KHR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'KHR' ? 'selected' : '' }}>Cambodian riel</option>
                                    <option value="KYD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'KYD' ? 'selected' : '' }}>Cayman Islands dollar</option>
                                    <option value="KZT" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'KZT' ? 'selected' : '' }}>Kazakhstani tenge</option>
                                    <option value="LAK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'LAK' ? 'selected' : '' }}>Lao kip</option>
                                    <option value="LBP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'LBP' ? 'selected' : '' }}>Lebanese pound</option>
                                    <option value="LKR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'LKR' ? 'selected' : '' }}>Sri Lankan rupee</option>
                                    <option value="LRD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'LRD' ? 'selected' : '' }}>Liberian dollar</option>
                                    <option value="LSL" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'LSL' ? 'selected' : '' }}>Lesotho loti</option>
                                    <option value="MAD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MAD' ? 'selected' : '' }}>Moroccan dirham</option>
                                    <option value="MDL" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MDL' ? 'selected' : '' }}>Moldovan leu</option>
                                    <option value="MKD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MKD' ? 'selected' : '' }}>Macedonian denar</option>
                                    <option value="MMK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MMK' ? 'selected' : '' }}>Myanmar kyat</option>
                                    <option value="MNT" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MNT' ? 'selected' : '' }}>Mongolian tugrik</option>
                                    <option value="MOP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MOP' ? 'selected' : '' }}>Macanese pataca</option>
                                    <option value="MUR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MUR' ? 'selected' : '' }}>Mauritian rupee</option>
                                    <option value="MVR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MVR' ? 'selected' : '' }}>Maldivian rufiyaa</option>
                                    <option value="MWK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MWK' ? 'selected' : '' }}>Malawian kwacha</option>
                                    <option value="MXN" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MXN' ? 'selected' : '' }}>Mexican peso</option>
                                    <option value="MYR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'MYR' ? 'selected' : '' }}>Malaysian ringgit</option>
                                    <option value="NAD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'NAD' ? 'selected' : '' }}>Namibian dollar</option>
                                    <option value="NGN" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'NGN' ? 'selected' : '' }}>Nigerian naira</option>
                                    <option value="NIO" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'NIO' ? 'selected' : '' }}>Nicaraguan cordoba</option>
                                    <option value="NOK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'NOK' ? 'selected' : '' }}>Norwegian krone</option>
                                    <option value="NPR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'NPR' ? 'selected' : '' }}>Nepalese rupee</option>
                                    <option value="NZD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'NZD' ? 'selected' : '' }}>New Zealand dollar</option>
                                    <option value="PEN" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'PEN' ? 'selected' : '' }}>Peruvian sol</option>
                                    <option value="PGK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'PGK' ? 'selected' : '' }}>Papua New Guinean kina</option>
                                    <option value="PHP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'PHP' ? 'selected' : '' }}>Philippine peso</option>
                                    <option value="PKR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'PKR' ? 'selected' : '' }}>Pakistani rupee</option>
                                    <option value="QAR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'QAR' ? 'selected' : '' }}>Qatari riyal</option>
                                    <option value="RUB" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'RUB' ? 'selected' : '' }}>Russian ruble</option>
                                    <option value="SAR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SAR' ? 'selected' : '' }}>Saudi Arabian riyal</option>
                                    <option value="SCR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SCR' ? 'selected' : '' }}>Seychellois rupee</option>
                                    <option value="SEK" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SEK' ? 'selected' : '' }}>Swedish krona</option>
                                    <option value="SGD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SGD' ? 'selected' : '' }}>Singapore dollar</option>
                                    <option value="SLL" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SLL' ? 'selected' : '' }}>Sierra Leonean leone</option>
                                    <option value="SOS" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SOS' ? 'selected' : '' }}>Somali shilling</option>
                                    <option value="SSP" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SSP' ? 'selected' : '' }}>South Sudanese pound</option>
                                    <option value="SVC" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SVC' ? 'selected' : '' }}>Salvadoran colón</option>
                                    <option value="SZL" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'SZL' ? 'selected' : '' }}>Swazi lilangeni</option>
                                    <option value="THB" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'THB' ? 'selected' : '' }}>Thai baht</option>
                                    <option value="TTD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'TTD' ? 'selected' : '' }}>Trinidad and Tobago dollar</option>
                                    <option value="TZS" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'TZS' ? 'selected' : '' }}>Tanzanian shilling</option>
                                    <option value="USD" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'USD' ? 'selected' : '' }}>United States dollar</option>
                                    <option value="UYU" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'UYU' ? 'selected' : '' }}>Uruguayan peso</option>
                                    <option value="UZS" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'UZS' ? 'selected' : '' }}>Uzbekistani so'm</option>
                                    <option value="YER" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'YER' ? 'selected' : '' }}>Yemeni rial</option>
                                    <option value="ZAR" {{ $all_razorpay_settings->setting_site_razorpay_currency == 'ZAR' ? 'selected' : '' }}>South African rand</option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('razorpay.currency-help') }}
                                </small>
                                @error('setting_site_razorpay_currency')
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
