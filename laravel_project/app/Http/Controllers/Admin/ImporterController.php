<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\City;
use App\Country;
use App\CustomField;
use App\Http\Controllers\Controller;
use App\ImportCsvData;
use App\ImportItemData;
use App\ImportItemFeatureData;
use App\Item;
use App\ItemFeature;
use App\Role;
use App\State;
use App\Subscription;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Exception;
use DateTimeZone;

class ImporterController extends Controller
{
    public function showUpload(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('importer_csv.seo.upload', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.importer.csv.upload');
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'import_csv_data_skip_first_row' => 'nullable|numeric|in:0,1',
            'import_csv_data_for_model' => 'required|numeric|in:1,2,3',
            'import_csv_data_file' => 'required|file|mimes:csv,txt|max:10280',
        ]);

        // prepare for file name
        $import_csv_data_skip_first_row = empty($request->import_csv_data_skip_first_row) ? ImportCsvData::IMPORT_CSV_SKIP_FIRST_ROW_NO : ImportCsvData::IMPORT_CSV_SKIP_FIRST_ROW_YES;
        $import_csv_data_filename = uniqid() . '-' . $request->file('import_csv_data_file')->getClientOriginalName();
        $import_csv_data_for_model = $request->import_csv_data_for_model;
        $import_csv_data_sample = array();

        // save the csv file
        $request->file('import_csv_data_file')->storeAs('importer', $import_csv_data_filename);

        // Read a CSV file
        $handle = fopen(storage_path('app/importer/') . $import_csv_data_filename, "r");

        // Optionally, you can keep the number of the line where
        // the loop its currently iterating over
        $lineNumber = 0;

        // Iterate over every line of the file
        while ((($raw_string = fgets($handle)) !== false)) {

            // Parse the raw csv string: "1, a, b, c"
            $row = str_getcsv($raw_string);

            if(!empty($row[0]))
            {
                // Increase the current line
                $lineNumber++;

                if($lineNumber == 1 && $import_csv_data_skip_first_row == ImportCsvData::IMPORT_CSV_SKIP_FIRST_ROW_YES)
                {
                    continue;
                }

                if(count($import_csv_data_sample) < 3)
                {
                    // into an array: ['1', 'a', 'b', 'c']
                    // And do what you need to do with every line
                    //var_dump($row);
                    $import_csv_data_sample[] = $row;
                }
            }
        }
        fclose($handle);

