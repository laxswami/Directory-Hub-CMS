<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Item;
use App\ItemLead;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class ItemLeadController extends Controller
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
        SEOMeta::setTitle(__('role_permission.item-leads.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_item_leads = ItemLead::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.item.item-lead.index', compact('all_item_leads'));
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
        SEOMeta::setTitle(__('role_permission.item-leads.seo.create', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $items = Item::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.item.item-lead.create',
            compact('items'));
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
            'item_slug' => 'required',
            'item_lead_name' => 'required|max:255',
            'item_lead_email' => 'required|email',
            'item_lead_phone' => 'nullable|numeric',
            'item_lead_subject' => 'nullable|max:255',
            'item_lead_message' => 'nullable|max:65535',
        ]);

        $item = Item::where('item_slug', $request->item_slug)->first();

        if($item)
        {
            $item_lead = new ItemLead(array(
                'item_id' => $item->id,
                'item_lead_name' => $request->item_lead_name,
                'item_lead_email' => $request->item_lead_email,
                'item_lead_phone' => $request->item_lead_phone,
                'item_lead_subject' => $request->item_lead_subject,
                'item_lead_message' => $request->item_lead_message,
            ));
            $item_lead->save();

            \Session::flash('flash_message', __('role_permission.item-leads.alert.item-lead-created'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.item-leads.edit', ['item_lead' => $item_lead]);
        }
        else
        {
            throw ValidationException::withMessages(['item_slug' => __('role_permission.item-leads.alert.listing-not-exist')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ItemLead $item_lead
     * @return RedirectResponse
     */
    public function show(ItemLead $item_lead)
    {
        return redirect()->route('admin.item-leads.edit', ['item_lead' => $item_lead]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ItemLead $item_lead
     * @return Response
     */
    public function edit(ItemLead $item_lead)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('role_permission.item-leads.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $items = Item::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.item.item-lead.edit',
            compact('items', 'item_lead'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ItemLead $item_lead
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, ItemLead $item_lead)
    {
        $request->validate([
            'item_slug' => 'required',
            'item_lead_name' => 'required|max:255',
            'item_lead_email' => 'required|email',
            'item_lead_phone' => 'nullable|numeric',
            'item_lead_subject' => 'nullable|max:255',
            'item_lead_message' => 'nullable|max:255',
        ]);

        $item = Item::where('item_slug', $request->item_slug)->first();

        if($item)
        {
            $item_lead->item_id = $item->id;
            $item_lead->item_lead_name = $request->item_lead_name;
            $item_lead->item_lead_email = $request->item_lead_email;
            $item_lead->item_lead_phone = $request->item_lead_phone;
            $item_lead->item_lead_subject = $request->item_lead_subject;
            $item_lead->item_lead_message = $request->item_lead_message;
            $item_lead->save();

            \Session::flash('flash_message', __('role_permission.item-leads.alert.item-lead-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.item-leads.edit', ['item_lead' => $item_lead]);
        }
        else
        {
            throw ValidationException::withMessages(['item_slug' => __('role_permission.item-leads.alert.listing-not-exist')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ItemLead $item_lead
     * @return RedirectResponse
     */
    public function destroy(ItemLead $item_lead)
    {
        $item_lead->deleteItemLead();

        \Session::flash('flash_message', __('role_permission.item-leads.alert.item-lead-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.item-leads.index');
    }
}
