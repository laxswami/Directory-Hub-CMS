<?php

namespace App\Http\Controllers\Auth;

use App\Customization;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\Setting;
use App\SocialiteAccount;
use App\SocialLogin;
use App\Subscription;
use App\Theme;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {
        if ($user->isAdmin())
        {
            $this->redirectTo = route('admin.index');
            //$this->redirectTo = route('page.home');
        }

        if ($user->isUser())
        {
            // if the website is in maintenance mode, forcely logout user
            $settings = app('site_global_settings');
            if($settings->setting_site_maintenance_mode == Setting::SITE_MAINTENANCE_MODE_ON)
            {
                Auth::logout();
            }

            //$this->redirectTo = route('user.index');
            $this->redirectTo = route('page.home');
        }
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     *
     */
    protected function validateLogin(Request $request)
    {
        $settings = app('site_global_settings');

        if($settings->setting_site_recaptcha_login_enable == Setting::SITE_RECAPTCHA_LOGIN_ENABLE)
        {
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);

            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
                'g-recaptcha-response' => 'recaptcha',
            ]);
        }
        else
        {
            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    }

    public function showLoginForm()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.auth.login', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
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
        if($settings->setting_site_recaptcha_login_enable == Setting::SITE_RECAPTCHA_LOGIN_ENABLE)
        {
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
        }
        /**
         * End initial Google reCAPTCHA version 2
         */

        return view($theme_view_path . 'auth.login',
            compact('social_login_facebook', 'social_login_google',
                'social_login_twitter', 'social_login_linkedin', 'social_login_github',
                'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
    }

    public function redirectToFacebook()
    {
        $social_logins = new SocialLogin();
        $social_login_facebook = $social_logins->getFacebookLogin();
        if($social_login_facebook->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.facebook' => array(
                    'client_id' => $social_login_facebook->social_login_provider_client_id,
                    'client_secret' => $social_login_facebook->social_login_provider_client_secret,
                    'redirect' => route('auth.login.facebook.callback'),
                )]
            );

            return Socialite::driver('facebook')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-facebook-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }

    }

    public function handleFacebookCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_facebook = $social_logins->getFacebookLogin();

            if($social_login_facebook->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-facebook-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.facebook' => array(
                    'client_id' => $social_login_facebook->social_login_provider_client_id,
                    'client_secret' => $social_login_facebook->social_login_provider_client_secret,
                    'redirect' => route('auth.login.facebook.callback'),
                )]
            );

            $user = Socialite::driver('facebook')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_FACEBOOK);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-facebook-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToGoogle()
    {
        $social_logins = new SocialLogin();
        $social_login_google = $social_logins->getGoogleLogin();
        if($social_login_google->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.google' => array(
                    'client_id' => $social_login_google->social_login_provider_client_id,
                    'client_secret' => $social_login_google->social_login_provider_client_secret,
                    'redirect' => route('auth.login.google.callback'),
                )]
            );

            return Socialite::driver('google')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-google-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }

    public function handleGoogleCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_google = $social_logins->getGoogleLogin();

            if($social_login_google->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-google-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.google' => array(
                    'client_id' => $social_login_google->social_login_provider_client_id,
                    'client_secret' => $social_login_google->social_login_provider_client_secret,
                    'redirect' => route('auth.login.google.callback'),
                )]
            );

            $user = Socialite::driver('google')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_GOOGLE);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-google-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToTwitter()
    {
        $social_logins = new SocialLogin();
        $social_login_twitter = $social_logins->getTwitterLogin();
        if($social_login_twitter->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.twitter' => array(
                    'client_id' => $social_login_twitter->social_login_provider_client_id,
                    'client_secret' => $social_login_twitter->social_login_provider_client_secret,
                    'redirect' => route('auth.login.twitter.callback'),
                )]
            );

            return Socialite::driver('twitter')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-twitter-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }
    public function handleTwitterCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_twitter = $social_logins->getTwitterLogin();

            if($social_login_twitter->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-twitter-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.twitter' => array(
                    'client_id' => $social_login_twitter->social_login_provider_client_id,
                    'client_secret' => $social_login_twitter->social_login_provider_client_secret,
                    'redirect' => route('auth.login.twitter.callback'),
                )]
            );

            $user = Socialite::driver('twitter')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_TWITTER);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-twitter-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToLinkedIn()
    {
        $social_logins = new SocialLogin();
        $social_login_linkedin = $social_logins->getLinkedInLogin();
        if($social_login_linkedin->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.linkedin' => array(
                    'client_id' => $social_login_linkedin->social_login_provider_client_id,
                    'client_secret' => $social_login_linkedin->social_login_provider_client_secret,
                    'redirect' => route('auth.login.linkedin.callback'),
                )]
            );

            return Socialite::driver('linkedin')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-linkedin-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }
    public function handleLinkedInCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_linkedin = $social_logins->getLinkedInLogin();

            if($social_login_linkedin->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-linkedin-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.linkedin' => array(
                    'client_id' => $social_login_linkedin->social_login_provider_client_id,
                    'client_secret' => $social_login_linkedin->social_login_provider_client_secret,
                    'redirect' => route('auth.login.linkedin.callback'),
                )]
            );

            $user = Socialite::driver('linkedin')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_LINKEDIN);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-linkedin-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    public function redirectToGitHub()
    {
        $social_logins = new SocialLogin();
        $social_login_github = $social_logins->getGitHubLogin();
        if($social_login_github->social_login_enabled == SocialLogin::SOCIAL_LOGIN_ENABLED)
        {
            config(
                ['services.github' => array(
                    'client_id' => $social_login_github->social_login_provider_client_id,
                    'client_secret' => $social_login_github->social_login_provider_client_secret,
                    'redirect' => route('auth.login.github.callback'),
                )]
            );

            return Socialite::driver('github')->redirect();
        }
        else
        {
            \Session::flash('flash_message', __('social_login.frontend.error-github-disabled'));
            \Session::flash('flash_type', 'danger');

            return back();
        }
    }
    public function handleGitHubCallback()
    {
        try {

            $social_logins = new SocialLogin();
            $social_login_github = $social_logins->getGitHubLogin();

            if($social_login_github->social_login_enabled == SocialLogin::SOCIAL_LOGIN_DISABLED)
            {
                \Session::flash('flash_message', __('social_login.frontend.error-github-disabled'));
                \Session::flash('flash_type', 'danger');

                return redirect()->route('login');
            }

            config(
                ['services.github' => array(
                    'client_id' => $social_login_github->social_login_provider_client_id,
                    'client_secret' => $social_login_github->social_login_provider_client_secret,
                    'redirect' => route('auth.login.github.callback'),
                )]
            );

            $user = Socialite::driver('github')->user();

            $find_user = $this->createOrGetSocialLoginUser($user, SocialLogin::SOCIAL_LOGIN_GITHUB);

            Auth::login($find_user);

            return redirect()->route('page.home');

        }
        catch(Exception $e)
        {
            \Session::flash('flash_message', __('social_login.error-github-callback'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('login');
        }
    }

    private function createOrGetSocialLoginUser($social_login_user, $social_login_provider)
    {
        $social_account = SocialiteAccount::where('socialite_account_provider_name', $social_login_provider)
            ->where('socialite_account_provider_id', $social_login_user->id)
            ->get()
            ->first();

        if($social_account)
        {
            return $social_account->user;
        }
        else
        {
            $new_social_account = new SocialiteAccount([
                'socialite_account_provider_id' => $social_login_user->id,
                'socialite_account_provider_name' => $social_login_provider,
            ]);

            $new_social_account_email = empty($social_login_user->email) ? strtolower($social_login_provider) . "-" . $social_login_user->id . "@mail.com" : $social_login_user->email;

            $find_user = User::where('email', $new_social_account_email)->first();

            if(!$find_user)
            {
                $find_user =  User::create([
                    'name' => $social_login_user->name,
                    'email' => $new_social_account_email,
                    'password' => Hash::make(uniqid()),
                    'role_id'   => Role::USER_ROLE_ID,
                    'user_suspended' => User::USER_NOT_SUSPENDED,
                    'email_verified_at' => date("Y-m-d H:i:s"),
                ]);

                // assign the new user a subscription with free plan
                $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();
                $free_subscription = new Subscription(array(
                    'user_id' => $find_user->id,
                    'plan_id' => $free_plan->id,
                    'subscription_start_date' => Carbon::now()->toDateString(),
//                    'subscription_max_featured_listing' => is_null($free_plan->plan_max_featured_listing) ? null : $free_plan->plan_max_featured_listing,
//                    'subscription_max_free_listing' => is_null($free_plan->plan_max_free_listing) ? null : $free_plan->plan_max_free_listing,
                ));
                $new_free_subscription = $find_user->subscription()->save($free_subscription);
            }

            $new_social_account->user()->associate($find_user);
            $new_social_account->save();

            return $find_user;
        }
    }
}
