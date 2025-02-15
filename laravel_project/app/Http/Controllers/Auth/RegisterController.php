<?php

namespace App\Http\Controllers\Auth;

use App\Customization;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\Setting;
use App\SocialLogin;
use App\Subscription;
use App\Theme;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_recaptcha_sign_up_enable == Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE)
        {
            // Start Google reCAPTCHA version 2
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);

            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'g-recaptcha-response' => ['recaptcha'],
            ]);
        }
        else
        {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $new_user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'   => Role::USER_ROLE_ID,
            'user_suspended' => User::USER_NOT_SUSPENDED,
        ]);

        // assign the new user a subscription with free plan
        $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();
        $free_subscription = new Subscription(array(
            'user_id' => $new_user->id,
            'plan_id' => $free_plan->id,
            'subscription_start_date' => Carbon::now()->toDateString(),
//            'subscription_max_featured_listing' => is_null($free_plan->plan_max_featured_listing) ? null : $free_plan->plan_max_featured_listing,
//            'subscription_max_free_listing' => is_null($free_plan->plan_max_free_listing) ? null : $free_plan->plan_max_free_listing,
        ));
        $new_free_subscription = $new_user->subscription()->save($free_subscription);

        return $new_user;
    }

    public function showRegistrationForm()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.auth.register', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Start social login
         */
        $social_logins = new SocialLogin();
        $social_login_facebook = $social_logins->isFacebookEnabled();
        $social_login_google = $social_logins->isGoogleEnabled();
        $social_login_twitter = $social_logins->isTwitterEnabled();
        $social_login_linkedin = $social_logins->isLinkedInEnabled();
        $social_login_github = $social_logins->isGitHubEnabled();
        /**
         * End social login
         */


        /**
         * Start inner page header customization
         */
        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;
        /**
         * End inner page header customization
         */

        /**
         * Start initial blade view file path
         */
        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();
        /**
         * End initial blade view file path
         */

        /**
         * Start initial Google reCAPTCHA version 2
         */
        if($settings->setting_site_recaptcha_sign_up_enable == Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE)
        {
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
        }
        /**
         * End initial Google reCAPTCHA version 2
         */

        return view($theme_view_path . 'auth.register',
            compact('social_login_facebook', 'social_login_google',
                'social_login_twitter', 'social_login_linkedin', 'social_login_github',
                'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start initial SMTP settings
         */
        if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
        {
            // config SMTP
            config_smtp(
                $settings->settings_site_smtp_sender_name,
                $settings->settings_site_smtp_sender_email,
                $settings->settings_site_smtp_host,
                $settings->settings_site_smtp_port,
                $settings->settings_site_smtp_encryption,
                $settings->settings_site_smtp_username,
                $settings->settings_site_smtp_password
            );
        }
        /**
         * End initial SMTP settings
         */

        if(!empty($settings->setting_site_name))
        {
            // set up APP_NAME
            config([
                'app.name' => $settings->setting_site_name,
            ]);
        }

        $this->validator($request->all())->validate();

        /**
         * Use try catch to avoid the 500 internal server error due to the SMTP email
         * may not able to send. The purpose is to improve the user experience.
         */
        try
        {
            event(new Registered($user = $this->create($request->all())));
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
        }

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
