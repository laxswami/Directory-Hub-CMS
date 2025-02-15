@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('importer_csv.import-csv-data-index') }}</h1>
            <p class="mb-4">{{ __('importer_csv.import-csv-data-index-desc') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.importer.csv.upload.show') }}" class="btn btn-info btn-icon-split">
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

            <div class="row pt-4">
                <div class="col-12">
                    {{ $all_import_csv_data_count . ' ' . __('category_description.records') }}
                </div>
            </div>

            <hr class="mt-1">

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('importer_csv.filename') }}</th>
                                <th>{{ __('importer_csv.progress') }}</th>
                                <th>{{ __('importer_csv.uploaded-at') }}</th>
                                <th>{{ __('importer_csv.model-for') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>{{ __('importer_csv.filename') }}</th>
                                <th>{{ __('importer_csv.progress') }}</th>
                                <th>{{ __('importer_csv.uploaded-at') }}</th>
                                <th>{{ __('importer_csv.model-for') }}</th>
                                <th>{{ __('backend.shared.action') }}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($all_import_csv_data as $all_import_csv_data_key => $import_csv_data)
                                <tr>
                                    <td>{{ $import_csv_data->import_csv_data_filename }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated @if($import_csv_data->import_csv_data_parse_status == \App\ImportCsvData::IMPORT_CSV_STATUS_ALL_PARSED) bg-success @else bg-info @endif" role="progressbar" aria-valuenow="{{ intval($import_csv_data->import_csv_data_parsed_rows/$import_csv_data->import_csv_data_total_rows * 100) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ intval($import_csv_data->import_csv_data_parsed_rows/$import_csv_data->import_csv_data_total_rows * 100) }}%">
                                                {{ $import_csv_data->import_csv_data_parsed_rows . ' / ' . $import_csv_data->import_csv_data_total_rows }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $import_csv_data->updated_at->diffForHumans() }}</td>
                                    <td>
                                        @if($import_csv_data->import_csv_data_for_model == \App\ImportCsvData::IMPORT_CSV_FOR_MODEL_LISTING)
                                            {{ __('importer_csv.csv-for-model-listing') }}
                                        @endif
                                    </td>
                                    <td>

                                        @if($import_csv_data->import_csv_data_parse_status != \App\ImportCsvData::IMPORT_CSV_STATUS_ALL_PARSED)
                                            <a href="{{ route('admin.importer.csv.upload.data.edit', ['import_csv_data' => $import_csv_data->id]) }}" class="btn btn-primary btn-sm text-white rounded">
                                                {{ __('importer_csv.parse') }}
                                            </a>
                                        @endif

                                        <a class="btn btn-danger btn-sm text-white rounded" href="#" data-toggle="modal" data-target="#deleteModal_{{ $import_csv_data->id }}">
                                            {{ __('importer_csv.delete-file') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {{ $all_import_csv_data->links() }}
                </div>
            </div>

        </div>
    </div>

    @foreach($all_import_csv_data as $all_import_csv_data_key => $import_csv_data)
        <!-- Modal -->
        <div class="modal fade" id="deleteModal_{{ $import_csv_data->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModal_{{ $import_csv_data->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __('backend.shared.delete-message', ['record_type' => __('importer_csv.csv-file'), 'record_name' => $import_csv_data->import_csv_data_filename]) }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <form action="{{ route('admin.importer.csv.upload.data.destroy', ['import_csv_data' => $import_csv_data->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('importer_csv.delete-file') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('scripts')
@endsection
