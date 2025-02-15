<?php

namespace App\Http\Controllers\User;

use App\Country;
use App\Http\Controllers\Controller;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function editProfile(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Edit Profile - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.profile.edit-profile', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $all_countries = Country::orderBy('country_name')->get();

        return response()->view('backend.user.profile.edit',
            compact('login_user', 'all_countries'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'user_prefer_language' => 'nullable|max:5',
            'user_prefer_country_id' => 'nullable|numeric',
        ]);

        $name = $request->name;
        $email = $request->email;
        $user_about = $request->user_about;
        $user_prefer_language = empty($request->user_prefer_language) ? null : $request->user_prefer_language;
        $user_prefer_country_id = empty($request->user_prefer_country_id) ? null : $request->user_prefer_country_id;

        $login_user = Auth::user();

        $validate_error = array();
        $email_exist = User::where('email', $email)
            ->where('id', '!=', $login_user->id)
            ->count();
        if($email_exist > 0)
        {
            $validate_error['email'] = __('prefer_country.error.user-email-exist');
        }

        if(!empty($user_prefer_country_id))
        {
            $country_exist = Country::find($user_prefer_country_id);
            if(!$country_exist)
            {
                $validate_error['user_prefer_country_id'] = __('prefer_country.alert.country-not-found');
            }
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        else
        {
            $user_image = $request->user_image;
            $user_image_name = $login_user->user_image;
            if(!empty($user_image)){

                $currentDate = Carbon::now()->toDateString();

                $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';

                if(!Storage::disk('public')->exists('user')){
                    Storage::disk('public')->makeDirectory('user');
                }
                if(Storage::disk('public')->exists('user/' . $login_user->user_image)){
                    Storage::disk('public')->delete('user/' . $login_user->user_image);
                }

                $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);

                Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
            }

            $login_user->name = $name;
            $login_user->email = $email;
            $login_user->user_about = $user_about;
            $login_user->user_image = $user_image_name;
            $login_user->user_prefer_language = $user_prefer_language;
            $login_user->user_prefer_country_id = $user_prefer_country_id;
            $login_user->save();

            \Session::flash('flash_message', __('alert.user-profile-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.profile.edit');
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editProfilePassword(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Change Password - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.profile.change-profile-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.user.profile.password.edit');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        $login_user = Auth::user();

        // check current password
        $current_password = $request->password;
        if(!Hash::check($current_password, $login_user->password))
        {
            throw ValidationException::withMessages(['password' => 'Current password wrong.']);
        }

        // change password
        $login_user->password = bcrypt($request->new_password);
        $login_user->save();

        \Session::flash('flash_message', __('alert.user-profile-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.profile.edit');
    }
}
