<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
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
        SEOMeta::setTitle(__('seo.backend.admin.category.categories', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */


        /**
         * Start initial filter
         */
        // filter search query
        $search_query = empty($request->search_query) ? null : $request->search_query;
        $search_values = !empty($search_query) ? preg_split('/\s+/', $search_query, -1, PREG_SPLIT_NO_EMPTY) : array();

        // filter sort by
        if($search_query)
        {
            $filter_sort_by = Category::CATEGORIES_SORT_BY_MOST_RELEVANT;
        }
        else
        {
            $filter_sort_by = empty($request->filter_sort_by) ? Category::CATEGORIES_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
        }

        // filter rows per page
        $filter_count_per_page = empty($request->filter_count_per_page) ? Category::COUNT_PER_PAGE_10 : $request->filter_count_per_page;
        /**
         * End initial filter
         */


        /**
         * Start build query
         */
        $categories_query = Category::query();

        $categories_query->select('categories.*');

        // search query
        if(is_array($search_values) && count($search_values) > 0)
        {
            $categories_query->where(function ($query) use ($search_values) {
                foreach($search_values as $search_values_key => $search_value)
                {
                    $query->orWhere('categories.category_name', 'LIKE', "%".$search_value."%");
                }
            });
        }

        // sort by
        if($filter_sort_by == Category::CATEGORIES_SORT_BY_NEWEST_CREATED)
        {
            $categories_query->orderBy('categories.created_at', 'DESC');
        }
        elseif($filter_sort_by == Category::CATEGORIES_SORT_BY_OLDEST_CREATED)
        {
            $categories_query->orderBy('categories.created_at', 'ASC');
        }
        elseif($filter_sort_by == Category::CATEGORIES_SORT_BY_NEWEST_UPDATED)
        {
            $categories_query->orderBy('categories.updated_at', 'DESC');
        }
        elseif($filter_sort_by == Category::CATEGORIES_SORT_BY_OLDEST_UPDATED)
        {
            $categories_query->orderBy('categories.updated_at', 'ASC');
        }
        elseif($filter_sort_by == Category::CATEGORIES_SORT_BY_CATEGORY_NAME_A_Z)
        {
            $categories_query->orderBy('categories.category_name', 'ASC');
        }
        elseif($filter_sort_by == Category::CATEGORIES_SORT_BY_CATEGORY_NAME_Z_A)
        {
            $categories_query->orderBy('categories.category_name', 'DESC');
        }

        $categories_query->distinct('categories.id');
        /**
         * End build query
         */

        /**
         * Start getting query result
         */
        $categories_count = $categories_query->count();

        $categories = $categories_query->paginate($filter_count_per_page);

        $querystringArray = [
            'search_query' => $search_query,
            'filter_sort_by' => $filter_sort_by,
            'filter_count_per_page' => $filter_count_per_page,
        ];

        $pagination = $categories->appends($querystringArray);
        /**
         * End getting query result
         */

        return response()->view('backend.admin.category.index',
            compact('categories', 'categories_count', 'search_query', 'filter_sort_by', 'filter_count_per_page',
                    'pagination'));
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
        SEOMeta::setTitle(__('seo.backend.admin.category.create-category', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $printable_categories = new Category();
        $printable_categories = $printable_categories->getPrintableCategories();

        return response()->view('backend.admin.category.create', compact('printable_categories'));
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
            'category_name' => 'required|unique:categories,category_name|max:255',
            'category_slug' => 'required|unique:categories,category_slug|regex:/^[\w-]*$/|max:255',
            'category_parent_id' => 'required|numeric',
            'category_description' => 'nullable|max:255',
            'category_icon' => 'nullable',
            'category_image' => 'nullable',
            'category_thumbnail_type' => 'required|in:1,2',
            'category_header_background_type' => 'required|in:1,2,3,4',
            'category_header_background_color' => 'nullable|max:255',
            'category_header_background_image' => 'nullable',
            'category_header_background_youtube_video' => 'nullable|url',
        ]);

        $category_name = $request->category_name;
        $category_slug = strtolower($request->category_slug);
        $category_description = empty($request->category_description) ? null : $request->category_description;
        $category_parent_id = empty($request->category_parent_id) ? null : $request->category_parent_id;

        $category_icon = empty($request->category_icon) ? null : $request->category_icon;
        $category_image = empty($request->category_image) ? null : $request->category_image;
        $category_thumbnail_type = $request->category_thumbnail_type;

        $category_header_background_type = $request->category_header_background_type;
        $category_header_background_color = empty($request->category_header_background_color) ? null : $request->category_header_background_color;
        $category_header_background_image = empty($request->category_header_background_image) ? null : $request->category_header_background_image;
        $category_header_background_youtube_video = empty($request->category_header_background_youtube_video) ? null : $request->category_header_background_youtube_video;

        /**
         * Start validate submission
         */
        $validate_error = array();

        if(!empty($category_parent_id))
        {
            $category_exist = Category::where('id', $category_parent_id)->count();

            if($category_exist == 0)
            {
                $validate_error['category_parent_id'] = __('categories.create-cat-not-found');
            }
        }

        if($category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_COLOR
            && empty($category_header_background_color))
        {
            $validate_error['category_header_background_color'] = __('category_image_option.validation-error.background-color-empty');
        }

        if($category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_IMAGE
            && empty($category_header_background_image))
        {
            $validate_error['category_header_background_image'] = __('category_image_option.validation-error.background-image-empty');
        }

        if($category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_VIDEO
            && empty($category_header_background_youtube_video))
        {
            $validate_error['category_header_background_youtube_video'] = __('category_image_option.validation-error.background-video-empty');
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        /**
         * End validate submission
         */

        /**
         * Start save category image
         */
        $category_image_file_name = null;
        if(!empty($category_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $category_image_file_name = $category_slug.'_'.$currentDate.'_'.uniqid().'.jpg';

            // create category storage folder if not exist
            if(!Storage::disk('public')->exists('category')){
                Storage::disk('public')->makeDirectory('category');
            }

            $category_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$category_image)))->stream('jpg', 100);
            Storage::disk('public')->put('category/'.$category_image_file_name, $category_image_obj);
        }
        /**
         * End save category image
         */

        /**
         * Start save category header background image
         */
        $category_header_background_image_file_name = null;
        if(!empty($category_header_background_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $category_header_background_image_file_name = $category_slug.'_background_'.$currentDate.'_'.uniqid().'.jpg';

            // create category storage folder if not exist
            if(!Storage::disk('public')->exists('category')){
                Storage::disk('public')->makeDirectory('category');
            }

            $category_header_background_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$category_header_background_image)))->stream('jpg', 100);
            Storage::disk('public')->put('category/'.$category_header_background_image_file_name, $category_header_background_image_obj);
        }
        /**
         * End save category header background image
         */

        $category = new Category();

        $category->category_name = $category_name;
        $category->category_slug = $category_slug;
        $category->category_parent_id = $category_parent_id;
        $category->category_description = $category_description;

        $category->category_icon = $category_icon;
        $category->category_image = $category_image_file_name;
        $category->category_thumbnail_type = $category_thumbnail_type;

        $category->category_header_background_type = $category_header_background_type;
        $category->category_header_background_color = $category_header_background_color;
        $category->category_header_background_image = $category_header_background_image_file_name;
        $category->category_header_background_youtube_video = $category_header_background_youtube_video;

        $category->save();

        \Session::flash('flash_message', __('alert.category-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.categories.edit', ['category' => $category]);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return RedirectResponse
     */
    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return Response
     */
    public function edit(Category $category)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.category.edit-category', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $printable_categories = new Category();
        $printable_categories = $printable_categories->getPrintableCategories();

        return response()->view('backend.admin.category.edit',
            compact('category', 'printable_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|max:255',
            'category_slug' => 'required|regex:/^[\w-]*$/|max:255',
            'category_parent_id' => 'required|numeric',
            'category_description' => 'nullable|max:255',
            'category_icon' => 'nullable',
            'category_image' => 'nullable',
            'category_thumbnail_type' => 'required|in:1,2',
            'category_header_background_type' => 'required|in:1,2,3,4',
            'category_header_background_color' => 'nullable|max:255',
            'category_header_background_image' => 'nullable',
            'category_header_background_youtube_video' => 'nullable|url',
        ]);

        $category_name = $request->category_name;
        $category_slug = strtolower($request->category_slug);
        $category_description = empty($request->category_description) ? null : $request->category_description;
        $category_parent_id = empty($request->category_parent_id) ? null : $request->category_parent_id;

        $category_icon = empty($request->category_icon) ? null : $request->category_icon;
        $category_image = empty($request->category_image) ? null : $request->category_image;
        $category_thumbnail_type = $request->category_thumbnail_type;

        $category_header_background_type = $request->category_header_background_type;
        $category_header_background_color = empty($request->category_header_background_color) ? null : $request->category_header_background_color;
        $category_header_background_image = empty($request->category_header_background_image) ? null : $request->category_header_background_image;
        $category_header_background_youtube_video = empty($request->category_header_background_youtube_video) ? null : $request->category_header_background_youtube_video;

        /**
         * Start validate submission
         */
        $validate_error = array();
        $category_name_exist = Category::where('category_name', $category_name)
            ->where('id', '!=', $category->id)->count();
        if($category_name_exist > 0)
        {
            $validate_error['category_name'] = __('categories.category-name-taken-error');
        }
        $category_slug_exist = Category::where('category_slug', $category_slug)
            ->where('id', '!=', $category->id)->count();
        if($category_slug_exist > 0)
        {
            $validate_error['category_slug'] = __('categories.category-slug-taken-error');
        }

        if(!empty($category_parent_id))
        {
            if($category_parent_id == $category->id)
            {
                $validate_error['category_parent_id'] = __('categories.self-parent-cat-error');
            }

            $category_exist = Category::where('id', $category_parent_id)->count();
            if($category_exist == 0)
            {
                $validate_error['category_parent_id'] = __('categories.create-cat-not-found');
            }
        }

        if($category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_COLOR
            && empty($category_header_background_color))
        {
            $validate_error['category_header_background_color'] = __('category_image_option.validation-error.background-color-empty');
        }

        if($category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_IMAGE
            && empty($category_header_background_image))
        {
            if(empty($category->category_header_background_image))
            {
                $validate_error['category_header_background_image'] = __('category_image_option.validation-error.background-image-empty');
            }
        }

        if($category_header_background_type == Category::CATEGORY_HEADER_BACKGROUND_TYPE_VIDEO
            && empty($category_header_background_youtube_video))
        {
            $validate_error['category_header_background_youtube_video'] = __('category_image_option.validation-error.background-video-empty');
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        /**
         * End validate submission
         */

        /**
         * Start save category image
         */
        $category_image_file_name = $category->category_image;
        if(!empty($category_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $category_image_file_name = $category_slug.'_'.$currentDate.'_'.uniqid().'.jpg';

            // create category storage folder if not exist
            if(!Storage::disk('public')->exists('category')){
                Storage::disk('public')->makeDirectory('category');
            }

            // delete the old category image file if exist
            if(Storage::disk('public')->exists('category/' . $category->category_image)){
                Storage::disk('public')->delete('category/' . $category->category_image);
            }

            $category_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$category_image)))->stream('jpg', 100);
            Storage::disk('public')->put('category/'.$category_image_file_name, $category_image_obj);
        }
        /**
         * End save category image
         */

        /**
         * Start save category header background image
         */
        $category_header_background_image_file_name = $category->category_header_background_image;
        if(!empty($category_header_background_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $category_header_background_image_file_name = $category_slug.'_background_'.$currentDate.'_'.uniqid().'.jpg';

            // create category storage folder if not exist
            if(!Storage::disk('public')->exists('category')){
                Storage::disk('public')->makeDirectory('category');
            }

            // delete the old category header background image file if exist
            if(Storage::disk('public')->exists('category/' . $category->category_header_background_image)){
                Storage::disk('public')->delete('category/' . $category->category_header_background_image);
            }

            $category_header_background_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$category_header_background_image)))->stream('jpg', 100);
            Storage::disk('public')->put('category/'.$category_header_background_image_file_name, $category_header_background_image_obj);
        }
        /**
         * End save category header background image
         */

        $category->category_name = $category_name;
        $category->category_slug = $category_slug;
        $category->category_parent_id = $category_parent_id;
        $category->category_description = $category_description;

        $category->category_icon = $category_icon;
        $category->category_image = $category_image_file_name;
        $category->category_thumbnail_type = $category_thumbnail_type;

        $category->category_header_background_type = $category_header_background_type;
        $category->category_header_background_color = $category_header_background_color;
        $category->category_header_background_image = $category_header_background_image_file_name;
        $category->category_header_background_youtube_video = $category_header_background_youtube_video;

        $category->save();

        \Session::flash('flash_message', __('alert.category-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.categories.edit', ['category' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Category $category)
    {
        // check model relations before delete
        if($category->allCustomFields()->count() > 0)
        {
            \Session::flash('flash_message', __('alert.category-delete-error-custom-field'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.categories.edit', $category);
        }
        elseif($category->allItems()->count() > 0)
        {
            \Session::flash('flash_message', __('alert.category-delete-error-listing'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.categories.edit', $category);
        }
        elseif($category->children()->count() > 0)
        {
            \Session::flash('flash_message', __('categories.category-delete-error-children'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.categories.edit', $category);
        }
        else
        {
            $category->deleteCategory();

            \Session::flash('flash_message', __('alert.category-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.categories.index');
        }
    }
}
