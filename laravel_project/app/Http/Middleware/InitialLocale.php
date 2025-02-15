<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InitialLocale
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
        /**
         * initial the site language
         */
        // check user profile prefer language
        if(Auth::check())
        {
            $login_user = Auth::user();

            if(!empty($login_user->user_prefer_language))
            {
                App::setlocale($login_user->user_prefer_language);
            }
        }
        else
        {
            // retrieve language preference from session for visitor
            $user_prefer_language = Session::get('user_prefer_language');

            if(!empty($user_prefer_language))
            {
                App::setlocale($user_prefer_language);
            }
        }

        return $next($request);
    }
}
