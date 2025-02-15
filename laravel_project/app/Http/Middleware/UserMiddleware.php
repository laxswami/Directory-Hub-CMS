<?php

namespace App\Http\Middleware;

use App\Role;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class UserMiddleware
{

    public function handle($request, Closure $next)
    {
        // check if user login and has user role
        if(Auth::check() && Auth::user()->isUser())
        {
            // check if user account suspended
            if(Auth::user()->hasActive())
            {
                return $next($request);
            }
            else
            {
                $settings = app('site_global_settings');
                /**
                 * Start SEO
                 */
                SEOMeta::setTitle(__('seo.backend.user.profile.suspended', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
                SEOMeta::setDescription('');
                SEOMeta::setCanonical(URL::current());
                SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
                /**
                 * End SEO
                 */
                return response()->view('backend.user.suspend');
            }


        }else{
            return redirect()->route('login');
        }
    }
}
