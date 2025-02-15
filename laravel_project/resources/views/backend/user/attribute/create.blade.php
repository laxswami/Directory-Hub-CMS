@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('product_attributes.create') }}</h1>
            <p class="mb-4">{{ __('product_attributes.create-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.attributes.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-xl-6 col-lg-10 col-sm-12">
                    <form method="POST" action="{{ route('user.attributes.store') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="attribute_name" class="text-black">{{ __('product_attributes.form-attribute-name') }}</label>
                                <input id="attribute_name" type="text" class="form-control @error('attribute_name') is-invalid @enderror" name="attribute_name" value="{{ old('attribute_name') }}">
                                @error('attribute_name')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="attribute_type" class="text-black">{{ __('product_attributes.form-attribute-type') }}</label>

                                <select class="custom-select" name="attribute_type">
                                    <option value="{{ \App\Attribute::TYPE_TEXT }}" {{ old('attribute_type') == \App\Attribute::TYPE_TEXT ? 'selected' : '' }}>{{ __('product_attributes.type-text') }}</option>
                                    <option value="{{ \App\Attribute::TYPE_SELECT }}" {{ old('attribute_type') == \App\Attribute::TYPE_SELECT ? 'selected' : '' }}>{{ __('product_attributes.type-select') }}</option>
                                    <option value="{{ \App\Attribute::TYPE_MULTI_SELECT }}" {{ old('attribute_type') == \App\Attribute::TYPE_MULTI_SELECT ? 'selected' : '' }}>{{ __('product_attributes.type-multi-select') }}</option>
                                    <option value="{{ \App\Attribute::TYPE_LINK }}" {{ old('attribute_type') == \App\Attribute::TYPE_LINK ? 'selected' : '' }}>{{ __('product_attributes.type-link') }}</option>
                                </select>

                                @error('attribute_type')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="attribute_seed_value" class="text-black">{{ __('product_attributes.form-attribute-seed-value') }}</label>
                                <input id="attribute_seed_value" type="text" class="form-control @error('attribute_seed_value') is-invalid @enderror" name="attribute_seed_value" value="{{ old('attribute_seed_value') }}">
                                <small id="attribute_seed_valueHelpBlock" class="form-text text-muted">
                                    {{ __('product_attributes.form-attribute-seed-value-help') }}
                                </small>
                                @error('attribute_seed_value')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.create') }}
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
