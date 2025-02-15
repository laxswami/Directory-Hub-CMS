<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Installed
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
        if (site_already_installed()) {
            return $next($request);
        }
        else
        {
            /**
             * Check if symlink function is enabled.
             * Some hosting servers disabled symlink function, which will cause 500 error.
             * So, we want check first, then notify users if symlink has been disabled before hit 500 error.
             */
            if (function_exists("symlink"))
            {
                generate_symlink();

                return redirect()->route('LaravelInstaller::welcome');
            }
            else
            {
                echo "<p><strong>Warning: </strong>The script detects PHP function symlink() has disabled on your hosting server, please contact your hosting server customer service, and ask them to enabled symlink() function for you. Otherwise, the Directory Hub script will not able to install successfully.</p>";
                echo "<p>If you have any questions about this warning, please email me at: <a href='https://codecanyon.net/user/lduruo10' target='_blank'>https://codecanyon.net/user/lduruo10</a></p>";
                die();
            }
        }

    }
}
