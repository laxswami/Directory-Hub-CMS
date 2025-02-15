<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Testimonial;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class TestimonialController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.testimonial.testimonials', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_testimonials = Testimonial::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.testimonial.index', compact('all_testimonials'));
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
        SEOMeta::setTitle(__('seo.backend.admin.testimonial.create-testimonial', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'testimonial_name' => 'required|max:255',
            'testimonial_description' => 'required',
        ]);

        $testimonial_name = $request->testimonial_name;
        $testimonial_company = $request->testimonial_company;
        $testimonial_job_title = $request->testimonial_job_title;
        $testimonial_description = $request->testimonial_description;

        // start upload testimonial image
        $testimonial_image = $request->testimonial_image;
        $testimonial_image_name = null;
        if(!empty($testimonial_image)){

            $currentDate = Carbon::now()->toDateString();

            $testimonial_image_name = str_slug($testimonial_name).'-'.$currentDate.'-'.uniqid().'.jpg';

            if(!Storage::disk('public')->exists('testimonial')){
                Storage::disk('public')->makeDirectory('testimonial');
            }

            $new_testimonial_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$testimonial_image)))->stream('jpg', 70);

            Storage::disk('public')->put('testimonial/'.$testimonial_image_name, $new_testimonial_image);
        }

        $testimonial = new Testimonial();
        $testimonial->testimonial_name = $testimonial_name;
        $testimonial->testimonial_company = $testimonial_company;
        $testimonial->testimonial_job_title = $testimonial_job_title;
        $testimonial->testimonial_image = $testimonial_image_name;
        $testimonial->testimonial_description = $testimonial_description;
        $testimonial->save();

        \Session::flash('flash_message', __('alert.testimonial-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Display the specified resource.
     *
     * @param Testimonial $testimonial
     * @return RedirectResponse
     */
    public function show(Testimonial $testimonial)
    {
        return redirect()->route('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Testimonial $testimonial
     * @return Response
     */
    public function edit(Testimonial $testimonial)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.testimonial.edit-testimonial', ['site_name'  => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.testimonial.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Testimonial $testimonial
     * @return RedirectResponse
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'testimonial_name' => 'required|max:255',
            'testimonial_description' => 'required',
        ]);

        $testimonial_name = $request->testimonial_name;
        $testimonial_company = $request->testimonial_company;
        $testimonial_job_title = $request->testimonial_job_title;
        $testimonial_description = $request->testimonial_description;

        // start upload testimonial image
        $testimonial_image = $request->testimonial_image;
        $testimonial_image_name = $testimonial->testimonial_image;
        if(!empty($testimonial_image)){

            $currentDate = Carbon::now()->toDateString();

            $testimonial_image_name = str_slug($testimonial_name).'-'.$currentDate.'-'.uniqid().'.jpg';

            if(!Storage::disk('public')->exists('testimonial')){
                Storage::disk('public')->makeDirectory('testimonial');
            }
            if(Storage::disk('public')->exists('testimonial/' . $testimonial->testimonial_image)){
                Storage::disk('public')->delete('testimonial/' . $testimonial->testimonial_image);
            }

            $new_testimonial_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$testimonial_image)))->stream('jpg', 70);

            Storage::disk('public')->put('testimonial/'.$testimonial_image_name, $new_testimonial_image);
        }

        $testimonial->testimonial_name = $testimonial_name;
        $testimonial->testimonial_company = $testimonial_company;
        $testimonial->testimonial_job_title = $testimonial_job_title;
        $testimonial->testimonial_image = $testimonial_image_name;
        $testimonial->testimonial_description = $testimonial_description;
        $testimonial->save();

        \Session::flash('flash_message', __('alert.testimonial-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Testimonial $testimonial
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Testimonial $testimonial)
    {
        // first, delete testimonial image
        if(!empty($testimonial->testimonial_image))
        {
            if(Storage::disk('public')->exists('testimonial/' . $testimonial->testimonial_image)){
                Storage::disk('public')->delete('testimonial/' . $testimonial->testimonial_image);
            }
        }

        // second, delete testimonial record
        $testimonial->delete();

        \Session::flash('flash_message', __('alert.testimonial-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.testimonials.index');
    }
}
