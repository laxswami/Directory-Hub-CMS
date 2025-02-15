<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SocialLogin;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SocialLoginController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('social_login.seo.index-login', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_social_logins = SocialLogin::orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.social-login.index',
            compact('all_social_logins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return redirect()->route('admin.social-logins.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.social-logins.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SocialLogin  $socialLogin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(SocialLogin $socialLogin)
    {
        return redirect()->route('admin.social-logins.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SocialLogin  $socialLogin
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialLogin $socialLogin)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('social_login.seo.edit-login', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $social_login = $socialLogin;

        return response()->view('backend.admin.social-login.edit',
            compact('social_login'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SocialLogin  $socialLogin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, SocialLogin $socialLogin)
    {
        $request->validate([
            //'social_login_provider_name' => 'required|max:255',
            'social_login_provider_client_id' => 'nullable|max:255',
            'social_login_provider_client_secret' => 'nullable|max:255',
            'social_login_enabled' => 'required|numeric|min:0|max:1',
        ]);

        $social_login_provider_client_id = $request->social_login_provider_client_id;
        $social_login_provider_client_secret = $request->social_login_provider_client_secret;
        $social_login_enabled = $request->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED ? SocialLogin::SOCIAL_LOGIN_ENABLED : SocialLogin::SOCIAL_LOGIN_DISABLED;

        $socialLogin->social_login_provider_client_id = $social_login_provider_client_id;
        $socialLogin->social_login_provider_client_secret = $social_login_provider_client_secret;
        $socialLogin->social_login_enabled = $social_login_enabled;
        $socialLogin->save();

        \Session::flash('flash_message', __('social_login.update-login-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.social-logins.edit', $socialLogin);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SocialLogin  $socialLogin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SocialLogin $socialLogin)
    {
        return redirect()->route('admin.social-logins.index');
    }
}
