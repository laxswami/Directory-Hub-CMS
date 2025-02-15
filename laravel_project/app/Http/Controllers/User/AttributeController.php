<?php

namespace App\Http\Controllers\User;

use App\Attribute;
use App\Http\Controllers\Controller;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class AttributeController extends Controller
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
        SEOMeta::setTitle(__('product_attributes.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $show_attributes_type = $request->show_attributes_type;

        $all_attributes_query = Attribute::query();

        if(!empty($show_attributes_type))
        {
            $all_attributes_query->where('attribute_type', $show_attributes_type);
        }

        $all_attributes = $all_attributes_query->where('user_id', $login_user->id)
            ->get();

        return response()->view('backend.user.attribute.index',
            compact('all_attributes', 'show_attributes_type'));
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
        SEOMeta::setTitle(__('product_attributes.seo.create', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.user.attribute.create');
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
        /**
         * Start form validation
         */
        $request->validate([
            'attribute_name' => 'required|max:255',
            'attribute_type' => 'required|numeric',
            'attribute_seed_value' => 'nullable|max:65535',
        ]);

        $login_user = Auth::user();

        $user_id = $login_user->id;
        $attribute_name = $request->attribute_name;
        $attribute_type = $request->attribute_type;
        $attribute_seed_value = $request->attribute_seed_value;

        $validate_error = array();
        if($attribute_type == Attribute::TYPE_SELECT || $attribute_type == Attribute::TYPE_MULTI_SELECT)
        {
            if(empty($attribute_seed_value))
            {
                $validate_error['attribute_seed_value'] = __('product_attributes.error.seed-value-required');
            }
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        /**
         * End form validation
         */

        $new_attribute = new Attribute(array(
            'user_id' => $user_id,
            'attribute_name' => $attribute_name,
            'attribute_type' => $attribute_type,
            'attribute_seed_value' => $attribute_seed_value,
        ));
        $new_attribute->save();

        \Session::flash('flash_message', __('product_attributes.alert.attribute-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.attributes.edit', ['attribute' => $new_attribute]);
    }

    /**
     * Display the specified resource.
     *
     * @param Attribute $attribute
     * @return RedirectResponse
     */
    public function show(Attribute $attribute)
    {
        return redirect()->route('user.attributes.edit', ['attribute' => $attribute]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attribute $attribute
     * @return Response
     */
    public function edit(Attribute $attribute)
    {
        Gate::authorize('edit-attribute', $attribute);

        $settings = app('site_global_settings');
        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('product_attributes.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.user.attribute.edit',
            compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Attribute $attribute
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Attribute $attribute)
    {
        Gate::authorize('update-attribute', $attribute);

        /**
         * Start form validation
         */
        $request->validate([
            'attribute_name' => 'required|max:255',
            'attribute_type' => 'required|numeric',
            'attribute_seed_value' => 'nullable|max:65535',
        ]);

        $attribute_name = $request->attribute_name;
        $attribute_type = $request->attribute_type;
        $attribute_seed_value = $request->attribute_seed_value;

        $validate_error = array();
        if($attribute_type == Attribute::TYPE_SELECT || $attribute_type == Attribute::TYPE_MULTI_SELECT)
        {
            if(empty($attribute_seed_value))
            {
                $validate_error['attribute_seed_value'] = __('product_attributes.error.seed-value-required');
            }
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        /**
         * End form validation
         */

        $attribute->attribute_name = $attribute_name;
        $attribute->attribute_type = $attribute_type;
        $attribute->attribute_seed_value = $attribute_seed_value;
        $attribute->save();

        \Session::flash('flash_message', __('product_attributes.alert.attribute-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.attributes.edit', ['attribute' => $attribute]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attribute $attribute
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Attribute $attribute)
    {
        Gate::authorize('delete-attribute', $attribute);

        // check model relations before delete
        if($attribute->productFeatures()->count() > 0)
        {
            \Session::flash('flash_message', __('product_attributes.alert.attribute-used-in-product'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.attributes.edit', ['attribute' => $attribute]);
        }
        else
        {
            $attribute->deleteAttribute();

            \Session::flash('flash_message', __('product_attributes.alert.attribute-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.attributes.index');
        }
    }
}
