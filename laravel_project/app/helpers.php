<?php

use App\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

if (! function_exists('get_item_slug')) {
    function get_item_slug()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_';
        $item_slug = '';

        $item_slug_found = true;

        while($item_slug_found)
        {
            for ($i = 0; $i < 11; $i++)
            {
                $item_slug .= $characters[mt_rand(0, 63)];
            }

            $item_exist = \App\Item::where('item_slug', $item_slug)->count();
            if($item_exist == 0)
            {
                $item_slug_found = false;
            }
        }

        return $item_slug;
    }
}

if (! function_exists('site_already_installed')) {
    function site_already_installed()
    {
        return file_exists(storage_path('installed'));
    }
}

if (! function_exists('generate_symlink')) {
    function generate_symlink()
    {
        // create a storage symbolic link in public folder and website root before run installer
        $target = storage_path('app'. DIRECTORY_SEPARATOR .'public');
        $link = public_path('storage');
        $blog_link = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'storage';
        $public_storage = $link;
        $root_storage = $blog_link;

        $vendor_target = $_SERVER["DOCUMENT_ROOT"]. DIRECTORY_SEPARATOR . 'vendor';
        $vendor_link = public_path('vendor');
        $vendor_symlink = $vendor_link;


        if(file_exists($public_storage))
        {
            unlink($public_storage);
        }
        if(file_exists($root_storage))
        {
            unlink($root_storage);
        }
        if(file_exists($vendor_symlink))
        {
            unlink($vendor_symlink);
        }

        symlink($target, $link);
        symlink($target, $blog_link);
        symlink($vendor_target, $vendor_link);
    }
}

if (! function_exists('is_demo_mode')) {
    function is_demo_mode()
    {
        return env("DEMO_MODE", false);
    }
}

if (! function_exists('config_smtp')) {
    function config_smtp($from_name, $from_email, $smtp_host, $smtp_port, $smtp_encryption, $smtp_username, $smtp_password)
    {
        $encryption = null;
        if($smtp_encryption == Setting::SITE_SMTP_ENCRYPTION_SSL)
        {
            $encryption = Setting::SITE_SMTP_ENCRYPTION_SSL_STR;
        }
        elseif($smtp_encryption == Setting::SITE_SMTP_ENCRYPTION_TLS)
        {
            $encryption = Setting::SITE_SMTP_ENCRYPTION_TLS_STR;
        }

        config([
            'mail.host' => $smtp_host,
            'mail.port' => $smtp_port,
            'mail.from' => ['address' => $from_email, 'name' => $from_name],
            'mail.encryption' => $encryption,
            'mail.username' => $smtp_username,
            'mail.password' => $smtp_password,
        ]);
    }
}

if (! function_exists('config_re_captcha')) {
    function config_re_captcha($recaptcha_site_key, $recaptcha_secret_key)
    {
        config([
            'recaptcha.api_site_key' => $recaptcha_site_key,
            'recaptcha.api_secret_key' => $recaptcha_secret_key,
        ]);
    }
}

if (! function_exists('generate_website_route_cache')) {
    function generate_website_route_cache()
    {
        Artisan::call('route:cache');
    }
}

if (! function_exists('generate_website_view_cache')) {
    function generate_website_view_cache()
    {
        Artisan::call('view:cache');
    }
}

if (! function_exists('clear_website_cache')) {
    function clear_website_cache()
    {
        Artisan::call('optimize:clear');
    }
}

if (! function_exists('do_license_verify')) {
    function do_license_verify($url,
                               $codecanyon_purchase_code,
                               $codecanyon_username,
                               $call_function,
                               $revoke_domain=null)
    {
        try
        {
            $register_domain = $_SERVER['HTTP_HOST'];

            if(empty($register_domain))
            {
                return array(
                    'status' => false,
                    'message' => __('license_verify.helper-redirect-no-url'),
                    'body' => null,
                );
            }
            else
            {
                /**
                 * Start curl function
                 */
                $data = http_build_query(array(
                    'api_secret' => \App\SettingLicense::LICENSE_API_SECRET,
                    'register_domain' => $register_domain,
                    'purchase_code' => $codecanyon_purchase_code,
                    'codecanyon_username' => strtolower($codecanyon_username),
                    'call_function' => $call_function,
                    'revoke_domain' => $revoke_domain,
                ));

                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_POSTFIELDS => $data,
                ));

                // Send the request with warnings supressed
                $result = @curl_exec($ch);

                // Handle connection errors (such as an API outage)
                // You should show users an appropriate message asking to try again later
                if (curl_errno($ch) > 0) {

                    Log::error(curl_error($ch));

                    /* Handle error */
                    return array(
                        'status' => false,
                        'message' => __('license_verify.helper-redirect-unknown-error'),
                        'body' => null,
                    );
                }

                // handle curl error
                if ($result === false)
                {
                    /* Handle error */
                    return array(
                        'status' => false,
                        'message' => __('license_verify.helper-redirect-connect-fail'),
                        'body' => null,
                    );
                }

                $body = @json_decode($result);

                // Close cURL session handle
                curl_close($ch);

                return array(
                    'status' => true,
                    'message' => "",
                    'body' => $body,
                );
                /**
                 * End curl function
                 */
            }
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage() . "\n" . $e->getTraceAsString());

            return array(
                'status' => false,
                'message' => __('license_verify.helper-redirect-unknown-error'),
                'body' => null,
            );
        }
    }
}

if (! function_exists('get_domain_verify_token_url')) {
    function get_domain_verify_token_url($codecanyon_purchase_code, $codecanyon_username)
    {
        $url = \App\SettingLicense::LICENSE_API_HOST . \App\SettingLicense::LICENSE_API_ROUTE_DOMAIN_VERIFY_TOKEN;

        $register_domain = $_SERVER['HTTP_HOST'];

        $domain_host_hash = sha1($register_domain);
        $license_purchase_code_codecanyon_username_hash = sha1($codecanyon_purchase_code . strtolower($codecanyon_username));

        return $url . '?domain_host_hash=' . $domain_host_hash . '&license_purchase_code_codecanyon_username_hash=' . $license_purchase_code_codecanyon_username_hash;
    }
}

if (! function_exists('get_domain_verify_do_url')) {
    function get_domain_verify_do_url($codecanyon_purchase_code, $codecanyon_username)
    {
        $url = \App\SettingLicense::LICENSE_API_HOST . \App\SettingLicense::LICENSE_API_ROUTE_DOMAIN_VERIFY_DO;

        $register_domain = $_SERVER['HTTP_HOST'];

        $domain_host_hash = sha1($register_domain);
        $license_purchase_code_codecanyon_username_hash = sha1($codecanyon_purchase_code . strtolower($codecanyon_username));

        return $url . '?domain_host_hash=' . $domain_host_hash . '&license_purchase_code_codecanyon_username_hash=' . $license_purchase_code_codecanyon_username_hash;
    }
}
if (! function_exists('get_vincenty_great_circle_distance')) {

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function get_vincenty_great_circle_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}
