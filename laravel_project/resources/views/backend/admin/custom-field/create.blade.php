@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.custom-field.add-custom-field') }}</h1>
            <p class="mb-4">{{ __('backend.custom-field.add-custom-field-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.custom-fields.index') }}" class="btn btn-info btn-icon-split">
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
                <div class="col-12 col-md-10 col-lg-6">
                    <form method="POST" action="{{ route('admin.custom-fields.store') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="custom_field_name" class="text-black">{{ __('backend.custom-field.custom-field-name') }}</label>
                                <input id="custom_field_name" type="text" class="form-control @error('custom_field_name') is-invalid @enderror" name="custom_field_name" value="{{ old('custom_field_name') }}" autofocus>
                                @error('custom_field_name')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="custom_field_type">{{ __('backend.custom-field.custom-field-type') }}</label>

                                <select class="custom-select" name="custom_field_type">
                                    <option value="{{ \App\CustomField::TYPE_TEXT }}" {{ old('custom_field_type') == \App\CustomField::TYPE_TEXT ? 'selected' : '' }}>{{ __('backend.custom-field.text') }}</option>
                                    <option value="{{ \App\CustomField::TYPE_SELECT }}" {{ old('custom_field_type') == \App\CustomField::TYPE_SELECT ? 'selected' : '' }}>{{ __('backend.custom-field.select') }}</option>
                                    <option value="{{ \App\CustomField::TYPE_MULTI_SELECT }}" {{ old('custom_field_type') == \App\CustomField::TYPE_MULTI_SELECT ? 'selected' : '' }}>{{ __('backend.custom-field.multi-select') }}</option>
                                    <option value="{{ \App\CustomField::TYPE_LINK }}" {{ old('custom_field_type') == \App\CustomField::TYPE_LINK ? 'selected' : '' }}>{{ __('backend.custom-field.link') }}</option>
                                </select>
                                @error('custom_field_type')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="custom_field_seed_value">{{ __('backend.custom-field.custom-field-seed-value') }}</label>
                                <input id="custom_field_seed_value" type="text" class="form-control @error('custom_field_seed_value') is-invalid @enderror" name="custom_field_seed_value" value="{{ old('custom_field_seed_value') }}" aria-describedby="seedValueHelpBlock">
                                <small id="seedValueHelpBlock" class="form-text text-muted">
                                    {{ __('backend.custom-field.custom-field-seed-value-help') }}
                                </small>
                                @error('custom_field_seed_value')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="custom_field_order">{{ __('backend.custom-field.custom-field-order') }}</label>
                                <input id="custom_field_order" type="number" min="0" class="form-control @error('custom_field_order') is-invalid @enderror" name="custom_field_order" value="{{ old('custom_field_order') }}">
                                @error('custom_field_order')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">

                                <label for="category" class="text-black">{{ __('backend.custom-field.categories') }}</label>
                                <select multiple class="custom-select" name="category[]" id="category" size="{{ count($all_categories) }}">
                                    @foreach($all_categories as $key => $category)
                                        <option {{ in_array($category['category_id'], old('category') ? old('category') : array()) ? 'selected' : '' }} value="{{ $category['category_id'] }}">{{ $category['category_name'] }}</option>
                                    @endforeach
                                </select>
                                @error('category')
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
