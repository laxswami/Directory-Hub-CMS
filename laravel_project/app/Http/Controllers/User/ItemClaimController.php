<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Item;
use App\ItemClaim;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ItemClaimController extends Controller
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
        //SEOMeta::setTitle('Dashboard - Cities - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('item_claim.seo.user-item-claims-index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item_claim_status = $request->item_claim_status;
        $login_user = Auth::user();

        if($item_claim_status == ItemClaim::ITEM_CLAIM_FILTER_REQUESTED)
        {
            $all_item_claims = ItemClaim::where('user_id', $login_user->id)
                ->where('item_claim_status', ItemClaim::ITEM_CLAIM_STATUS_REQUESTED)
                ->get();
        }
        elseif($item_claim_status == ItemClaim::ITEM_CLAIM_FILTER_DISAPPROVED)
        {
            $all_item_claims = ItemClaim::where('user_id', $login_user->id)
                ->where('item_claim_status', ItemClaim::ITEM_CLAIM_STATUS_DISAPPROVED)
                ->get();
        }
        elseif($item_claim_status == ItemClaim::ITEM_CLAIM_FILTER_APPROVED)
        {
            $all_item_claims = ItemClaim::where('user_id', $login_user->id)
                ->where('item_claim_status', ItemClaim::ITEM_CLAIM_STATUS_APPROVED)
                ->get();
        }
        else
        {
            // if no data filter value get, then show all item claims of the login user.
            $all_item_claims = ItemClaim::where('user_id', $login_user->id)
                ->get();
        }

        return response()->view('backend.user.item.item-claim.index',
            compact('all_item_claims', 'item_claim_status'));
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
        //$site_prefer_country_id = app('site_prefer_country_id');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Cities - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('item_claim.seo.user-item-claims-create', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // first check if the item exist
        $item_slug = $request->item_slug;
        $item = Item::where('item_slug', $item_slug)
            ->where('item_status', Item::ITEM_PUBLISHED)
            //->where('country_id', $site_prefer_country_id)
            ->get();

        if($item->count() == 0)
        {
            \Session::flash('flash_message', __('item_claim.alert.item-not-found'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.item-claims.index');
        }
        else
        {
            $item = $item->first();
            $item_has_claimed = $item->hasClaimed();
            $item_claimed_user = $item->getClaimedUser();
        }

        return response()->view('backend.user.item.item-claim.create',
            compact('item', 'item_has_claimed', 'item_claimed_user'));
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
            'item_claims_item_id' => 'required|numeric',
            'item_claim_full_name' => 'required|max:255',
            'item_claim_phone' => 'nullable|max:255',
            'item_claim_email' => 'nullable|email|max:255',
            'item_claim_additional_proof' => 'nullable|max:65535',
            'item_claim_additional_upload' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $item_id = $request->item_claims_item_id;
        $item = Item::find($item_id);
        if(!$item)
        {
            \Session::flash('flash_message', __('item_claim.alert.item-not-found'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.item-claims.index');
        }

        $login_user = Auth::user();
        $item_claim_full_name = $request->item_claim_full_name;
        $item_claim_phone = $request->item_claim_phone;
        $item_claim_email = $request->item_claim_email;
        $item_claim_additional_proof = $request->item_claim_additional_proof;
        $item_claim_additional_proof_file_name = null;

        // start saving the user uploaded document
        if(!empty($request->item_claim_additional_upload))
        {
            $item_claim_additional_proof_file_name = strval($login_user->id) . uniqid() . '.' . $request->item_claim_additional_upload->extension();
            $request->item_claim_additional_upload->storeAs('item_claim_doc', $item_claim_additional_proof_file_name);
        }

        $new_item_claim = new ItemClaim();
        $new_item_claim->user_id = $login_user->id;
        $new_item_claim->item_id = $item->id;
        $new_item_claim->item_claim_full_name = $item_claim_full_name;
        $new_item_claim->item_claim_phone = $item_claim_phone;
        $new_item_claim->item_claim_email = $item_claim_email;
        $new_item_claim->item_claim_additional_proof = $item_claim_additional_proof;
        $new_item_claim->item_claim_additional_upload = $item_claim_additional_proof_file_name;
        $new_item_claim->item_claim_status = ItemClaim::ITEM_CLAIM_STATUS_REQUESTED;
        $new_item_claim->save();

        \Session::flash('flash_message', __('item_claim.alert.user-item-claim-created-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.item-claims.edit', ['item_claim' => $new_item_claim]);
    }

    /**
     * Display the specified resource.
     *
     * @param ItemClaim $itemClaim
     * @return RedirectResponse
     */
    public function show(ItemClaim $itemClaim)
    {
        return redirect()->route('user.item-claims.edit', ['item_claim' => $itemClaim]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ItemClaim $itemClaim
     * @return Response
     */
    public function edit(ItemClaim $itemClaim)
    {
        Gate::authorize('edit-item-claim', $itemClaim);

        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('item_claim.seo.user-item-claims-edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item = $itemClaim->item()->first();
        $item_has_claimed = $item->hasClaimed();
        $item_claimed_user = $item->getClaimedUser();
        $item_claim = $itemClaim;

        return response()->view('backend.user.item.item-claim.edit',
            compact('item', 'item_claim', 'item_has_claimed', 'item_claimed_user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ItemClaim $itemClaim
     * @return RedirectResponse
     */
    public function update(Request $request, ItemClaim $itemClaim)
    {
        Gate::authorize('update-item-claim', $itemClaim);

        $request->validate([
            'item_claim_full_name' => 'required|max:255',
            'item_claim_phone' => 'nullable|max:255',
            'item_claim_email' => 'nullable|email|max:255',
            'item_claim_additional_proof' => 'nullable|max:65535',
            'item_claim_additional_upload' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $login_user = Auth::user();
        $item_claim_full_name = $request->item_claim_full_name;
        $item_claim_phone = $request->item_claim_phone;
        $item_claim_email = $request->item_claim_email;
        $item_claim_additional_proof = $request->item_claim_additional_proof;
        $item_claim_additional_proof_file_name = $itemClaim->item_claim_additional_upload;

        // start saving the user uploaded document
        if(!empty($request->item_claim_additional_upload))
        {
            $item_claim_additional_proof_file_name = strval($login_user->id) . uniqid() . '.' . $request->item_claim_additional_upload->extension();
            $request->item_claim_additional_upload->storeAs('item_claim_doc', $item_claim_additional_proof_file_name);

            if (!empty($itemClaim->item_claim_additional_upload)
                && Storage::disk('local')->exists('item_claim_doc/' . $itemClaim->item_claim_additional_upload)) {
                Storage::disk('local')->delete('item_claim_doc/' . $itemClaim->item_claim_additional_upload);
            }
        }

        $itemClaim->item_claim_full_name = $item_claim_full_name;
        $itemClaim->item_claim_phone = $item_claim_phone;
        $itemClaim->item_claim_email = $item_claim_email;
        $itemClaim->item_claim_additional_proof = $item_claim_additional_proof;
        $itemClaim->item_claim_additional_upload = $item_claim_additional_proof_file_name;

        if($itemClaim->item_claim_status != ItemClaim::ITEM_CLAIM_STATUS_APPROVED)
        {
            $itemClaim->item_claim_status = ItemClaim::ITEM_CLAIM_STATUS_REQUESTED;
        }
        $itemClaim->save();

        if($itemClaim->item_claim_status == ItemClaim::ITEM_CLAIM_STATUS_APPROVED)
        {
            \Session::flash('flash_message', __('item_claim.alert.user-item-claim-approved-updated-success'));
        }
        else
        {
            \Session::flash('flash_message', __('item_claim.alert.user-item-claim-updated-success'));
        }
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.item-claims.edit', ['item_claim' => $itemClaim]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ItemClaim $itemClaim
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(ItemClaim $itemClaim)
    {
        Gate::authorize('delete-item-claim', $itemClaim);

        $itemClaim->deleteItemClaim();

        \Session::flash('flash_message', __('item_claim.alert.user-item-claim-deleted-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.item-claims.index');
    }

    public function downloadItemClaimDoc(ItemClaim $itemClaim)
    {
        Gate::authorize('download-item-claim', $itemClaim);

        $file_name = $itemClaim->item_claim_additional_upload;

        if(empty($file_name))
        {
            abort(404);
        }
        else
        {
            return response()->download(storage_path('app/item_claim_doc/') . $file_name);
        }
    }
}
