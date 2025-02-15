@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.custom-field.edit-custom-field') }}</h1>
            <p class="mb-4">{{ __('backend.custom-field.edit-custom-field-desc') }}</p>
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
                    <form method="POST" action="{{ route('admin.custom-fields.update', $customField) }}" class="">
                        @csrf
                        @method('PUT')

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="custom_field_name" class="text-black">{{ __('backend.custom-field.custom-field-name') }}</label>
                                <input id="custom_field_name" type="text" class="form-control @error('custom_field_name') is-invalid @enderror" name="custom_field_name" value="{{ old('custom_field_name') ? old('custom_field_name') : $customField->custom_field_name }}" autofocus>
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
                                    <option value="{{ \App\CustomField::TYPE_TEXT }}" {{ (old('custom_field_type') ? old('custom_field_type') : $customField->custom_field_type) == \App\CustomField::TYPE_TEXT ? 'selected' : '' }}>{{ __('backend.custom-field.text') }}</option>
                                    <option value="{{ \App\CustomField::TYPE_SELECT }}" {{ (old('custom_field_type') ? old('custom_field_type') : $customField->custom_field_type) == \App\CustomField::TYPE_SELECT ? 'selected' : '' }}>{{ __('backend.custom-field.select') }}</option>
                                    <option value="{{ \App\CustomField::TYPE_MULTI_SELECT }}" {{ (old('custom_field_type') ? old('custom_field_type') : $customField->custom_field_type) == \App\CustomField::TYPE_MULTI_SELECT ? 'selected' : '' }}>{{ __('backend.custom-field.multi-select') }}</option>
                                    <option value="{{ \App\CustomField::TYPE_LINK }}" {{ (old('custom_field_type') ? old('custom_field_type') : $customField->custom_field_type) == \App\CustomField::TYPE_LINK ? 'selected' : '' }}>{{ __('backend.custom-field.link') }}</option>
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
                                <input id="custom_field_seed_value" type="text" class="form-control @error('custom_field_seed_value') is-invalid @enderror" name="custom_field_seed_value" value="{{ old('custom_field_seed_value') ? old('custom_field_seed_value') : $customField->custom_field_seed_value }}" aria-describedby="seedValueHelpBlock">
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
                                <input id="custom_field_order" type="number" min="0" class="form-control @error('custom_field_order') is-invalid @enderror" name="custom_field_order" value="{{ old('custom_field_order') ? old('custom_field_order') : $customField->custom_field_order }}">
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
                                        <option {{ (in_array($category['category_id'], old('category') ? old('category') : array()) || $customField->isBelongToCategory($category['category_id'])) ? 'selected' : '' }} value="{{ $category['category_id'] }}">{{ $category['category_name'] }}</option>
                                    @endforeach
                                </select>
                                @error('category')
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
                    {{ __('delete_custom_field.delete-custom-field-warning') . ' ' . __('backend.shared.delete-message', ['record_type' => __('backend.shared.custom-field'), 'record_name' => $customField->custom_field_name]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('admin.custom-fields.destroy', $customField) }}" method="POST">
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
