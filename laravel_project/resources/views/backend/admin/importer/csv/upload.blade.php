@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('importer_csv.show-upload') }}</h1>
            <p class="mb-4">{{ __('importer_csv.show-upload-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-12">

                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <p>
                            <strong>
                                <i class="far fa-question-circle"></i>
                                {{ __('importer_csv.csv-file-upload-listing-instruction') }}
                            </strong>
                        </p>
                        <ul>
                            <li>{{ __('sitemap_import.csv-file-upload-listing-instruction-columns') }}</li>
                            <li>{{ __('sitemap_import.csv-file-upload-listing-instruction-image-urls') }}</li>
                            <li>{{ __('sitemap_import.csv-file-upload-listing-instruction-gallery-images-urls') }}</li>
                            <li>
                                {{ __('import_csv_hours.timezone-import-help') }}
                                <a href="{{ \App\ImportCsvData::URL_PHP_TIMEZONE }}" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    {{ __('import_csv_hours.timezone-php') }}
                                </a>
                            </li>
                            <li>{{ __('import_csv_hours.show-hours-import-help') }}</li>
                            <li>{{ __('import_csv_hours.hours-import-help') }}</li>
                            <li>{{ __('import_csv_hours.exceptional-hours-help') }}</li>
                            <li>{{ __('importer_csv.csv-file-upload-listing-instruction-tip-1') }}</li>
                            <li>{{ __('importer_csv.csv-file-upload-listing-instruction-tip-2') }}</li>
                            <li>{{ __('importer_csv.csv-file-upload-listing-instruction-tip-3') }}</li>
                            <li>
                                <a href="{{ \App\ImportCsvData::URL_DOWNLOAD_SAMPLE_CSV  }}" target="_blank">
                                    <i class="fas fa-cloud-download-alt"></i>
                                    {{ __('import_csv_hours.download-sample-import-csv') }}
                                </a>
                            </li>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.importer.csv.upload.process') }}" class="" enctype="multipart/form-data">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-6 col-12">
                                <label class="text-black" for="import_csv_data_for_model">{{ __('importer_csv.csv-for-model') }}</label>
                                <select class="custom-select @error('import_csv_data_for_model') is-invalid @enderror" name="import_csv_data_for_model" id="import_csv_data_for_model">
                                    <option {{ old('import_csv_data_for_model') == \App\ImportCsvData::IMPORT_CSV_FOR_MODEL_LISTING ? 'selected' : '' }} value="{{ \App\ImportCsvData::IMPORT_CSV_FOR_MODEL_LISTING }}">{{ __('importer_csv.csv-for-model-listing') }}</option>
                                </select>
                                @error('import_csv_data_for_model')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 col-12">
                                <label for="import_csv_data_file" class="text-black">{{ __('importer_csv.choose-csv-file') }}</label>
                                <input id="import_csv_data_file" type="file" class="form-control @error('import_csv_data_file') is-invalid @enderror" name="import_csv_data_file">
                                <small class="form-text text-muted">
                                    {{ __('importer_csv.choose-csv-file-help') }}
                                </small>
                                @error('import_csv_data_file')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 col-12">
                                <div class="custom-control custom-checkbox">
                                    <input value="{{ \App\ImportCsvData::IMPORT_CSV_SKIP_FIRST_ROW_YES }}" name="import_csv_data_skip_first_row" type="checkbox" class="custom-control-input" id="import_csv_data_skip_first_row" {{ old('import_csv_data_skip_first_row') == \App\ImportCsvData::IMPORT_CSV_SKIP_FIRST_ROW_YES ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="import_csv_data_skip_first_row">{{ __('importer_csv.csv-skip-first-row') }}</label>
                                </div>
                                @error('import_csv_data_skip_first_row')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row form-group justify-content-between">
                            <div class="col-6">
                                <button type="submit" class="btn btn-success text-white">
                                    {{ __('importer_csv.upload') }}
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('admin.importer.csv.upload.data.index') }}">
                                    {{ __('importer_csv.sidebar.upload-history') }}
                                </a>
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
