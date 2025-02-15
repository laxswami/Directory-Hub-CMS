<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Role;
use App\Subscription;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.users', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $request_query_array = $request->query();

        $user_email_verified = $request->user_email_verified == User::USER_EMAIL_NOT_VERIFIED ? User::USER_EMAIL_NOT_VERIFIED : User::USER_EMAIL_VERIFIED;
        $user_suspended = $request->user_suspended == User::USER_SUSPENDED ? User::USER_SUSPENDED : User::USER_NOT_SUSPENDED;
        $order_by = empty($request->order_by) ? User::ORDER_BY_USER_NEWEST : $request->order_by;
        $count_per_page = empty($request->count_per_page) ? User::COUNT_PER_PAGE_10 : $request->count_per_page;

        $all_users_query = User::query();
        $all_users_query->where('role_id', Role::USER_ROLE_ID);

        // email verification query
        if($user_email_verified == User::USER_EMAIL_VERIFIED)
        {
            $all_users_query->where('email_verified_at', '!=', null);
        }
        else
        {
            $all_users_query->where('email_verified_at',null);
        }

        // account status query
        if($user_suspended == User::USER_SUSPENDED)
        {
            $all_users_query->where('user_suspended',User::USER_SUSPENDED);
        }
        else
        {
            $all_users_query->where('user_suspended',User::USER_NOT_SUSPENDED);
        }

        // order by
        if($order_by == User::ORDER_BY_USER_NEWEST)
        {
            $all_users_query->orderBy('created_at', 'DESC');
        }
        elseif($order_by == User::ORDER_BY_USER_OLDEST)
        {
            $all_users_query->orderBy('created_at', 'ASC');
        }
        elseif($order_by == User::ORDER_BY_USER_NAME_A_Z)
        {
            $all_users_query->orderBy('name', 'ASC');
        }
        elseif($order_by == User::ORDER_BY_USER_NAME_Z_A)
        {
            $all_users_query->orderBy('name', 'DESC');
        }
        elseif($order_by == User::ORDER_BY_USER_EMAIL_A_Z)
        {
            $all_users_query->orderBy('email', 'ASC');
        }
        elseif($order_by == User::ORDER_BY_USER_EMAIL_Z_A)
        {
            $all_users_query->orderBy('email', 'DESC');
        }

        $all_users_count = $all_users_query->count();
        $all_users = $all_users_query->paginate($count_per_page);

        // show all users except self (admin)
        //$all_users = User::where('role_id', Role::USER_ROLE_ID)->orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.user.index',
            compact('all_users', 'all_users_count', 'user_email_verified', 'user_suspended',
                'order_by', 'count_per_page', 'request_query_array'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.create-user', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_countries = Country::orderBy('country_name')->get();

        return response()->view('backend.admin.user.create',
            compact('all_countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|confirmed',
            'user_prefer_language' => 'nullable|max:5',
            'user_prefer_country_id' => 'nullable|numeric',
        ]);

        $name = $request->name;
        $email = $request->email;
        $email_verified_at = date("Y-m-d H:i:s");
        $password = bcrypt($request->password);
        $role_id = Role::USER_ROLE_ID;
        $user_about = $request->user_about;
        $user_prefer_language = empty($request->user_prefer_language) ? null : $request->user_prefer_language;
        $user_prefer_country_id = empty($request->user_prefer_country_id) ? null : $request->user_prefer_country_id;

        // validate country_id
        $validate_error = array();
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

        $user_image = $request->user_image;
        $user_image_name = null;
        if(!empty($user_image)){

            $currentDate = Carbon::now()->toDateString();

            $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';

            if(!Storage::disk('public')->exists('user')){
                Storage::disk('public')->makeDirectory('user');
            }

            $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);

            Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->email_verified_at = $email_verified_at;
        $user->password = $password;
        $user->role_id = $role_id;
        $user->user_about = $user_about;
        $user->user_image = $user_image_name;
        $user->user_prefer_language = $user_prefer_language;
        $user->user_prefer_country_id = $user_prefer_country_id;
        $user->save();

        // when create a new user, we also need to create a free subscription record
        $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();
        $free_subscription = new Subscription(array(
            'user_id' => $user->id,
            'plan_id' => $free_plan->id,
            'subscription_start_date' => Carbon::now()->toDateString(),
//            'subscription_max_featured_listing' => is_null($free_plan->plan_max_featured_listing) ? null : $free_plan->plan_max_featured_listing,
//            'subscription_max_free_listing' => is_null($free_plan->plan_max_free_listing) ? null : $free_plan->plan_max_free_listing,
        ));
        $new_free_subscription = $user->subscription()->save($free_subscription);


        \Session::flash('flash_message', __('alert.user-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.edit-user', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Get current user's social accounts if any
         */
        $social_accounts = $user->socialiteAccounts()->get();

        $all_countries = Country::orderBy('country_name')->get();

        return response()->view('backend.admin.user.edit',
            compact('user', 'social_accounts', 'all_countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $user)
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

        $validate_error = array();
        $user_email_exist = User::where('email', $email)
            ->where('id', '!=', $user->id)
            ->count();
        if($user_email_exist > 0)
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

        $user_image = $request->user_image;
        $user_image_name = $user->user_image;
        if(!empty($user_image)){

            $currentDate = Carbon::now()->toDateString();

            $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';

            if(!Storage::disk('public')->exists('user')){
                Storage::disk('public')->makeDirectory('user');
            }
            if(Storage::disk('public')->exists('user/' . $user->user_image)){
                Storage::disk('public')->delete('user/' . $user->user_image);
            }

            $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);

            Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
        }

        $user->name = $name;
        $user->email = $email;
        $user->user_about = $user_about;
        $user->user_prefer_language = $user_prefer_language;
        $user->user_prefer_country_id = $user_prefer_country_id;
        $user->user_image = $user_image_name;
        $user->save();

        \Session::flash('flash_message', __('alert.user-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        // Cannot delete admin account
        if(!$user->isAdmin())
        {
            $user->deleteUser();

            \Session::flash('flash_message', __('alert.user-deleted'));
            \Session::flash('flash_type', 'success');
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * @param User $user
     * @return Response
     */
    public function editUserPassword(User $user)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.change-user-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.user.password.edit', compact('user'));
    }

    public function updateUserPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        // change password
        $user->password = bcrypt($request->new_password);
        $user->save();

        \Session::flash('flash_message', __('alert.user-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

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
        SEOMeta::setTitle(__('seo.backend.admin.user.edit-profile', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $user_admin = User::getAdmin();

        $all_countries = Country::orderBy('country_name')->get();

        return response()->view('backend.admin.user.profile.edit',
            compact('user_admin', 'all_countries'));
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

        $user_admin = User::getAdmin();

        $validate_error = array();
        $email_exist = User::where('email', $email)
            ->where('id', '!=', $user_admin->id)
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
            $user_image_name = $user_admin->user_image;
            if(!empty($user_image)){

                $currentDate = Carbon::now()->toDateString();

                $user_image_name = 'admin-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';

                if(!Storage::disk('public')->exists('user')){
                    Storage::disk('public')->makeDirectory('user');
                }
                if(Storage::disk('public')->exists('user/' . $user_admin->user_image)){
                    Storage::disk('public')->delete('user/' . $user_admin->user_image);
                }

                $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);

                Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
            }

            $user_admin->name = $name;
            $user_admin->email = $email;
            $user_admin->user_about = $user_about;
            $user_admin->user_image = $user_image_name;
            $user_admin->user_prefer_language = $user_prefer_language;
            $user_admin->user_prefer_country_id = $user_prefer_country_id;
            $user_admin->save();

            \Session::flash('flash_message', __('alert.user-profile-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.users.profile.edit');
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
        SEOMeta::setTitle(__('seo.backend.admin.user.change-profile-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.user.profile.password.edit');
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

        $user_admin = User::getAdmin();

        // check current password
        $current_password = $request->password;
        if(!Hash::check($current_password, $user_admin->password))
        {
            throw ValidationException::withMessages(['password' => 'Current password wrong.']);
        }

        // change password
        $user_admin->password = bcrypt($request->new_password);
        $user_admin->save();

        \Session::flash('flash_message', __('alert.user-profile-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.profile.edit');
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function suspendUser(User $user)
    {
        $user->user_suspended = User::USER_SUSPENDED;
        $user->save();

        \Session::flash('flash_message', __('alert.user-suspended'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function unsuspendUser(User $user)
    {
        $user->user_suspended = User::USER_NOT_SUSPENDED;
        $user->save();

        \Session::flash('flash_message', __('alert.user-unlocked'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    public function bulkVerifyUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->verifyEmail();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-verified'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }

    public function bulkSuspendUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->suspendAccount();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-suspended'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }

    public function bulkUnsuspendUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->unsuspendAccount();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-unlocked'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }

    public function bulkDeleteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->deleteUser();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }
}
