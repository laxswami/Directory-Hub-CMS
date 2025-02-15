@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('product_attributes.edit') }}</h1>
            <p class="mb-4">{{ __('product_attributes.edit-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.attributes.index') }}" class="btn btn-info btn-icon-split">
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

            <div class="row mb-3">
                <div class="col-12">
                    @if($attribute_owner->isUser())
                        <div class="alert alert-warning" role="alert">
                            {{ __('product_attributes.edit-owner-alert', ['user_name' => $attribute_owner->name, 'user_email' => $attribute_owner->email]) }}
                            <a href="{{ route('admin.users.edit', ['user' => $attribute_owner->id]) }}" class="alert-link" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                                {{ __('product_attributes.edit-owner-alert-view-profile') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-10 col-sm-12">
                    <form method="POST" action="{{ route('admin.attributes.update', ['attribute' => $attribute->id]) }}" class="">
                        @csrf
                        @method('PUT')
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="attribute_name" class="text-black">{{ __('product_attributes.form-attribute-name') }}</label>
                                <input id="attribute_name" type="text" class="form-control @error('attribute_name') is-invalid @enderror" name="attribute_name" value="{{ old('attribute_name') ? old('attribute_name') : $attribute->attribute_name }}">
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
                                    <option value="{{ \App\Attribute::TYPE_TEXT }}" {{ (old('attribute_type') ? old('attribute_type') : $attribute->attribute_type) == \App\Attribute::TYPE_TEXT ? 'selected' : '' }}>{{ __('product_attributes.type-text') }}</option>
                                    <option value="{{ \App\Attribute::TYPE_SELECT }}" {{ (old('attribute_type') ? old('attribute_type') : $attribute->attribute_type) == \App\Attribute::TYPE_SELECT ? 'selected' : '' }}>{{ __('product_attributes.type-select') }}</option>
                                    <option value="{{ \App\Attribute::TYPE_MULTI_SELECT }}" {{ (old('attribute_type') ? old('attribute_type') : $attribute->attribute_type) == \App\Attribute::TYPE_MULTI_SELECT ? 'selected' : '' }}>{{ __('product_attributes.type-multi-select') }}</option>
                                    <option value="{{ \App\Attribute::TYPE_LINK }}" {{ (old('attribute_type') ? old('attribute_type') : $attribute->attribute_type) == \App\Attribute::TYPE_LINK ? 'selected' : '' }}>{{ __('product_attributes.type-link') }}</option>
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
                                <input id="attribute_seed_value" type="text" class="form-control @error('attribute_seed_value') is-invalid @enderror" name="attribute_seed_value" value="{{ old('attribute_seed_value') ? old('attribute_seed_value') : $attribute->attribute_seed_value }}">
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

    <!-- Modal - delete -->
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
                    {{ __('backend.shared.delete-message', ['record_type' => __('product_attribute.product-attribute'), 'record_name' => $attribute->attribute_name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.attributes.destroy', ['attribute' => $attribute->id]) }}" method="POST">
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
