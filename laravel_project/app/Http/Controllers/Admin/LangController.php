<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class LangController extends Controller
{

    public function syncIndex(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Create City - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('trans.sync-lang-seo-title', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.lang.sync.index');
    }

    public function syncDo(Request $request)
    {
        define('STDIN',fopen("php://stdin","r"));

        if (function_exists("ini_set"))
        {
            // we need to increase the execution time
            ini_set('max_execution_time', 3600); //3600 seconds = 60 minutes
        }

        $lang_in_db = DB::table('translations')->count();

        if($lang_in_db > 0)
        {
            /**
             * Start sync languages for existing users
             */
            $sync_missing_translation = Artisan::call('translation:sync-translations', [
                'from' => 'database',
                'to' => 'file',
                'language' => 'all',
            ]);
            $sync_missing_translation = Artisan::call('translation:sync-translations', [
                'from' => 'file',
                'to' => 'database',
                'language' => 'all',
            ]);
            /**
             * End sync languages for existing users
             */
        }
        else
        {
            /**
             * Start sync languages for first time user
             */
            $sync_missing_translation = Artisan::call('translation:sync-translations', [
                'from' => 'file',
                'to' => 'database',
                'language' => 'all',
            ]);
            /**
             * End sync languages for first time user
             */
        }

        // now set translation.driver to database.
        $path = config_path('translation.php');
        $contents = File::get($path);

        $contents = str_replace("'driver' => 'file',", "'driver' => 'database',", $contents);

        File::put($path, $contents);

        \Session::flash('flash_message', __('trans.alert.sync-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.lang.sync.index');
    }

    public function syncRestore(Request $request)
    {
        // now set translation.driver to file.
        $path = config_path('translation.php');
        $contents = File::get($path);

        $contents = str_replace("'driver' => 'database',", "'driver' => 'file',", $contents);

        File::put($path, $contents);

        \Session::flash('flash_message', __('trans.alert.sync-restore-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.lang.sync.index');
    }
}
