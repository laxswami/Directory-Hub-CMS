@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-mb-12 col-lg-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('bank_transfer.edit-bank-transfer') }}</h1>
            <p class="mb-4">{{ __('bank_transfer.edit-bank-transfer-desc') }}</p>
        </div>
        <div class="col-mb-12 col-lg-3 pb-3 text-right">
            <a href="{{ route('admin.settings.payment.bank-transfer.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-mb-12 col-lg-6">
                    <form method="POST" action="{{ route('admin.settings.payment.bank-transfer.update', ['setting_bank_transfer' => $setting_bank_transfer->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row form-group">
                            <div class="col-12">
                                @if($setting_bank_transfer->setting_bank_transfer_status == \App\Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-success text-white rounded">{{ __('bank_transfer.enable') }}</span>
                                @else
                                    <span class="pl-2 pr-2 pt-1 pb-1 bg-warning text-white rounded">{{ __('bank_transfer.disable') }}</span>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="setting_bank_transfer_bank_name" class="text-black">{{ __('bank_transfer.bank-name') }}</label>
                                <input id="setting_bank_transfer_bank_name" type="text" class="form-control @error('setting_bank_transfer_bank_name') is-invalid @enderror" name="setting_bank_transfer_bank_name" value="{{ old('setting_bank_transfer_bank_name') ? old('setting_bank_transfer_bank_name') : $setting_bank_transfer->setting_bank_transfer_bank_name }}">
                                @error('setting_bank_transfer_bank_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="setting_bank_transfer_bank_account_info">{{ __('bank_transfer.bank-account-info') }}</label>
                                <textarea rows="4" id="setting_bank_transfer_bank_account_info" class="form-control @error('setting_bank_transfer_bank_account_info') is-invalid @enderror" name="setting_bank_transfer_bank_account_info">{{ old('setting_bank_transfer_bank_account_info') ? old('setting_bank_transfer_bank_account_info') : $setting_bank_transfer->setting_bank_transfer_bank_account_info }}</textarea>
                                @error('setting_bank_transfer_bank_account_info')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-6">
                                <label class="text-black" for="setting_bank_transfer_status">{{ __('bank_transfer.status') }}</label>

                                <select class="custom-select @error('setting_bank_transfer_status') is-invalid @enderror" name="setting_bank_transfer_status">
                                    <option value="{{ \App\Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE }}" {{ (old('setting_bank_transfer_status') ? old('setting_bank_transfer_status') : $setting_bank_transfer->setting_bank_transfer_status) == \App\Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE ? 'selected' : '' }}>{{ __('bank_transfer.enable') }}</option>
                                    <option value="{{ \App\Setting::SITE_PAYMENT_BANK_TRANSFER_DISABLE }}" {{ (old('setting_bank_transfer_status') ? old('setting_bank_transfer_status') : $setting_bank_transfer->setting_bank_transfer_status) == \App\Setting::SITE_PAYMENT_BANK_TRANSFER_DISABLE ? 'selected' : '' }}>{{ __('bank_transfer.disable') }}</option>
                                </select>
                                @error('setting_bank_transfer_status')
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
                            <div class="col-4 text-right">
                                <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                    {{ __('backend.shared.delete') }}
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
                    {{ __('backend.shared.delete-message', ['record_type' => __('bank_transfer.bank-transfer'), 'record_name' => $setting_bank_transfer->setting_bank_transfer_bank_name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.settings.payment.bank-transfer.destroy', ['setting_bank_transfer' => $setting_bank_transfer->id]) }}" method="POST">
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
