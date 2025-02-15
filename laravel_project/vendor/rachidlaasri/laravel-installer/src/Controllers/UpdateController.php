<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\Setting;
use App\SettingLicense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;
use RachidLaasri\LaravelInstaller\Helpers\InstalledFileManager;
use Illuminate\Support\Facades\File;

class UpdateController extends Controller
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    const CODE_CANYON_ITEM_ID = 26890239;
    const CODE_CANYON_TOKEN = 'RJjSw6BS3BaetGKJ3JqzpEOZIAirlSJ6';
    const CODE_CANYON_USER_AGENT = 'Purchase Code Verification';

    /**
     * Display the updater welcome page.
     *
     * @return View
     */
    public function welcome()
    {
        /**
         * start my custom code, clear all laravel cache before update.
         */
        $settings = Setting::find(1);

        if(empty($settings->setting_site_last_cached_at))
        {
            clear_website_cache();
        }
        else
        {
            // check if the laravel_project/theme_views folder exist due to the theme system update
            if(!File::exists(base_path(\App\Theme::THEME_VIEWS)))
            {
                File::makeDirectory(base_path(\App\Theme::THEME_VIEWS));
            }

            clear_website_cache();
            generate_website_route_cache();
            generate_website_view_cache();
        }
        /**
         * end my custom code, clear all laravel cache before update.
         */

        return view('vendor.installer.update.welcome');
    }

    /**
     * Display the updater overview page.
     *
     * @return View
     */
    public function overview()
    {
        $migrations = $this->getMigrations();
        $dbMigrations = $this->getExecutedMigrations();

        $settings_license_purchase_code = '';
        $settings_license_codecanyon_username = '';

        if(Schema::hasTable('settings_license'))
        {
            $setting_license = SettingLicense::where('setting_id', 1)->first();
            $settings_license_purchase_code = $setting_license->settings_license_purchase_code;
            $settings_license_codecanyon_username = $setting_license->settings_license_codecanyon_username;
        }

        return view('vendor.installer.update.overview',
            [
                'numberOfUpdatesPending' => count($migrations) - count($dbMigrations),
                'settings_license_purchase_code' => $settings_license_purchase_code,
                'settings_license_codecanyon_username' => $settings_license_codecanyon_username
            ]);
    }

    /**
     * Migrate and seed the database.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function database(Request $request)
    {

        // license check starts here
        $app_purchase_code = empty($request->app_purchase_code) ? '' : $request->app_purchase_code;
        $to_verify_codecanyon_username = empty($request->to_verify_codecanyon_username) ? '' : $request->to_verify_codecanyon_username;

        $verify_response = do_license_verify(
            SettingLicense::LICENSE_API_HOST.SettingLicense::LICENSE_API_ROUTE_VERIFY,
            $app_purchase_code,
            $to_verify_codecanyon_username,
            4);

        $verify_response_status = $verify_response['status'];
        $verify_response_message = $verify_response['message'];
        $verify_response_body = $verify_response['body'];

        if($verify_response_status)
        {
            if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS)
            {
                eval($verify_response_body->proceed_passcode);

                return redirect()->route('LaravelUpdater::final')
                    ->with(['message' => $response]);

            }
            else
            {
                return redirect()->route('LaravelUpdater::overview')->withInput()->withErrors([
                    'app_purchase_code' => $verify_response_body->status_message,
                ]);
            }
        }
        else
        {
            return redirect()->route('LaravelUpdater::overview')->withInput()->withErrors([
                'app_purchase_code' => $verify_response_message,
            ]);
        }
        // license check ends here
    }

    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return View
     */
    public function finish(InstalledFileManager $fileManager)
    {
        $fileManager->update();

        return view('vendor.installer.update.finished');
    }

    private function verifyPurchase(string $purchase_code, string $to_verify_codecanyon_username)
    {
        $code = $purchase_code;
        $personalToken = self::CODE_CANYON_TOKEN;
        $userAgent = self::CODE_CANYON_USER_AGENT;

        // Surrounding whitespace can cause a 404 error, so trim it first
        $code = trim($code);

        // Make sure the code looks valid before sending it to Envato
        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $code))
        {
            return array('verified' => false, 'message' => "Invalid purchase code");
            //throw new Exception("Invalid code");
        }

        // Build the request
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://api.envato.com/v3/market/author/sale?code={$code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,

            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer {$personalToken}",
                "User-Agent: {$userAgent}"
            )
        ));

        // Send the request with warnings supressed
        $response = @curl_exec($ch);

        // Handle connection errors (such as an API outage)
        // You should show users an appropriate message asking to try again later
        if (curl_errno($ch) > 0) {
            return array('verified' => false, 'message' => "Error connecting to API: " . curl_error($ch));
            //throw new Exception("Error connecting to API: " . curl_error($ch));
        }

        // If we reach this point in the code, we have a proper response!
        // Let's get the response code to check if the purchase code was found
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // HTTP 404 indicates that the purchase code doesn't exist
        if ($responseCode === 404) {
            return array('verified' => false, 'message' => "The purchase code was invalid");
            //throw new Exception("The purchase code was invalid");
        }

        // Anything other than HTTP 200 indicates a request or API error
        // In this case, you should again ask the user to try again later
        if ($responseCode !== 200) {
            return array('verified' => false, 'message' => "Failed to validate code due to an error: HTTP {$responseCode}");
            //throw new Exception("Failed to validate code due to an error: HTTP {$responseCode}");
        }

        // Parse the response into an object with warnings supressed
        $body = @json_decode($response);

        // Check for errors while decoding the response (PHP 5.3+)
        if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            return array('verified' => false, 'message' => "Error parsing response");
            //throw new Exception("Error parsing response");
        }

        // Now we can check the details of the purchase code
        // At this point, you are guaranteed to have a code that belongs to you
        // You can apply logic such as checking the item's id and buyer's username.

        $id = $body->item->id; // (int) 26890239
        $codecanyon_username = strtolower($body->buyer);

        if($id == self::CODE_CANYON_ITEM_ID && $to_verify_codecanyon_username == $codecanyon_username)
        {
            return array('verified' => true, 'message' => null);
        }
        else
        {
            return array('verified' => false, 'message' => "Cannot verify the purchase code");
        }
    }
}
