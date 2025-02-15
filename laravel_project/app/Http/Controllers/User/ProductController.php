<?php

namespace App\Http\Controllers\User;

use App\Attribute;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductFeature;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
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
        SEOMeta::setTitle(__('products.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $show_products_status = $request->show_products_status;

        $all_products_query = Product::query();

        if(!empty($show_products_status))
        {
            // show products for specific status
            $all_products_query->where('product_status', $show_products_status);
        }

        // show products for login user
        $all_products_query->where('user_id', $login_user->id);

        $all_products = $all_products_query->get();

        $setting_product_currency_symbol = $settings->setting_product_currency_symbol;

        return response()->view('backend.user.product.index',
            compact('all_products', 'show_products_status', 'setting_product_currency_symbol'));

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
        SEOMeta::setTitle(__('products.seo.create', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $setting_product_max_gallery_photos = $settings->setting_product_max_gallery_photos;
        $setting_product_currency_symbol = $settings->setting_product_currency_symbol;

        return response()->view('backend.user.product.create',
            compact('setting_product_max_gallery_photos', 'setting_product_currency_symbol'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * Start form validation
         */
        $request->validate([
            'product_name' => 'required|max:255',
            'product_description' => 'required|max:65535',
            'product_price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $settings = app('site_global_settings');

        $login_user = Auth::user();
        $user_id = $login_user->id;

        $product_status = $settings->setting_product_auto_approval_enable == Setting::SITE_PRODUCT_AUTO_APPROVAL_ENABLED ? Product::STATUS_APPROVED : Product::STATUS_PENDING;

        $product_name = $request->product_name;
        $product_description = $request->product_description;
        $product_price = $request->product_price;
        $product_image_large = $request->feature_image;
        $product_slug = uniqid() . $user_id;
        /**
         * End form validation
         */

        /**
         * Start saving product feature image
         */
        $product_image_large_name = null;
        $product_image_medium_name = null;
        $product_image_small_name = null;
        if(!empty($product_image_large))
        {
            $currentDate = Carbon::now()->toDateString();
            $product_image_large_name = $user_id . '-large-' . uniqid() . '-' . $currentDate .'.jpg';
            $product_image_medium_name = $user_id . '-medium-' . uniqid() . '-' . $currentDate . '.jpg';
            $product_image_small_name = $user_id . '-small-' . uniqid() . '-' . $currentDate . '.jpg';

            if(!Storage::disk('public')->exists('product')){
                Storage::disk('public')->makeDirectory('product');
            }

            // large size (original)
            $product_image_large_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$product_image_large)))->stream('jpg', 70);
            Storage::disk('public')->put('product/'.$product_image_large_name, $product_image_large_image_obj);

            // medium size
            $product_image_medium_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$product_image_large)))
                ->resize(224, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $product_image_medium_image_obj = $product_image_medium_image_obj->stream('jpg', 70);
            Storage::disk('public')->put('product/'.$product_image_medium_name, $product_image_medium_image_obj);

            // small size
            $product_image_small_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$product_image_large)))
                ->resize(128, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $product_image_small_image_obj = $product_image_small_image_obj->stream('jpg', 70);
            Storage::disk('public')->put('product/'.$product_image_small_name, $product_image_small_image_obj);
        }
        /**
         * End saving product feature image
         */

        /**
         * Start saving new product
         */
        $new_product = new Product(array(
            'user_id' => $user_id,
            'product_status' => $product_status,
            'product_name' => $product_name,
            'product_description' => $product_description,
            'product_price' => $product_price,
            'product_slug' => $product_slug,
            'product_image_small' => $product_image_small_name,
            'product_image_medium' => $product_image_medium_name,
            'product_image_large' => $product_image_large_name,
        ));
        $new_product->save();
        /**
         * End saving new product
         */

        /**
         * Start saving product gallery images
         */
        $image_gallary = $request->image_gallery;
        if(is_array($image_gallary) && count($image_gallary) > 0)
        {
            foreach($image_gallary as $image_gallary_key => $image)
            {
                if($image_gallary_key < $settings->setting_product_max_gallery_photos)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $product_image_gallery['product_image_gallery_name'] = $user_id . '-gallery-' . uniqid() . '-' . $currentDate . '.jpg';
                    $product_image_gallery['product_image_gallery_thumb_name'] = $user_id . '-gallery-thumb-' . uniqid() . '-' . $currentDate . '.jpg';

                    if(!Storage::disk('public')->exists('product/gallery')){
                        Storage::disk('public')->makeDirectory('product/gallery');
                    }

                    // original
                    $one_gallery_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream('jpg', 80);
                    Storage::disk('public')->put('product/gallery/'.$product_image_gallery['product_image_gallery_name'], $one_gallery_image_obj);

                    // thumb size
                    $one_gallery_image_thumb_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                        ->resize(null, 80, function($constraint) {
                            $constraint->aspectRatio();
                        });
                    $one_gallery_image_thumb_obj = $one_gallery_image_thumb_obj->stream('jpg', 70);
                    Storage::disk('public')->put('product/gallery/'.$product_image_gallery['product_image_gallery_thumb_name'], $one_gallery_image_thumb_obj);

                    $new_product_image_gallery = $new_product->productGalleries()->create($product_image_gallery);
                }
            }
        }
        /**
         * End saving product gallery images
         */

        // success, flash message
        \Session::flash('flash_message', __('products.alert.product-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.products.edit', ['product' => $new_product->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function show(Product $product)
    {
        return redirect()->route('user.products.edit', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product)
    {
        Gate::authorize('edit-product', $product);

        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('products.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $product_features = $product->productFeatures()
            ->with('attribute')
            ->orderBy('product_feature_order')
            ->get();

        /**
         * Start initial available product attributes
         */
        $attributes = Attribute::where('user_id', $login_user->id)
            ->orderBy('attribute_name')
            ->get();
        /**
         * End initial available product attributes
         */

        $setting_product_max_gallery_photos = $settings->setting_product_max_gallery_photos;
        $setting_product_currency_symbol = $settings->setting_product_currency_symbol;

        return response()->view('backend.user.product.edit',
            compact('product', 'product_features', 'attributes',
                'setting_product_max_gallery_photos', 'setting_product_currency_symbol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update-product', $product);

        $settings = app('site_global_settings');

        /**
         * Start form validation
         */
        $validate_rule = [
            'product_name' => 'required|max:255',
            'product_description' => 'required|max:65535',
            'product_price' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ];

        $product_features_links = $product->productFeatures()
            ->with('attribute')
            ->get();


        $product_features_links_validation = array();
        foreach($product_features_links as $key => $product_feature_link)
        {
            if($product_feature_link->attribute->attribute_type == Attribute::TYPE_LINK)
            {
                $product_features_links_validation[str_slug('product_feature_' . $product_feature_link->id)] = 'nullable|url';
            }
        }
        $validate_rule = array_merge($validate_rule, $product_features_links_validation);

        $request->validate($validate_rule);
        /**
         * End form validation
         */

        $user_id = $product->user_id;
        $product_name = $request->product_name;
        $product_description = $request->product_description;
        $product_price = $request->product_price;
        $product_image_large = $request->feature_image;

        /**
         * Start saving feature image
         */
        $product_image_large_name = $product->product_image_large;
        $product_image_medium_name = $product->product_image_medium;
        $product_image_small_name = $product->product_image_small;
        if(!empty($product_image_large))
        {
            $currentDate = Carbon::now()->toDateString();
            $product_image_large_name = $user_id . '-large-' . uniqid() . '-' . $currentDate .'.jpg';
            $product_image_medium_name = $user_id . '-medium-' . uniqid() . '-' . $currentDate . '.jpg';
            $product_image_small_name = $user_id . '-small-' . uniqid() . '-' . $currentDate . '.jpg';

            if(!Storage::disk('public')->exists('product')){
                Storage::disk('public')->makeDirectory('product');
            }

            if(Storage::disk('public')->exists('product/' . $product->product_image_large)){

                Storage::disk('public')->delete('product/' . $product->product_image_large);
                Storage::disk('public')->delete('product/' . $product->product_image_medium);
                Storage::disk('public')->delete('product/' . $product->product_image_small);

            }

            // large size (original)
            $product_image_large_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$product_image_large)))->stream('jpg', 70);
            Storage::disk('public')->put('product/'.$product_image_large_name, $product_image_large_image_obj);

            // medium size
            $product_image_medium_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$product_image_large)))
                ->resize(224, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $product_image_medium_image_obj = $product_image_medium_image_obj->stream('jpg', 70);
            Storage::disk('public')->put('product/'.$product_image_medium_name, $product_image_medium_image_obj);

            // small size
            $product_image_small_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$product_image_large)))
                ->resize(128, null, function($constraint) {
                    $constraint->aspectRatio();
                });
            $product_image_small_image_obj = $product_image_small_image_obj->stream('jpg', 70);
            Storage::disk('public')->put('product/'.$product_image_small_name, $product_image_small_image_obj);
        }
        /**
         * End saving feature image
         */

        /**
         * Start saving product
         */
        $product->product_name = $product_name;
        $product->product_description = $product_description;
        $product->product_price = $product_price;
        $product->product_image_small = $product_image_small_name;
        $product->product_image_medium = $product_image_medium_name;
        $product->product_image_large = $product_image_large_name;
        $product->save();
        /**
         * End saving product
         */

        /**
         * Start saving product gallery images
         */
        $image_gallary = $request->image_gallery;
        if(is_array($image_gallary) && count($image_gallary) > 0)
        {
            $total_product_image_gallery = $product->productGalleries()->count();
            foreach($image_gallary as $image_gallary_key => $image)
            {
                if($total_product_image_gallery + $image_gallary_key < $settings->setting_product_max_gallery_photos)
                {
                    $currentDate = Carbon::now()->toDateString();
                    $product_image_gallery['product_image_gallery_name'] = $user_id . '-gallery-' . uniqid() . '-' . $currentDate . '.jpg';
                    $product_image_gallery['product_image_gallery_thumb_name'] = $user_id . '-gallery-thumb-' . uniqid() . '-' . $currentDate . '.jpg';

                    if(!Storage::disk('public')->exists('product/gallery')){
                        Storage::disk('public')->makeDirectory('product/gallery');
                    }

                    // original
                    $one_gallery_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))->stream('jpg', 80);
                    Storage::disk('public')->put('product/gallery/'.$product_image_gallery['product_image_gallery_name'], $one_gallery_image_obj);

                    // thumb size
                    $one_gallery_image_thumb_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image)))
                        ->resize(null, 80, function($constraint) {
                            $constraint->aspectRatio();
                        });
                    $one_gallery_image_thumb_obj = $one_gallery_image_thumb_obj->stream('jpg', 70);
                    Storage::disk('public')->put('product/gallery/'.$product_image_gallery['product_image_gallery_thumb_name'], $one_gallery_image_thumb_obj);

                    $new_product_image_gallery = $product->productGalleries()->create($product_image_gallery);
                }
            }
        }
        /**
         * End saving product gallery images
         */

        /**
         * Start saving product features
         */
        $product_features = $product->productFeatures()
            ->with('attribute')
            ->get();

        foreach($product_features as $product_features_key => $product_feature)
        {
            if($product_feature->attribute->attribute_type == Attribute::TYPE_MULTI_SELECT)
            {
                $multi_select_values = $request->get(str_slug('product_feature_' . $product_feature->id), '');
                $multi_select_str = '';
                if(is_array($multi_select_values))
                {
                    foreach($multi_select_values as $key => $value)
                    {
                        $multi_select_str .= $value . ', ';
                    }
                }

                $product_feature->product_feature_value = empty($multi_select_str) ? '' : substr(trim($multi_select_str), 0, -1);
                $product_feature->save();
            }
            else
            {
                $product_feature->product_feature_value = $request->get(str_slug('product_feature_' . $product_feature->id), '');
                $product_feature->save();
            }
        }
        /**
         * End saving product features
         */

        // success, flash message
        \Session::flash('flash_message', __('products.alert.product-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.products.edit', ['product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete-product', $product);

        if($product->hasAssignedToListing())
        {
            \Session::flash('flash_message', __('products.alert.product-in-use'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.products.edit', ['product' => $product]);
        }
        else
        {
            $product->deleteProduct();

            \Session::flash('flash_message', __('products.alert.product-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.products.index');
        }
    }

    public function updateProductAttribute(Request $request, Product $product)
    {
        Gate::authorize('update-product', $product);

        /**
         * Start form validation
         */
        $request->validate([
            'attribute' => 'required|array',
            'attribute.*' => 'numeric',
        ]);

        $product_attributes = $request->attribute;

        $validate_error = array();
        foreach($product_attributes as $key => $attribute)
        {
            $attribute_exist = Attribute::find($attribute);

            if(!$attribute_exist)
            {
                $validate_error['attributes'] = __('products.product-attribute-not-exist');
            }
            else
            {
                if($attribute_exist->user_id != $product->user_id)
                {
                    $validate_error['attributes'] = __('products.attribute-owner-not-match-product');
                }
            }
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        /**
         * End form validation
         */

        $product_features_count = $product->productFeatures()->count();

        foreach($product_attributes as $key => $attribute)
        {
            $new_product_feature = new ProductFeature(array(
                'attribute_id' => $attribute,
                'product_feature_order' => $product_features_count + ($key + 1),
            ));

            $product->productFeatures()->save($new_product_feature);
        }

        // success, flash message
        \Session::flash('flash_message', __('products.alert.product-attribute-added'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.products.edit', ['product' => $product]);
    }

    public function rankUpProductFeature(Request $request, Product $product, ProductFeature $product_feature)
    {
        Gate::authorize('update-product', $product);

        if($product->id != $product_feature->product_id)
        {
            \Session::flash('flash_message', __('products.error.feature-not-match-product'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.products.edit', ['product' => $product]);
        }
        else
        {
            $old_rank = intval($product_feature->product_feature_order);

            $move_down_product_feature = $product->productFeatures()
                ->where('product_feature_order', $old_rank-1)
                ->get();

            if($move_down_product_feature->count() > 0)
            {
                $move_down_product_feature = $move_down_product_feature->first();
                $move_down_product_feature->product_feature_order = $old_rank;
                $move_down_product_feature->save();

                $product_feature->product_feature_order = $old_rank-1;
                $product_feature->save();
            }

            // success, flash message
            \Session::flash('flash_message', __('products.alert.product-feature-ranked-up'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.products.edit', ['product' => $product]);
        }
    }

    public function rankDownProductFeature(Request $request, Product $product, ProductFeature $product_feature)
    {
        Gate::authorize('update-product', $product);

        if ($product->id != $product_feature->product_id)
        {
            \Session::flash('flash_message', __('products.error.feature-not-match-product'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.products.edit', ['product' => $product]);
        }
        else
        {
            $old_rank = intval($product_feature->product_feature_order);

            $move_down_product_feature = $product->productFeatures()
                ->where('product_feature_order', $old_rank + 1)
                ->get();

            if ($move_down_product_feature->count() > 0) {
                $move_down_product_feature = $move_down_product_feature->first();
                $move_down_product_feature->product_feature_order = $old_rank;
                $move_down_product_feature->save();

                $product_feature->product_feature_order = $old_rank + 1;
                $product_feature->save();
            }

            // success, flash message
            \Session::flash('flash_message', __('products.alert.product-feature-ranked-down'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.products.edit', ['product' => $product]);
        }
    }

    public function destroyProductFeature(Request $request, Product $product, ProductFeature $product_feature)
    {
        Gate::authorize('update-product', $product);

        if ($product->id != $product_feature->product_id)
        {
            \Session::flash('flash_message', __('products.error.feature-not-match-product'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.products.edit', ['product' => $product]);
        }
        else
        {
            // first to re-order the lower product features record
            $product_features = $product->productFeatures()
                ->where('product_feature_order', '>', $product_feature->product_feature_order)
                ->orderBy('product_feature_order')
                ->get();

            foreach($product_features as $key => $feature)
            {
                $feature->product_feature_order = intval($feature->product_feature_order) - 1;
                $feature->save();
            }

            $product_feature->delete();

            // success, flash message
            \Session::flash('flash_message', __('products.alert.product-feature-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.products.edit', ['product' => $product]);
        }
    }
}
