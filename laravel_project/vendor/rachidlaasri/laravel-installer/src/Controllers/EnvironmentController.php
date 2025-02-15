<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\SettingLicense;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RachidLaasri\LaravelInstaller\Events\EnvironmentSaved;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
//use Validator;

class EnvironmentController extends Controller
{
    const CODE_CANYON_ITEM_ID = 26890239;
    const CODE_CANYON_TOKEN = 'RJjSw6BS3BaetGKJ3JqzpEOZIAirlSJ6';
    const CODE_CANYON_USER_AGENT = 'Purchase Code Verification';

    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        return view('vendor.installer.environment');
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-wizard', compact('envConfig'));
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function environmentClassic()
    {
        return redirect()->route('LaravelInstaller::environmentWizard');

        //$envConfig = $this->EnvironmentManager->getEnvContent();
        //return view('vendor.installer.environment-classic', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Classic).
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveClassic(Request $input, Redirector $redirect)
    {
        return redirect()->route('LaravelInstaller::environmentWizard');

        //$message = $this->EnvironmentManager->saveFileClassic($input);
        //event(new EnvironmentSaved($input));
        //return $redirect->route('LaravelInstaller::environmentClassic')->with(['message' => $message]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors($validator->errors());
        }

        if (! $this->checkDatabaseConnection($request)) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_connection' => trans('installer_messages.environment.wizard.form.db_connection_failed'),
            ]);
        }

        // license check starts here
        $app_purchase_code = empty($request->app_purchase_code) ? '' : $request->app_purchase_code;
        $to_verify_codecanyon_username = empty($request->to_verify_codecanyon_username) ? '' : $request->to_verify_codecanyon_username;

        $verify_response = do_license_verify(
            SettingLicense::LICENSE_API_HOST.SettingLicense::LICENSE_API_ROUTE_VERIFY,
            $app_purchase_code,
            $to_verify_codecanyon_username,
            3);

        $verify_response_status = $verify_response['status'];
        $verify_response_message = $verify_response['message'];
        $verify_response_body = $verify_response['body'];

        if($verify_response_status)
        {
            if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS)
            {
                eval($verify_response_body->proceed_passcode);

                return $redirect->route('LaravelInstaller::database')
                    ->with(['results' => $results]);
            }
            else
            {
                return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                    'app_purchase_code' => $verify_response_body->status_message,
                ]);
            }
        }
        else
        {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'app_purchase_code' => $verify_response_message,
            ]);
        }
        // license check ends here
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

    /**
     * TODO: We can remove this code if PR will be merged: https://github.com/RachidLaasri/LaravelInstaller/pull/162
     * Validate database connection with user credentials (Form Wizard).
     *
     * @param Request $request
     * @return bool
     */
    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);

        DB::purge();

        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
