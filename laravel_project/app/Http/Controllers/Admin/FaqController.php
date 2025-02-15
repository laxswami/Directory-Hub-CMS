<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class FaqController extends Controller
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
        SEOMeta::setTitle(__('seo.backend.admin.faq.faqs', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_faqs = Faq::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.faq.index', compact('all_faqs'));
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
        SEOMeta::setTitle(__('seo.backend.admin.faq.create-faq', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.faq.create');
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
            'faqs_question' => 'required|max:255',
            'faqs_answer' => 'required',
            'faqs_order' => 'numeric',
        ]);

        $total_faqs = Faq::all()->count();

        $faq = new Faq();
        $faq->faqs_question = $request->faqs_question;
        $faq->faqs_answer = $request->faqs_answer;
        $faq->faqs_order = empty($request->faqs_order) ? $total_faqs + 1 : $request->faqs_order;
        $faq->save();

        \Session::flash('flash_message', __('alert.faq-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.faqs.edit', compact('faq'));
    }

    /**
     * Display the specified resource.
     *
     * @param Faq $faq
     * @return RedirectResponse
     */
    public function show(Faq $faq)
    {
        return redirect()->route('admin.faqs.edit', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Faq $faq
     * @return Response
     */
    public function edit(Faq $faq)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.faq.edit-faq', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Faq $faq
     * @return RedirectResponse
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'faqs_question' => 'required|max:255',
            'faqs_answer' => 'required',
            'faqs_order' => 'numeric',
        ]);

        $total_faqs = Faq::all()->count();

        $faq->faqs_question = $request->faqs_question;
        $faq->faqs_answer = $request->faqs_answer;
        $faq->faqs_order = empty($request->faqs_order) ? $total_faqs + 1 : $request->faqs_order;
        $faq->save();

        \Session::flash('flash_message', __('alert.faq-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.faqs.edit', compact('faq'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Faq $faq
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        \Session::flash('flash_message', __('alert.faq-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.faqs.index');
    }
}
