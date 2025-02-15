<?php

namespace App\Http\Controllers\Admin;

use App\Attribute;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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

        $show_attributes_for = $request->show_attributes_for;
        $show_attributes_type = $request->show_attributes_type;

        $all_attributes_query = Attribute::query();

        if(!empty($show_attributes_for))
        {
            // show attributes for specific user
            $all_attributes_query->where('user_id', $show_attributes_for);
        }

        if(!empty($show_attributes_type))
        {
            $all_attributes_query->where('attribute_type', $show_attributes_type);
        }

        $all_attributes = $all_attributes_query->get();

        /**
         * Start initial data filter form
         */
        $login_user = Auth::user();

        $other_users = User::where('email_verified_at', '!=', null)
            ->where('id', '!=', $login_user->id)
            ->where('user_suspended', User::USER_NOT_SUSPENDED)
            ->orderBy('name')
            ->get();
        /**
         * End initial data filter form
         */

        return response()->view('backend.admin.attribute.index',
            compact('login_user', 'other_users', 'all_attributes',
                'show_attributes_for', 'show_attributes_type'));
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

        /**
         * Start initial form of Attribute owner selector
         */
        $login_user = Auth::user();

        $other_users = User::where('email_verified_at', '!=', null)
            ->where('id', '!=', $login_user->id)
            ->where('user_suspended', User::USER_NOT_SUSPENDED)
            ->orderBy('name')
            ->get();
        /**
         * End initial form of Attribute owner selector
         */

        return response()->view('backend.admin.attribute.create',
            compact('login_user', 'other_users'));
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
            'user_id' => 'required|numeric',
            'attribute_name' => 'required|max:255',
            'attribute_type' => 'required|numeric',
            'attribute_seed_value' => 'nullable|max:65535',
        ]);

        $user_id = $request->user_id;
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

        $user_id_exist = User::where('id', $user_id)
            ->where('user_suspended', User::USER_NOT_SUSPENDED)
            ->count();
        if($user_id_exist == 0)
        {
            $validate_error['user_id'] = __('product_attributes.error.user-not-exist');
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

        return redirect()->route('admin.attributes.edit', ['attribute' => $new_attribute]);
    }

    /**
     * Display the specified resource.
     *
     * @param Attribute $attribute
     * @return RedirectResponse
     */
    public function show(Attribute $attribute)
    {
        return redirect()->route('admin.attributes.edit', ['attribute' => $attribute]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attribute $attribute
     * @return Response
     */
    public function edit(Attribute $attribute)
    {
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

        $attribute_owner = $attribute->user()->first();

        return response()->view('backend.admin.attribute.edit',
            compact('attribute', 'attribute_owner'));
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

        return redirect()->route('admin.attributes.edit', ['attribute' => $attribute]);
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
        // check model relations before delete
        if($attribute->productFeatures()->count() > 0)
        {
            \Session::flash('flash_message', __('product_attributes.alert.attribute-used-in-product'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.attributes.edit', ['attribute' => $attribute]);
        }
        else
        {
            $attribute->deleteAttribute();

            \Session::flash('flash_message', __('product_attributes.alert.attribute-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.attributes.index');
        }
    }
}
