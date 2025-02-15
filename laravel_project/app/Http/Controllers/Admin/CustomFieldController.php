<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\CustomField;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class CustomFieldController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.custom-field.custom-fields', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

//        $category_id = $request->category;
//
//        if($category_id)
//        {
//            $category = Category::findOrFail($category_id);
//
//            //$all_custom_fields = $category->customFields()->orderBy('custom_field_order')->get();
//            $all_custom_fields = $category->allCustomFields()->orderBy('custom_field_order')->get();
//        }
//        else
//        {
//            $all_custom_fields = CustomField::all();
//        }
//
//        $all_categories = new Category();
//        $all_categories = $all_categories->getPrintableCategories();


        $all_printable_categories = new Category();
        $all_printable_categories = $all_printable_categories->getPrintableCategoriesNoDash();

        /**
         * Start initial filter
         */
        // filter search query
        $search_query = empty($request->search_query) ? null : $request->search_query;
        $search_values = !empty($search_query) ? preg_split('/\s+/', $search_query, -1, PREG_SPLIT_NO_EMPTY) : array();

        // filter categories
        $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;

        $filter_category_ids = array();
        if(count($filter_categories) == 0)
        {
            foreach($all_printable_categories as $all_printable_categories_key => $printable_category)
            {
                $filter_category_ids[] = $printable_category['category_id'];
            }
        }
        else
        {
            $filter_category_ids = $filter_categories;
        }

        // filter custom field type
        $filter_custom_field_type = $request->filter_custom_field_type;
        if(empty($filter_custom_field_type))
        {
            $filter_custom_field_type = array(CustomField::TYPE_TEXT, CustomField::TYPE_SELECT, CustomField::TYPE_MULTI_SELECT, CustomField::TYPE_LINK);
        }

        // filter sort by
        if($search_query)
        {
            $filter_sort_by = CustomField::CUSTOM_FIELDS_SORT_BY_MOST_RELEVANT;
        }
        else
        {
            $filter_sort_by = empty($request->filter_sort_by) ? CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
        }

        // filter rows per page
        $filter_count_per_page = empty($request->filter_count_per_page) ? CustomField::COUNT_PER_PAGE_10 : $request->filter_count_per_page;
        /**
         * End initial filter
         */

        /**
         * Start build query
         */
        $custom_fields_query = CustomField::query();

        $custom_fields_query->select('custom_fields.*');

        // categories
        $custom_fields_query->join('category_custom_field as ccf', 'custom_fields.id', '=', 'ccf.custom_field_id')
            ->whereIn("ccf.category_id", $filter_category_ids);

        // search query
        if(is_array($search_values) && count($search_values) > 0)
        {
            $custom_fields_query->where(function ($query) use ($search_values) {
                foreach($search_values as $search_values_key => $search_value)
                {
                    $query->orWhere('custom_fields.custom_field_name', 'LIKE', "%".$search_value."%");
                }
            });
        }

        // custom field type
        $custom_fields_query->whereIn('custom_fields.custom_field_type', $filter_custom_field_type);

        // sort by
        if($filter_sort_by == CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_CREATED)
        {
            $custom_fields_query->orderBy('custom_fields.created_at', 'DESC');
        }
        elseif($filter_sort_by == CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_CREATED)
        {
            $custom_fields_query->orderBy('custom_fields.created_at', 'ASC');
        }
        elseif($filter_sort_by == CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_UPDATED)
        {
            $custom_fields_query->orderBy('custom_fields.updated_at', 'DESC');
        }
        elseif($filter_sort_by == CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_UPDATED)
        {
            $custom_fields_query->orderBy('custom_fields.updated_at', 'ASC');
        }
        elseif($filter_sort_by == CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_A_Z)
        {
            $custom_fields_query->orderBy('custom_fields.custom_field_name', 'ASC');
        }
        elseif($filter_sort_by == CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_Z_A)
        {
            $custom_fields_query->orderBy('custom_fields.custom_field_name', 'DESC');
        }

        $custom_fields_query->distinct('custom_fields.id');
        /**
         * End build query
         */

        /**
         * Start getting query result
         */
        $custom_fields_count = $custom_fields_query->count();
        $custom_fields = $custom_fields_query->paginate($filter_count_per_page);

        $querystringArray = [
            'search_query' => $search_query,
            'filter_categories' => $filter_categories,
            'filter_custom_field_type' => $filter_custom_field_type,
            'filter_sort_by' => $filter_sort_by,
            'filter_count_per_page' => $filter_count_per_page,
        ];

        $pagination = $custom_fields->appends($querystringArray);
        /**
         * End getting query result
         */


        return response()->view('backend.admin.custom-field.index',
            compact('custom_fields', 'custom_fields_count', 'all_printable_categories', 'filter_categories',
                    'filter_custom_field_type', 'filter_sort_by', 'filter_count_per_page', 'search_query', 'pagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.custom-field.create-custom-field', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_categories = new Category();
        $all_categories = $all_categories->getPrintableCategories();

        return response()->view('backend.admin.custom-field.create', compact('all_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'category.*' => 'numeric',
            'custom_field_name' => 'required|max:255',
            'custom_field_type' => 'required|numeric',
        ]);

        $custom_field_type = $request->custom_field_type;

        if($custom_field_type == CustomField::TYPE_SELECT || $custom_field_type == CustomField::TYPE_MULTI_SELECT)
        {
            if(empty($request->custom_field_seed_value))
            {
                throw ValidationException::withMessages(
                    [
                        'custom_field_seed_value' => 'Seed value required',
                    ]);
            }
        }

        $custom_field_name = $request->custom_field_name;
        $custom_field_seed_value = $request->custom_field_seed_value;
        $custom_field_order = $request->custom_field_order;

        $new_custom_field = new CustomField(array(
            'custom_field_name' => $custom_field_name,
            'custom_field_type' => $custom_field_type,
            'custom_field_seed_value' => $custom_field_seed_value,
            'custom_field_order' => $custom_field_order,
        ));
        $new_custom_field->save();

        $categories = $request->category;
        $category_ids = array();

        foreach($categories as $key => $category_id)
        {
            $category = Category::find($category_id);
            if($category)
            {
                $category_ids[] = $category_id;
            }
        }

        $new_custom_field->allCategories()->sync($category_ids);

        \Session::flash('flash_message', __('alert.custom-field-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.custom-fields.edit', $new_custom_field);
    }

    /**
     * Display the specified resource.
     *
     * @param CustomField $customField
     * @return RedirectResponse
     */
    public function show(CustomField $customField)
    {
        return redirect()->route('admin.custom-fields.edit', $customField);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CustomField $customField
     * @return Response
     */
    public function edit(CustomField $customField)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.custom-field.edit-custom-field', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_categories = new Category();
        $all_categories = $all_categories->getPrintableCategories();

        return response()->view('backend.admin.custom-field.edit',
            compact('customField', 'all_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CustomField $customField
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, CustomField $customField)
    {
        $request->validate([
            'custom_field_name' => 'required|max:255',
            'custom_field_type' => 'required|numeric',
            'category' => 'required',
            'category.*' => 'numeric',
        ]);

        $custom_field_type = $request->custom_field_type;

        if($custom_field_type == CustomField::TYPE_SELECT || $custom_field_type == CustomField::TYPE_MULTI_SELECT)
        {
            if(empty($request->custom_field_seed_value))
            {
                throw ValidationException::withMessages(
                    [
                        'custom_field_seed_value' => 'Seed value required',
                    ]);
            }
        }

        $customField->custom_field_name = $request->custom_field_name;
        $customField->custom_field_type = $request->custom_field_type;
        $customField->custom_field_seed_value = $request->custom_field_seed_value;
        $customField->custom_field_order = $request->custom_field_order;
        $customField->save();

        $categories = $request->category;
        $category_ids = array();

        foreach($categories as $key => $category_id)
        {
            $category = Category::find($category_id);

            if($category)
            {
                $category_ids[] = $category_id;
            }
        }

        $customField->allCategories()->sync($category_ids);

        \Session::flash('flash_message', __('alert.custom-field-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.custom-fields.edit', $customField);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CustomField $customField
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(CustomField $customField)
    {
        $customField->deleteCustomField();

        \Session::flash('flash_message', __('alert.custom-field-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.custom-fields.index');

    }
}
