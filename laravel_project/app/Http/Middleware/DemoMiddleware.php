<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Support\Facades\App;

class DemoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $demo_mode = env("DEMO_MODE", false);

        $route_name = $request->route()->getName();

        // For demo mode, all requests except GET are avoid.
        if($demo_mode)
        {
            if($request->isMethod('get')
                && $route_name != 'page.locale.update'
                && $route_name != 'page.country.update')
            {
                return $next($request);
            }
            if(!$request->isMethod('get') && $request->is('login', 'logout', 'search'))
            {
                return $next($request);
            }
            else
            {
                \Session::flash('flash_message', __('alert.demo-mode-prohibited'));
                \Session::flash('flash_type', 'danger');

                return back();
            }


        } else {

            return $next($request);
        }
    }
}