        if($lineNumber == 0)
        {
            if (Storage::disk('local')->exists('importer/' . $import_csv_data_filename)) {
                Storage::disk('local')->delete('importer/' . $import_csv_data_filename);
            }

            \Session::flash('flash_message', __('importer_csv.alert.upload-empty-file'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.importer.csv.upload.show');
        }

        $lineNumber = $import_csv_data_skip_first_row == ImportCsvData::IMPORT_CSV_SKIP_FIRST_ROW_YES ? $lineNumber-1 : $lineNumber;

        $new_import_csv_data = New ImportCsvData(array(
            'import_csv_data_filename' => $import_csv_data_filename,
            'import_csv_data_sample' => json_encode($import_csv_data_sample, JSON_INVALID_UTF8_IGNORE),
            'import_csv_data_skip_first_row' => $import_csv_data_skip_first_row,
            'import_csv_data_total_rows' => $lineNumber,
            'import_csv_data_parse_status' => ImportCsvData::IMPORT_CSV_STATUS_NOT_PARSED,
            'import_csv_data_for_model' => $import_csv_data_for_model,
        ));
        $new_import_csv_data->save();

        \Session::flash('flash_message', __('importer_csv.alert.upload-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.importer.csv.upload.data.edit',
            ['import_csv_data' => $new_import_csv_data]);
    }

    public function indexCsvData(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('importer_csv.seo.csv-data-index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_import_csv_data_query = ImportCsvData::orderBy('updated_at', 'DESC');
        $all_import_csv_data_count = $all_import_csv_data_query->count();
        $all_import_csv_data = $all_import_csv_data_query->paginate(10);


        return response()->view('backend.admin.importer.csv.data.index',
            compact('all_import_csv_data_count', 'all_import_csv_data'));
    }

    public function editCsvData(Request $request, ImportCsvData $import_csv_data)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('importer_csv.seo.csv-data-edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        if($import_csv_data->import_csv_data_parse_status == ImportCsvData::IMPORT_CSV_STATUS_ALL_PARSED)
        {
            \Session::flash('flash_message', __('importer_csv.alert.fully-parsed'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.importer.csv.upload.data.index');
        }
        else
        {
            $import_csv_data_sample = json_decode($import_csv_data->import_csv_data_sample, true);
            $import_csv_data_sample_header = $import_csv_data_sample[0];

            /**
             * Start initial available listing custom fields for column type
             */
            $available_custom_fields_ids = array();
            $categories = Category::all();
            foreach($categories as $categories_key => $category)
            {
                $category_custom_fields = $category->allCustomFields()->get();
                foreach($category_custom_fields as $category_custom_fields_key => $category_custom_field)
                {
                    if(!in_array($category_custom_field->id, $available_custom_fields_ids))
                    {
                        $available_custom_fields_ids[] = $category_custom_field->id;
                    }
                }
            }
            /**
             * End initial available listing custom fields for column type
             */

            return response()->view('backend.admin.importer.csv.data.edit',
                compact('import_csv_data', 'import_csv_data_sample', 'import_csv_data_sample_header',
                        'available_custom_fields_ids'));
        }
    }

    public function ajaxParseCsvData(Request $request, ImportCsvData $import_csv_data)
    {
        if($import_csv_data->import_csv_data_parse_status == ImportCsvData::IMPORT_CSV_STATUS_ALL_PARSED)
        {
            return response()->json(['error' => __('importer_csv.alert.fully-parsed')]);
        }

        $parsed_row_count = $import_csv_data->import_csv_data_parsed_rows;
        $start_row_at = $parsed_row_count + 1;

        // get the csv data fields of selector
        $csv_data_columns = $request->csv_data_columns;

        // get mark up text
        $import_item_data_markup = $request->import_item_data_markup;

        // Read a CSV file
        $handle = fopen(storage_path('app/importer/') . $import_csv_data->import_csv_data_filename, "r");

        // Optionally, you can keep the number of the line where
        // the loop its currently iterating over
        $lineNumber = 0;

        // Iterate over every line of the file
        while ((($raw_string = fgets($handle)) !== false)) {

            // Parse the raw csv string: "1, a, b, c"
            $row = str_getcsv($raw_string);

            if(!empty($row[0]))
            {
                // Increase the current line
                $lineNumber++;

                if($lineNumber == 1 && $import_csv_data->import_csv_data_skip_first_row == ImportCsvData::IMPORT_CSV_SKIP_FIRST_ROW_YES)
                {
                    continue;
                }

                if($lineNumber >= $start_row_at)
                {
                    $import_item_data_columns = ImportItemData::DATA_COLUMNS;
                    $new_import_item_data = new ImportItemData();

                    $new_import_item_feature_data = array();

                    foreach($row as $key => $row_value)
                    {
                        $row_value = empty($row_value) ? null : $row_value;

                        $selected_column = $csv_data_columns[$key];

                        if($selected_column == ImportItemData::DATA_COLUMNS_DO_NOT_PARSE)
                        {
                            continue;
                        }
                        elseif(starts_with($selected_column, 'custom_field_'))
                        {
                            $custom_field_id = intval(str_replace('custom_field_', '', $selected_column));

                            $custom_field_exist = CustomField::find($custom_field_id);

                            if($custom_field_exist)
                            {
                                $new_import_item_feature_data[] = array(
                                    'import_item_feature_data_custom_field_id' => $custom_field_exist->id,
                                    'import_item_feature_data_item_feature_value' => $row_value,
                                );
                            }
                        }
                        else
                        {
                            $import_item_data_column = $import_item_data_columns[$selected_column];

                            if($import_item_data_column == 'import_item_data_item_image_galleries')
                            {
                                $import_item_data_item_image_galleries_str = '';
                                $import_item_data_item_image_galleries_array = preg_split('/\s+/', $row_value);

                                foreach($import_item_data_item_image_galleries_array as $import_item_data_item_image_galleries_array_key => $import_item_data_item_image_galleries_line)
                                {
                                    $import_item_data_item_image_galleries_str .= trim($import_item_data_item_image_galleries_line) . "\n";
                                }

                                $new_import_item_data->$import_item_data_column = $import_item_data_item_image_galleries_str;
                            }
                            elseif($import_item_data_column == 'import_item_data_item_hours')
                            {
                                $import_item_data_item_image_hours_str = '';
                                $import_item_data_item_image_hours_array = preg_split('/\s+/', $row_value);

                                foreach($import_item_data_item_image_hours_array as $import_item_data_item_image_hours_array_key => $import_item_data_item_image_hours_line)
                                {
                                    $import_item_data_item_image_hours_str .= trim($import_item_data_item_image_hours_line) . "\n";
                                }

                                $new_import_item_data->$import_item_data_column = $import_item_data_item_image_hours_str;
                            }
                            elseif($import_item_data_column == 'import_item_data_item_image_hour_exceptions')
                            {
                                $import_item_data_item_image_hour_exceptions_str = '';
                                $import_item_data_item_image_hour_exceptions_array = preg_split('/\s+/', $row_value);

                                foreach($import_item_data_item_image_hour_exceptions_array as $import_item_data_item_image_hour_exceptions_array_key => $import_item_data_item_image_hour_exceptions_line)
                                {
                                    $import_item_data_item_image_hour_exceptions_str .= trim($import_item_data_item_image_hour_exceptions_line) . "\n";
                                }

                                $new_import_item_data->$import_item_data_column = $import_item_data_item_image_hour_exceptions_str;
                            }
                            else
                            {
                                $new_import_item_data->$import_item_data_column = $row_value;
                            }
                        }
                    }

                    $new_import_item_data->import_item_data_markup = $import_item_data_markup;
                    $new_import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_NOT_PROCESSED;
                    $new_import_item_data->import_item_data_source = ImportItemData::SOURCE_CSV;
                    $new_import_item_data->save();

                    // saving item features
                    if(count($new_import_item_feature_data) > 0)
                    {
                        foreach($new_import_item_feature_data as $new_import_item_feature_data_key => $an_import_item_feature_data)
                        {
                            $create_import_item_feature_data = new ImportItemFeatureData(array(
                                'import_item_feature_data_custom_field_id' => $an_import_item_feature_data['import_item_feature_data_custom_field_id'],
                                'import_item_feature_data_item_feature_value' => $an_import_item_feature_data['import_item_feature_data_item_feature_value'],
                            ));

                            $new_import_item_data->importItemFeatureData()->save($create_import_item_feature_data);
                        }
                    }

                    // update the parsed row count of $import_csv_data
                    $parsed_row_count++;
                    $import_csv_data->import_csv_data_parsed_rows = $parsed_row_count;
                    if($parsed_row_count >= $import_csv_data->import_csv_data_total_rows)
                    {
                        $import_csv_data->import_csv_data_parse_status = ImportCsvData::IMPORT_CSV_STATUS_ALL_PARSED;
                    }
                    $import_csv_data->save();
                }
            }
        }
        fclose($handle);

        return response()->json(['success' => __('importer_csv.alert.parsed-success')]);
    }

    public function ajaxParseProgressCsvData(Request $request, ImportCsvData $import_csv_data)
    {
        $message = __('importer_csv.parsed-percentage',
            ['parsed_count' => $import_csv_data->import_csv_data_parsed_rows, 'total_count' => $import_csv_data->import_csv_data_total_rows]);

        $end = 0;
        if($import_csv_data->import_csv_data_parse_status == ImportCsvData::IMPORT_CSV_STATUS_ALL_PARSED)
        {
            $end = 1;
        }

        $progress_percent = strval(intval($import_csv_data->import_csv_data_parsed_rows/$import_csv_data->import_csv_data_total_rows * 100));

        return response()->json(['progress' => $message, 'end' => $end, 'progress_percent' => $progress_percent]);
    }

    public function destroyCsvData(Request $request, ImportCsvData $import_csv_data)
    {
        if (Storage::disk('local')->exists('importer/' . $import_csv_data->import_csv_data_filename)) {
            Storage::disk('local')->delete('importer/' . $import_csv_data->import_csv_data_filename);
        }

        $import_csv_data->delete();

        \Session::flash('flash_message', __('importer_csv.alert.csv-file-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.importer.csv.upload.data.index');
    }


    public function indexItemData(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('importer_csv.seo.item-index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $import_item_data_process_status = $request->import_item_data_process_status;
        $selected_import_item_data_markup = $request->selected_import_item_data_markup;
        $order_by = $request->order_by;
        $count_per_page = $request->count_per_page;

        if(empty($import_item_data_process_status))
        {
            $import_item_data_process_status = array(ImportItemData::PROCESS_STATUS_NOT_PROCESSED, ImportItemData::PROCESS_STATUS_PROCESSED_ERROR);
        }
        if(empty($order_by))
        {
            $order_by = ImportItemData::ORDER_BY_ITEM_NEWEST_PARSED;
        }
        if(empty($count_per_page))
        {
            $count_per_page = ImportItemData::COUNT_PER_PAGE_10;
        }

        $all_import_item_data_query = ImportItemData::query();

        // where import_item_data_process_status
        $all_import_item_data_query->whereIn('import_item_data_process_status', $import_item_data_process_status);

        // markup
        if(!empty($selected_import_item_data_markup))
        {
            $all_import_item_data_query->where('import_item_data_markup', $selected_import_item_data_markup);
        }

        // orderBy
        if($order_by == ImportItemData::ORDER_BY_ITEM_NEWEST_PROCESSED)
        {
            $all_import_item_data_query->orderBy('updated_at', 'DESC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_OLDEST_PROCESSED)
        {
            $all_import_item_data_query->orderBy('updated_at', 'ASC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_NEWEST_PARSED)
        {
            $all_import_item_data_query->orderBy('created_at', 'DESC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_OLDEST_PARSED)
        {
            $all_import_item_data_query->orderBy('created_at', 'ASC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_TITLE_A_Z)
        {
            $all_import_item_data_query->orderBy('import_item_data_item_title', 'ASC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_TITLE_Z_A)
        {
            $all_import_item_data_query->orderBy('import_item_data_item_title', 'DESC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_CITY_A_Z)
        {
            $all_import_item_data_query->orderBy('import_item_data_city', 'ASC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_CITY_Z_A)
        {
            $all_import_item_data_query->orderBy('import_item_data_city', 'DESC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_STATE_A_Z)
        {
            $all_import_item_data_query->orderBy('import_item_data_state', 'ASC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_STATE_Z_A)
        {
            $all_import_item_data_query->orderBy('import_item_data_state', 'DESC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_COUNTRY_A_Z)
        {
            $all_import_item_data_query->orderBy('import_item_data_country', 'ASC');
        }
        elseif($order_by == ImportItemData::ORDER_BY_ITEM_COUNTRY_Z_A)
        {
            $all_import_item_data_query->orderBy('import_item_data_country', 'DESC');
        }

        $all_import_item_data_count = $all_import_item_data_query->count();
        $all_import_item_data = $all_import_item_data_query->paginate($count_per_page);

        /**
         * Start initial categories
         */
        $all_categories = new Category();
        $all_categories = $all_categories->getPrintableCategoriesNoDash();
        /**
         * End initial categories
         */

        /**
         * Start initial users
         */
        $all_users = User::where('email_verified_at', '!=', null)
            ->where('role_id', Role::USER_ROLE_ID)
            ->where('user_suspended', User::USER_NOT_SUSPENDED)
            ->orderBy('name')
            ->get();

        $admin_user = Auth::user();
        /**
         * End initial users
         */

        /**
         * Start initial markup
         */
        $all_import_item_data_markup = ImportItemData::select('import_item_data_markup')
            ->orderBy('import_item_data_markup')->groupBy('import_item_data_markup')->get();
        /**
         * End initial markup
         */


        return response()->view('backend.admin.importer.item.index',
            compact('all_import_item_data_count', 'all_import_item_data', 'import_item_data_process_status',
                'order_by', 'all_categories', 'all_users', 'admin_user', 'count_per_page', 'all_import_item_data_markup',
                'selected_import_item_data_markup'));
    }

    public function editItemData(Request $request, ImportItemData $import_item_data)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('importer_csv.seo.item-edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Start initial categories
         */
        $all_categories = new Category();
        $all_categories = $all_categories->getPrintableCategoriesNoDash();
        /**
         * End initial categories
         */

        /**
         * Start initial users
         */
        $all_users = User::where('email_verified_at', '!=', null)
            ->where('role_id', Role::USER_ROLE_ID)
            ->where('user_suspended', User::USER_NOT_SUSPENDED)
            ->orderBy('name')
            ->get();

        $admin_user = Auth::user();
        /**
         * End initial users
         */

        /**
         * Start initial time zone selector
         */
        $time_zone_identifiers = DateTimeZone::listIdentifiers();
        /**
         * End initial time zone selector
         */

        $all_import_item_feature_data = $import_item_data->importItemFeatureData()->get();

        $imported_item = null;
        if($import_item_data->import_item_data_process_status == ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
        {
            // if imported success, we will verify the item_id and get it.
            $item_exist = Item::find($import_item_data->import_item_data_item_id);

            if($item_exist)
            {
                $imported_item = $item_exist;
            }
        }

        return response()->view('backend.admin.importer.item.edit',
            compact('import_item_data', 'all_categories', 'all_users', 'admin_user', 'imported_item',
                    'all_import_item_feature_data', 'time_zone_identifiers'));
    }

    public function updateItemData(Request $request, ImportItemData $import_item_data)
    {
        if($import_item_data->import_item_data_process_status == ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
        {
            \Session::flash('flash_message', __('importer_csv.alert.import-item-cannot-edit-success-processed'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.importer.item.data.edit',
                ['import_item_data' => $import_item_data]);
        }

        $request->validate([
            'import_item_data_item_title' => 'nullable|max:255',
            'import_item_data_item_slug' => 'nullable|max:255',
            'import_item_data_item_address' => 'nullable|max:255',
            'import_item_data_city' => 'nullable|max:255',
            'import_item_data_state' => 'nullable|max:255',
            'import_item_data_country' => 'nullable|max:255',
            'import_item_data_item_lat' => 'nullable|max:255',
            'import_item_data_item_lng' => 'nullable|max:255',
            'import_item_data_item_postal_code' => 'nullable|max:255',
            'import_item_data_item_description' => 'nullable|max:65535',
            'import_item_data_item_phone' => 'nullable|max:255',
            'import_item_data_item_website' => 'nullable|url|max:255',
            'import_item_data_item_social_facebook' => 'nullable|url|max:255',
            'import_item_data_item_social_twitter' => 'nullable|url|max:255',
            'import_item_data_item_social_linkedin' => 'nullable|url|max:255',
            'import_item_data_item_youtube_id' => 'nullable|max:255',
            'import_item_data_item_image' => 'nullable|url|max:65535',
            'import_item_data_item_image_galleries' => 'nullable|max:65535',
            'import_item_data_item_hour_time_zone' => 'nullable|max:255',
            'import_item_data_item_hour_show_hours' => 'nullable|numeric|in:1,2',
            'import_item_data_item_hours' => 'nullable|max:65535',
            'import_item_data_item_image_hour_exceptions' => 'nullable|max:65535',
            'import_item_data_item_social_instagram' => 'nullable|max:255',
            'import_item_data_item_social_whatsapp' => 'nullable|numeric',
        ]);

        /**
         * Start validate URLs for import_item_data_item_image_galleries
         */
        $import_item_data_item_image_galleries = $request->import_item_data_item_image_galleries;

        $validate_error = array();

        // iterate the $import_item_data_item_image_galleries
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $import_item_data_item_image_galleries) as $import_item_data_item_image_galleries_line)
        {
            if(!empty($import_item_data_item_image_galleries_line))
            {
                if(!starts_with($import_item_data_item_image_galleries_line, "http://") && !starts_with($import_item_data_item_image_galleries_line, "https://"))
                {
                    $validate_error['import_item_data_item_image_galleries'] = __('sitemap_import.alert.url-starts-with');
                    break;
                }
            }
        }
        /**
         * End validate URLs for import_item_data_item_image_galleries
         */

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $import_item_data->import_item_data_item_title = $request->import_item_data_item_title;
        $import_item_data->import_item_data_item_slug = $request->import_item_data_item_slug;
        $import_item_data->import_item_data_item_address = $request->import_item_data_item_address;
        $import_item_data->import_item_data_city = $request->import_item_data_city;
        $import_item_data->import_item_data_state = $request->import_item_data_state;
        $import_item_data->import_item_data_country = $request->import_item_data_country;
        $import_item_data->import_item_data_item_lat = $request->import_item_data_item_lat;
        $import_item_data->import_item_data_item_lng = $request->import_item_data_item_lng;
        $import_item_data->import_item_data_item_postal_code = $request->import_item_data_item_postal_code;
        $import_item_data->import_item_data_item_description = $request->import_item_data_item_description;
        $import_item_data->import_item_data_item_phone = $request->import_item_data_item_phone;
        $import_item_data->import_item_data_item_website = $request->import_item_data_item_website;
        $import_item_data->import_item_data_item_social_facebook = $request->import_item_data_item_social_facebook;
        $import_item_data->import_item_data_item_social_twitter = $request->import_item_data_item_social_twitter;
        $import_item_data->import_item_data_item_social_linkedin = $request->import_item_data_item_social_linkedin;
        $import_item_data->import_item_data_item_youtube_id = $request->import_item_data_item_youtube_id;

        $import_item_data->import_item_data_item_image = $request->import_item_data_item_image;
        $import_item_data->import_item_data_item_image_galleries = $import_item_data_item_image_galleries;

        $import_item_data->import_item_data_item_hour_time_zone = $request->import_item_data_item_hour_time_zone;
        $import_item_data->import_item_data_item_hour_show_hours = $request->import_item_data_item_hour_show_hours;
        $import_item_data->import_item_data_item_hours = $request->import_item_data_item_hours;
        $import_item_data->import_item_data_item_image_hour_exceptions = $request->import_item_data_item_image_hour_exceptions;

        $import_item_data->import_item_data_item_social_instagram = strtolower($request->import_item_data_item_social_instagram);
        $import_item_data->import_item_data_item_social_whatsapp = ltrim($request->import_item_data_item_social_whatsapp, '0');

        $import_item_data->save();

        /**
         * Start saving import_item_feature_data
         */
        $all_import_item_feature_data = $import_item_data->importItemFeatureData()->get();
        foreach($all_import_item_feature_data as $all_import_item_feature_data_key => $an_import_item_feature_data)
        {
            $import_item_feature_data_item_feature_value = $request->get('custom_field_' . $an_import_item_feature_data->id);
            $an_import_item_feature_data->import_item_feature_data_item_feature_value = $import_item_feature_data_item_feature_value;
            $an_import_item_feature_data->save();
        }
        /**
         * End saving import_item_feature_data
         */

        \Session::flash('flash_message', __('importer_csv.alert.import-item-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.importer.item.data.edit',
            ['import_item_data' => $import_item_data]);
    }

    public function destroyItemData(Request $request, ImportItemData $import_item_data)
    {
        $import_item_data->deleteImportItemData();

        \Session::flash('flash_message', __('importer_csv.alert.import-item-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.importer.item.data.index');
    }

    public function ajaxDestroyItemData(Request $request, ImportItemData $import_item_data)
    {
        $import_item_data->deleteImportItemData();

        return response()->json([
            'message' => __('importer_csv.alert.import-item-deleted'),
        ]);
    }

    public function importItemData(Request $request, ImportItemData $import_item_data)
    {
        if($import_item_data->import_item_data_process_status == ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
        {
            \Session::flash('flash_message', __('importer_csv.import-errors.import-item-cannot-process-success-processed'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.importer.item.data.edit',
                ['import_item_data' => $import_item_data]);
        }

        $request->validate([
            'category' => 'required',
            'category.*' => 'numeric',
            'user_id' => 'required',
            'item_type' => 'required|numeric|in:1,2',
            'item_status' => 'required|numeric|in:1,2,3',
            'item_featured' => 'required|numeric|in:0,1',
        ]);

        $user_id = $request->user_id;

        if($user_id == ImportItemData::IMPORT_RANDOM_USER)
        {
            $subscription = new Subscription();
            $active_user_ids = $subscription->getActiveUserIds();
            $user_id = $active_user_ids[array_rand($active_user_ids)];
        }

        $select_categories = $request->category;
        $item_type = $request->item_type;
        $item_status = $request->item_status;
        $item_featured = $request->item_featured;

        $process_result = $this->processItemImport(
                                    $import_item_data,
                                    $user_id,
                                    $item_status,
                                    $item_featured,
                                    $select_categories,
                                    $item_type
                                );

        if($process_result)
        {
            \Session::flash('flash_message', __('importer_csv.alert.import-process-success'));
            \Session::flash('flash_type', 'success');
        }
        else
        {
            \Session::flash('flash_message', __('importer_csv.alert.import-process-error'));
            \Session::flash('flash_type', 'danger');
        }

        return redirect()->route('admin.importer.item.data.edit',
            ['import_item_data' => $import_item_data]);
    }

    public function ajaxImportItemData(Request $request, ImportItemData $import_item_data)
    {
        $user_id = $request->user_id;
        $select_categories = $request->category;
        $item_type = $request->item_type;
        $item_status = $request->item_status;
        $item_featured = $request->item_featured;

        if($user_id == ImportItemData::IMPORT_RANDOM_USER)
        {
            $subscription = new Subscription();
            $active_user_ids = $subscription->getActiveUserIds();
            $user_id = $active_user_ids[array_rand($active_user_ids)];
        }

        $process_result = $this->processItemImport(
            $import_item_data,
            $user_id,
            $item_status,
            $item_featured,
            $select_categories,
            $item_type
        );

        if($process_result)
        {
            return response()->json([
                'status' => ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS,
                'message' => __('importer_csv.alert.import-process-success'),
            ]);
        }
        else
        {
            return response()->json([
                'status' => ImportItemData::PROCESS_STATUS_PROCESSED_ERROR,
                'message' => __('importer_csv.alert.import-process-error'),
            ]);
        }
    }

    public function ajaxImportAllItemData(Request $request)
    {
        $user_id = $request->user_id;
        $select_categories = $request->category;
        $item_status = $request->item_status;
        $item_featured = $request->item_featured;

        $all_import_item_data = ImportItemData::where('import_item_data_process_status', '!=', ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
            ->orderBy('created_at')->get();

        foreach($all_import_item_data as $all_import_item_data_key => $import_item_data)
        {
            $process_result = $this->processItemImport(
                $import_item_data,
                $user_id,
                $item_status,
                $item_featured,
                $select_categories
            );
        }

        return response()->json([
            'message' => __('importer_csv.alert.import-all-process-completed'),
        ]);
    }


    /**
     * process import of an record in import_item_data table to listing
     *
     * @param ImportItemData $import_item_data
     * @param int $user_id
     * @param int $item_status
     * @param int $item_featured
     * @param array $select_categories
     * @return bool
     */
    private function processItemImport(ImportItemData $import_item_data,
                                       int $user_id,
                                       int $item_status,
                                       int $item_featured,
                                       array $select_categories,
                                       int $item_type)
    {
        $new_item = null;

        try
        {
            // validate import_item_data_process_status
            if($import_item_data->import_item_data_process_status == ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS)
            {
                $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.import-item-cannot-process-success-processed');
                $import_item_data->save();
                return false;
            }

            // validate item_type
            if($item_type != Item::ITEM_TYPE_REGULAR && $item_type != Item::ITEM_TYPE_ONLINE)
            {
                $import_item_data->import_item_data_process_error_log = __('theme_directory_hub.importer.import-error.item-type-not-exist');
                $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                $import_item_data->save();
                return false;
            }

            // validate item_title
            $item_title = trim($import_item_data->import_item_data_item_title);
            if(empty($item_title))
            {
                $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.item-title-required');
                $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                $import_item_data->save();
                return false;
            }

            // validate item_description
            $item_description = trim($import_item_data->import_item_data_item_description);
            $item_description = empty($item_description) ? null : $item_description;

            // validate item_postal_code
            $item_postal_code = null;
            if($item_type == Item::ITEM_TYPE_REGULAR)
            {
                $item_postal_code = trim($import_item_data->import_item_data_item_postal_code);
            }

            // validate user
            $item_user = User::find($user_id);
            if(!$item_user)
            {
                $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.user-not-exist');
                $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                $import_item_data->save();
                return false;
            }

            // validate item_status
            if($item_status != Item::ITEM_SUBMITTED && $item_status != Item::ITEM_PUBLISHED && $item_status != Item::ITEM_SUSPENDED)
            {
                $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.item-status-not-exist');
                $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                $import_item_data->save();
                return false;
            }

            // validate item_featured
            if($item_featured != Item::ITEM_FEATURED && $item_featured != Item::ITEM_NOT_FEATURED)
            {
                $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.item-featured-not-exist');
                $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                $import_item_data->save();
                return false;
            }

            // validate categories array, remove out any non-exist categories
            $select_categories_array = array();
            $item_categories_string = "";
            foreach($select_categories as $select_categories_key => $select_category)
            {
                $select_category = Category::find($select_category);
                if($select_category)
                {
                    $select_categories_array[] = $select_category->id;
                    $item_categories_string = $item_categories_string . " " . $select_category->category_name;
                }
            }
            if(count($select_categories_array) == 0)
            {
                $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.categories-required');
                $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                $import_item_data->save();
                return false;
            }

            // validate country
            $country_id = null;
            if($item_type == Item::ITEM_TYPE_REGULAR)
            {
                $country_origin = trim($import_item_data->import_item_data_country);
                $country_find = true;

                /**
                 * Start guess country by country name
                 */
                $country_exist = Country::where('country_name', $country_origin)->get();
                if($country_exist->count() == 0)
                {
                    $country_lowercase = strtolower($country_origin);
                    $country_exist = Country::where('country_name', $country_lowercase)->get();

                    if($country_exist->count() == 0)
                    {
                        $country_ucfirst = ucfirst($country_lowercase);
                        $country_exist = Country::where('country_name', $country_ucfirst)->get();

                        if($country_exist->count() == 0)
                        {
                            $country_ucwords = ucwords($country_lowercase);
                            $country_exist = Country::where('country_name', $country_ucwords)->get();

                            if($country_exist->count() == 0)
                            {
                                $country_uppercase = strtoupper($country_lowercase);
                                $country_exist = Country::where('country_name', $country_uppercase)->get();

                                if($country_exist->count() == 0)
                                {
                                    $country_find = false;
                                }
                            }
                        }
                    }
                }
                /**
                 * End guess country by country name
                 */

                /**
                 * Start guess country by country abbr
                 */
                if(!$country_find)
                {
                    $country_find = true;

                    $country_exist = Country::where('country_abbr', $country_origin)->get();
                    if($country_exist->count() == 0)
                    {
                        $country_lowercase = strtolower($country_origin);
                        $country_exist = Country::where('country_abbr', $country_lowercase)->get();

                        if($country_exist->count() == 0)
                        {
                            $country_ucfirst = ucfirst($country_lowercase);
                            $country_exist = Country::where('country_abbr', $country_ucfirst)->get();

                            if($country_exist->count() == 0)
                            {
                                $country_ucwords = ucwords($country_lowercase);
                                $country_exist = Country::where('country_abbr', $country_ucwords)->get();

                                if($country_exist->count() == 0)
                                {
                                    $country_uppercase = strtoupper($country_lowercase);
                                    $country_exist = Country::where('country_abbr', $country_uppercase)->get();

                                    if($country_exist->count() == 0)
                                    {
                                        $country_find = false;
                                    }
                                }
                            }
                        }
                    }

                }
                /**
                 * End guess country by country abbr
                 */

                if(!$country_find)
                {
                    $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.country-not-exist');
                    $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                    $import_item_data->save();
                    return false;
                }

                $country_exist = $country_exist->first();
                $country_id = $country_exist->id;
            }


            // validate state
            $state_id = null;
            if($item_type == Item::ITEM_TYPE_REGULAR)
            {
                $state_origin = trim($import_item_data->import_item_data_state);
                $state_find = true;

                /**
                 * Start guess state by state name
                 */
                $state_exist = State::where('state_name', $state_origin)->where('country_id', $country_id)->get();
                if($state_exist->count() == 0)
                {
                    $state_lowercase = strtolower($state_origin);
                    $state_exist = State::where('state_name', $state_lowercase)->where('country_id', $country_id)->get();

                    if($state_exist->count() == 0)
                    {
                        $state_ucfirst = ucfirst($state_lowercase);
                        $state_exist = State::where('state_name', $state_ucfirst)->where('country_id', $country_id)->get();

                        if($state_exist->count() == 0)
                        {
                            $state_ucwords = ucwords($state_lowercase);
                            $state_exist = State::where('state_name', $state_ucwords)->where('country_id', $country_id)->get();

                            if($state_exist->count() == 0)
                            {
                                $state_uppercase = strtoupper($state_lowercase);
                                $state_exist = State::where('state_name', $state_uppercase)->where('country_id', $country_id)->get();

                                if($state_exist->count() == 0)
                                {
                                    $state_find = false;
                                }
                            }
                        }
                    }
                }
                /**
                 * End guess state by state name
                 */

                /**
                 * Start guess state by state abbr
                 */
                if(!$state_find)
                {
                    $state_find = true;

                    $state_exist = State::where('state_abbr', $state_origin)->where('country_id', $country_id)->get();
                    if($state_exist->count() == 0)
                    {
                        $state_lowercase = strtolower($state_origin);
                        $state_exist = State::where('state_abbr', $state_lowercase)->where('country_id', $country_id)->get();

                        if($state_exist->count() == 0)
                        {
                            $state_ucfirst = ucfirst($state_lowercase);
                            $state_exist = State::where('state_abbr', $state_ucfirst)->where('country_id', $country_id)->get();

                            if($state_exist->count() == 0)
                            {
                                $state_ucwords = ucwords($state_lowercase);
                                $state_exist = State::where('state_abbr', $state_ucwords)->where('country_id', $country_id)->get();

                                if($state_exist->count() == 0)
                                {
                                    $state_uppercase = strtoupper($state_lowercase);
                                    $state_exist = State::where('state_abbr', $state_uppercase)->where('country_id', $country_id)->get();

                                    if($state_exist->count() == 0)
                                    {
                                        $state_find = false;
                                    }
                                }
                            }
                        }
                    }
                }
                /**
                 * End guess state by state abbr
                 */

                if(!$state_find)
                {
                    $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.state-not-exist');
                    $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                    $import_item_data->save();
                    return false;
                }

                $state_exist = $state_exist->first();
                $state_id = $state_exist->id;
            }

            // validate city
            $city_id = null;
            if($item_type == Item::ITEM_TYPE_REGULAR)
            {
                $city_origin = trim($import_item_data->import_item_data_city);
                $city_exist = City::where('city_name', $city_origin)->where('state_id', $state_id)->get();
                if($city_exist->count() == 0)
                {
                    $city_lowercase = strtolower($city_origin);
                    $city_exist = City::where('city_name', $city_lowercase)->where('state_id', $state_id)->get();

                    if($city_exist->count() == 0)
                    {
                        $city_ucfirst = ucfirst($city_lowercase);
                        $city_exist = City::where('city_name', $city_ucfirst)->where('state_id', $state_id)->get();

                        if($city_exist->count() == 0)
                        {
                            $city_ucwords = ucwords($city_lowercase);
                            $city_exist = City::where('city_name', $city_ucwords)->where('state_id', $state_id)->get();

                            if($city_exist->count() == 0)
                            {
                                $city_uppercase = strtoupper($city_lowercase);
                                $city_exist = City::where('city_name', $city_uppercase)->where('state_id', $state_id)->get();

                                if($city_exist->count() == 0)
                                {
                                    $import_item_data->import_item_data_process_error_log = __('importer_csv.import-errors.city-not-exist');
                                    $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                                    $import_item_data->save();
                                    return false;
                                }
                            }
                        }
                    }
                }
                $city_exist = $city_exist->first();
                $city_id = $city_exist->id;
            }

            // validate item_hour_time_zone
            $item_hour_time_zone = $import_item_data->import_item_data_item_hour_time_zone;
            if(!empty($item_hour_time_zone))
            {
                $time_zone_identifiers = DateTimeZone::listIdentifiers();
                if(!in_array($item_hour_time_zone, $time_zone_identifiers))
                {
                    $import_item_data->import_item_data_process_error_log = __('importer_csv_hours.import-errors.timezone-not-exist');
                    $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                    $import_item_data->save();
                    return false;
                }
            }

            // prepare item_hour_show_hours
            $item_hour_show_hours = $import_item_data->import_item_data_item_hour_show_hours;
            if(!empty($item_hour_show_hours))
            {
                $item_hour_show_hours = $item_hour_show_hours == Item::ITEM_HOUR_SHOW ? Item::ITEM_HOUR_SHOW : Item::ITEM_HOUR_NOT_SHOW;
            }

            // prepare item_slug
            $item_slug = str_slug($item_title);
            if(empty($item_slug))
            {
                $item_slug = uniqid();
            }
            else
            {
                $item_slug_exist = Item::where('item_slug', $item_slug)->get();
                if($item_slug_exist->count() > 0)
                {
                    $item_slug = $item_slug . '-' . uniqid();
                }
            }

            $item_address = null;
            $item_address_hide = Item::ITEM_ADDR_HIDE;
            $item_lat = null;
            $item_lng = null;

            if($item_type == Item::ITEM_TYPE_REGULAR)
            {
                $item_address = trim($import_item_data->import_item_data_item_address);

                // prepare item_address_hide
                $item_address_hide = Item::ITEM_ADDR_NOT_HIDE;
                if(empty($item_address))
                {
                    $item_address_hide = Item::ITEM_ADDR_HIDE;
                }

                // prepare item_lat & item_lng
                $item_lat = trim($import_item_data->import_item_data_item_lat);
                $item_lng = trim($import_item_data->import_item_data_item_lng);

                if(empty($item_lat))
                {
                    $item_lat = $city_exist->city_lat;
                }

                if(empty($item_lng))
                {
                    $item_lng = $city_exist->city_lng;
                }
            }

            // prepare website, facebook, twitter, linkedin
            $import_item_data_item_website = trim($import_item_data->import_item_data_item_website);
            $import_item_data_item_social_facebook = trim($import_item_data->import_item_data_item_social_facebook);
            $import_item_data_item_social_twitter = trim($import_item_data->import_item_data_item_social_twitter);
            $import_item_data_item_social_linkedin = trim($import_item_data->import_item_data_item_social_linkedin);
            $import_item_data_item_social_instagram = trim($import_item_data->import_item_data_item_social_instagram);
            $import_item_data_item_social_whatsapp = trim($import_item_data->import_item_data_item_social_whatsapp);

            $item_website = empty($import_item_data_item_website) ? null : strtolower($import_item_data_item_website);
            $item_social_facebook = empty($import_item_data_item_social_facebook) ? null : strtolower($import_item_data_item_social_facebook);
            $item_social_twitter = empty($import_item_data_item_social_twitter) ? null : strtolower($import_item_data_item_social_twitter);
            $item_social_linkedin = empty($import_item_data_item_social_linkedin) ? null : strtolower($import_item_data_item_social_linkedin);
            $item_social_instagram = empty($import_item_data_item_social_instagram) ? null : strtolower($import_item_data_item_social_instagram);
            $item_social_whatsapp = empty($import_item_data_item_social_whatsapp) ? null : $import_item_data_item_social_whatsapp;

            if(!empty($item_website))
            {
                if(!starts_with($item_website, "http://") && !starts_with($item_website, "https://"))
                {
                    $item_website = "https://" . $item_website;
                }
            }
            if(!empty($item_social_facebook))
            {
                if(!starts_with($item_social_facebook, "http://") && !starts_with($item_social_facebook, "https://"))
                {
                    $item_social_facebook = "https://" . $item_social_facebook;
                }
            }
            if(!empty($item_social_twitter))
            {
                if(!starts_with($item_social_twitter, "http://") && !starts_with($item_social_twitter, "https://"))
                {
                    $item_social_twitter = "https://" . $item_social_twitter;
                }
            }
            if(!empty($item_social_linkedin))
            {
                if(!starts_with($item_social_linkedin, "http://") && !starts_with($item_social_linkedin, "https://"))
                {
                    $item_social_linkedin = "https://" . $item_social_linkedin;
                }
            }
            if(!empty($item_social_whatsapp))
            {
                // validate whatsapp number to make sure only includes numbers
                $item_social_whatsapp = ltrim($item_social_whatsapp, '0');

                if(!ctype_digit($item_social_whatsapp))
                {
                    $import_item_data->import_item_data_process_error_log = __('item_whatsapp_instagram.import-error.whatsapp-error');
                    $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
                    $import_item_data->save();
                    return false;
                }
            }

            // prepare other data fields
            $import_item_data_item_phone = trim($import_item_data->import_item_data_item_phone);
            $import_item_data_item_youtube_id = trim($import_item_data->import_item_data_item_youtube_id);

            $item_phone = empty($import_item_data_item_phone) ? null : $import_item_data_item_phone;
            $item_youtube_id = empty($import_item_data_item_youtube_id) ? null : $import_item_data_item_youtube_id;

            // prepare item_location_str
            $item_location_str = null;
            if($item_type == Item::ITEM_TYPE_REGULAR)
            {
                $item_location_str = $city_exist->city_name . ' ' . $state_exist->state_name . ' ' . $country_exist->country_name . ' ' . $item_postal_code;
            }

            // start create a new item model and save the new record to database
            $new_item = new Item(array(
                'user_id' => $user_id,
                'item_status' => $item_status,
                'item_featured' => $item_featured,
                'item_featured_by_admin' => $item_featured == Item::ITEM_FEATURED ? Item::ITEM_FEATURED_BY_ADMIN : Item::ITEM_NOT_FEATURED_BY_ADMIN,
                'item_title' => $item_title,
                'item_slug' => $item_slug,
                'item_description' => $item_description,
                'item_address' => $item_address,
                'item_address_hide' => $item_address_hide,
                'city_id' => $city_id,
                'state_id' => $state_id,
                'country_id' => $country_id,
                'item_postal_code' => $item_postal_code,
                'item_lat' => $item_lat,
                'item_lng' => $item_lng,
                'item_youtube_id' => $item_youtube_id,
                'item_phone' => $item_phone,
                'item_website' => $item_website,
                'item_social_facebook' => $item_social_facebook,
                'item_social_twitter' => $item_social_twitter,
                'item_social_linkedin' => $item_social_linkedin,
                'item_social_instagram' => $item_social_instagram,
                'item_social_whatsapp' => $item_social_whatsapp,
                'item_categories_string' => $item_categories_string,
                'item_location_str' => $item_location_str,
                'item_type' => $item_type,
            ));
            $new_item->save();

            if(!empty($item_hour_time_zone))
            {
                $new_item->item_hour_time_zone = $item_hour_time_zone;
                $new_item->save();
            }

            if(!empty($item_hour_show_hours))
            {
                $new_item->item_hour_show_hours = $item_hour_show_hours;
                $new_item->save();
            }

            // sync the categories for the new item record
            $new_item->allCategories()->sync($select_categories_array);

            // start create the item features for this new item
            $category_custom_fields = new CustomField();
            $category_custom_fields = $category_custom_fields->getDistinctCustomFieldsByCategories($select_categories_array);

            if($category_custom_fields->count() > 0)
            {
                $item_features_string = "";

                foreach($category_custom_fields as $category_custom_fields_key => $custom_field)
                {
                    $import_item_feature_data_exist = $import_item_data->importItemFeatureData()
                        ->where('import_item_feature_data_custom_field_id', $custom_field->id)->get();

                    $item_feature_value = "";
                    if($import_item_feature_data_exist->count() > 0)
                    {
                        $item_feature_value = $import_item_feature_data_exist->first()->import_item_feature_data_item_feature_value;

                        $item_features_string .= $item_feature_value . ' ';
                    }

                    $new_item_feature = new ItemFeature(array(
                        'custom_field_id' => $custom_field->id,
                        'item_feature_value' => $item_feature_value,
                    ));

                    $created_item_feature = $new_item->features()->save($new_item_feature);
                }

                $new_item->item_features_string = $item_features_string;
                $new_item->save();
            }

            /**
             * Start process hours and exception hours
             */
            // hours
            $import_item_data_item_hours = $import_item_data->import_item_data_item_hours;

            foreach(preg_split("/((\r?\n)|(\r\n?))/", $import_item_data_item_hours) as $import_item_data_item_hours_line)
            {

                // The hour format should be day-of-week_hh:mm_hh:mm, for example, 1_08:00_23:00 means
                // open on Monday, and starts at 8 o'clock and close at 23 o'clock.
                $item_hour_record = explode('_', $import_item_data_item_hours_line);

                if(count($item_hour_record) == 3)
                {
                    $new_item->insertHours(intval($item_hour_record[0]), $item_hour_record[1], $item_hour_record[2]);
                }
            }

            // exception hours
            $import_item_data_item_image_hour_exceptions = $import_item_data->import_item_data_item_image_hour_exceptions;

            foreach(preg_split("/((\r?\n)|(\r\n?))/", $import_item_data_item_image_hour_exceptions) as $import_item_data_item_image_hour_exceptions_line)
            {
                // The hour exceptions format should be yyyy-mm-dd_hh:mm_hh:mm or yyyy-mm-dd,
                // for example, 2022-03-10_13:00_16:00 means opens on 2022-03-10 from 13 o'clock to 16 o'clock.
                // or, 2022-12-25 means close all day on 2022-12-25
                $item_hour_exception_record = explode('_', $import_item_data_item_image_hour_exceptions_line);

                if(count($item_hour_exception_record) == 1)
                {
                    $new_item->insertHourExceptions($item_hour_exception_record[0]);
                }
                elseif(count($item_hour_exception_record) == 3)
                {
                    $new_item->insertHourExceptions($item_hour_exception_record[0], $item_hour_exception_record[1], $item_hour_exception_record[2]);
                }
            }
            /**
             * End process hours and exception hours
             */

            /**
             * Start process feature image and gallery images
             */
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );

            // feature image
            $import_item_data_item_image = $import_item_data->import_item_data_item_image;

            if(!empty($import_item_data_item_image))
            {
                $import_item_data_item_image_content = base64_encode(file_get_contents($import_item_data_item_image, false, stream_context_create($arrContextOptions)));

                $currentDate = Carbon::now()->toDateString();

                $item_feature_image_name = $item_slug.'-'.$currentDate.'-'.uniqid().'.jpg';
                $item_feature_image_name_medium = $item_slug . '-' . $currentDate . '-' . uniqid() . '-medium.jpg';
                $item_feature_image_name_small = $item_slug . '-' . $currentDate . '-' . uniqid() . '-small.jpg';
                $item_feature_image_name_tiny = $item_slug . '-' . $currentDate . '-' . uniqid() . '-tiny.jpg';

                // blur feature image name
                $item_feature_image_name_blur = $item_slug . '-' . $currentDate . '-' . uniqid() . '-blur.jpg';

                if(!Storage::disk('public')->exists('item')){
                    Storage::disk('public')->makeDirectory('item');
                }

                // original size
                $item_feature_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$import_item_data_item_image_content)))
                    ->resize(800, 687, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $item_feature_image = $item_feature_image->stream('jpg', 70);
                Storage::disk('public')->put('item/'.$item_feature_image_name, $item_feature_image);

                // medium size
                $item_feature_image_medium = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$import_item_data_item_image_content)))
                    ->resize(350, 300, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $item_feature_image_medium = $item_feature_image_medium->stream('jpg', 70);
                Storage::disk('public')->put('item/'.$item_feature_image_name_medium, $item_feature_image_medium);

                // small size
                $item_feature_image_small = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$import_item_data_item_image_content)))
                    ->resize(230, 198, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $item_feature_image_small = $item_feature_image_small->stream('jpg', 70);
                Storage::disk('public')->put('item/'.$item_feature_image_name_small, $item_feature_image_small);

                // tiny size
                $item_feature_image_tiny = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$import_item_data_item_image_content)))
                    ->resize(160, 138, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $item_feature_image_tiny = $item_feature_image_tiny->stream('jpg', 70);
                Storage::disk('public')->put('item/'.$item_feature_image_name_tiny, $item_feature_image_tiny);

                // blur feature image
                $item_feature_image_blur = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$import_item_data_item_image_content)));
                $item_feature_image_blur->blur(50);
                $item_feature_image_blur->stream('jpg', 70);
                Storage::disk('public')->put('item/'.$item_feature_image_name_blur, $item_feature_image_blur);

                $new_item->item_image = $item_feature_image_name;
                $new_item->item_image_medium = $item_feature_image_name_medium;
                $new_item->item_image_small = $item_feature_image_name_small;
                $new_item->item_image_tiny = $item_feature_image_name_tiny;
                $new_item->item_image_blur = $item_feature_image_name_blur;
                $new_item->save();
            }

            // gallery images
            $import_item_data_item_image_galleries = $import_item_data->import_item_data_item_image_galleries;

            foreach(preg_split("/((\r?\n)|(\r\n?))/", $import_item_data_item_image_galleries) as $import_item_data_item_image_galleries_line)
            {
                if(!empty($import_item_data_item_image_galleries_line))
                {
                    $item_gallery_image_content = base64_encode(file_get_contents($import_item_data_item_image_galleries_line, false, stream_context_create($arrContextOptions)));

                    $currentDate = Carbon::now()->toDateString();
                    $item_image_gallery_uniqid = uniqid();

                    $item_image_gallery['item_image_gallery_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'.jpg';
                    $item_image_gallery['item_image_gallery_thumb_name'] = 'gallary-'.$currentDate.'-'.$item_image_gallery_uniqid.'-thumb.jpg';

                    if(!Storage::disk('public')->exists('item/gallery')){
                        Storage::disk('public')->makeDirectory('item/gallery');
                    }

                    // original
                    $one_gallery_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$item_gallery_image_content)))->stream('jpg', 80);
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_name'], $one_gallery_image);

                    // thumb size
                    $one_gallery_image_thumb = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$item_gallery_image_content)))
                        ->resize(null, 180, function($constraint) {
                            $constraint->aspectRatio();
                        });
                    $one_gallery_image_thumb = $one_gallery_image_thumb->stream('jpg', 70);
                    Storage::disk('public')->put('item/gallery/'.$item_image_gallery['item_image_gallery_thumb_name'], $one_gallery_image_thumb);

                    $created_item_image_gallery = $new_item->galleries()->create($item_image_gallery);
                }
            }
            /**
             * End process gallery images
             */

            // finish the listing import
            $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_SUCCESS;
            $import_item_data->import_item_data_item_id = $new_item->id;
            $import_item_data->save();
            return true;
        }
        catch (Exception $e) {

            Log::error($e);

            $import_item_data->import_item_data_item_id = null;
            $import_item_data->import_item_data_process_error_log = $e->getMessage();
            $import_item_data->import_item_data_process_status = ImportItemData::PROCESS_STATUS_PROCESSED_ERROR;
            $import_item_data->save();

            if($new_item)
            {
                $new_item->deleteItem();
            }

            return false;
        }

    }
}
