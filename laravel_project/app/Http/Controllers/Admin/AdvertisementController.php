<?php

namespace App\Http\Controllers\Admin;

use App\Advertisement;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class AdvertisementController extends Controller
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
        SEOMeta::setTitle(__('advertisement.seo.index-ad', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_advertisements = Advertisement::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.ad.index',
            compact('all_advertisements'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('advertisement.seo.create-ad', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $advertisement_place = $request->advertisement_place > 0 ? $request->advertisement_place : 0;

        return response()->view('backend.admin.ad.create',
            compact('advertisement_place'));
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
            'advertisement_name' => 'required|max:255',
            'advertisement_status' => 'required|numeric|min:0|max:1',
            'advertisement_place' => 'required|numeric|min:1|max:7',
            'advertisement_code' => 'required',
            'advertisement_position' => 'required|numeric|min:1|max:18',
            'advertisement_alignment' => 'required|numeric|min:1|max:3',
        ]);

        $advertisement_name = $request->advertisement_name;
        $advertisement_status = $request->advertisement_status == Advertisement::AD_STATUS_ENABLE ? Advertisement::AD_STATUS_ENABLE : Advertisement::AD_STATUS_DISABLE;
        $advertisement_place = $request->advertisement_place;
        $advertisement_code = $request->advertisement_code;
        $advertisement_position = $request->advertisement_position;
        $advertisement_alignment = $request->advertisement_alignment;

        $advertisement = new Advertisement(array(
            'advertisement_name' => $advertisement_name,
            'advertisement_status' => $advertisement_status,
            'advertisement_place' => $advertisement_place,
            'advertisement_code' => $advertisement_code,
            'advertisement_position' => $advertisement_position,
            'advertisement_alignment' => $advertisement_alignment,
        ));
        $advertisement->save();

        \Session::flash('flash_message', __('advertisement.create-ad-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.advertisements.edit', $advertisement);

    }

    /**
     * Display the specified resource.
     *
     * @param Advertisement $advertisement
     * @return RedirectResponse
     */
    public function show(Advertisement $advertisement)
    {
        return redirect()->route('admin.advertisements.edit', $advertisement);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Advertisement $advertisement
     * @return Response
     */
    public function edit(Advertisement $advertisement)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('advertisement.seo.update-ad', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.ad.edit',
            compact('advertisement'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Advertisement $advertisement
     * @return RedirectResponse
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        $request->validate([
            'advertisement_name' => 'required|max:255',
            'advertisement_status' => 'required|numeric|min:0|max:1',
            'advertisement_place' => 'required|numeric|min:1|max:7',
            'advertisement_code' => 'required',
            'advertisement_position' => 'required|numeric|min:1|max:18',
            'advertisement_alignment' => 'required|numeric|min:1|max:3',
        ]);

        $advertisement_name = $request->advertisement_name;
        $advertisement_status = $request->advertisement_status == Advertisement::AD_STATUS_ENABLE ? Advertisement::AD_STATUS_ENABLE : Advertisement::AD_STATUS_DISABLE;
        $advertisement_place = $request->advertisement_place;
        $advertisement_code = $request->advertisement_code;
        $advertisement_position = $request->advertisement_position;
        $advertisement_alignment = $request->advertisement_alignment;

        $advertisement->advertisement_name = $advertisement_name;
        $advertisement->advertisement_status = $advertisement_status;
        $advertisement->advertisement_place = $advertisement_place;
        $advertisement->advertisement_code = $advertisement_code;
        $advertisement->advertisement_position = $advertisement_position;
        $advertisement->advertisement_alignment = $advertisement_alignment;
        $advertisement->save();

        \Session::flash('flash_message', __('advertisement.update-ad-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.advertisements.edit', $advertisement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Advertisement $advertisement
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();

        \Session::flash('flash_message', __('advertisement.delete-ad-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.advertisements.index');
    }
}
