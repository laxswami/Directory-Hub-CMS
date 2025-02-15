<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route_name = $request->route()->getName();

        if((!$request->isMethod('get') && $request->is('login', 'logout')) || $route_name == 'admin.settings.license.domain.verify')
        {
            // allow the login and logout post request go through so that the website admin
            // can still access the website frontend and backend.
            return $next($request);
        }
        else
        {
            /**
             * Start check if the website set to maintenance mode
             */
            $settings = app('site_global_settings');

            $show_maintenance_mode = false;
            if($settings->setting_site_maintenance_mode == Setting::SITE_MAINTENANCE_MODE_ON)
            {
                if(Auth::check())
                {
                    $login_user = Auth::user();

                    if($login_user->isUser())
                    {
                        $show_maintenance_mode = true;
                    }
                }
                else
                {
                    $show_maintenance_mode = true;
                }
            }
            /**
             * End check if the website set to maintenance mode
             */

            if($show_maintenance_mode)
            {
                /**
                 * Start initial Google reCAPTCHA version 2
                 */
                if($settings->setting_site_recaptcha_login_enable == Setting::SITE_RECAPTCHA_LOGIN_ENABLE)
                {
                    config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
                }
                /**
                 * End initial Google reCAPTCHA version 2
                 */

                // show maintenance mode page
                return response()->view('maintenance.index');
            }
            else
            {
                return $next($request);
            }
        }

    }
}
