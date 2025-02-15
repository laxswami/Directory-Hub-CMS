<?php

namespace App\Http\Controllers\Admin;

use App\Customization;
use App\Http\Controllers\Controller;
use App\Theme;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Exception;
use ZipArchive;
use Illuminate\Filesystem\Filesystem;

class ThemeController extends Controller
{
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('theme_directory_hub.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_themes = Theme::all();

        return response()->view('backend.admin.theme.index',
            compact('all_themes'));
    }

    public function createTheme(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('theme_directory_hub.seo.install-theme', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.theme.create');
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'theme_install_zip' => 'required|file|mimes:zip|max:30720',
        ]);

        try
        {
            // saving the zip file temporarily
            $theme_install_zip_name = uniqid() . '.' . $request->theme_install_zip->extension();
            $request->theme_install_zip->storeAs('theme_temp', $theme_install_zip_name);

            // unzip the theme install package
            $theme_zip = new ZipArchive();
            $res_theme_zip = $theme_zip->open(storage_path('app/theme_temp/') . $theme_install_zip_name);
            if ($res_theme_zip === TRUE)
            {
                // Extract file
                $theme_zip->extractTo(storage_path('app/theme_temp/'));
                $theme_zip->close();

                // import SQL file
                DB::unprepared(File::get(storage_path('app/theme_temp/') . 'theme_install.sql'));

                // extra theme_assets.zip to theme_assets/frontend_assets folder
                $theme_zip_assets = new ZipArchive();
                $res_theme_zip_assets = $theme_zip_assets->open(storage_path('app/theme_temp/') . 'theme_assets.zip');
                if($res_theme_zip_assets === TRUE)
                {
                    // Extract file
                    $theme_zip_assets->extractTo(base_path() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . Theme::THEME_ASSETS . DIRECTORY_SEPARATOR . Theme::THEME_ASSETS_FRONTEND . DIRECTORY_SEPARATOR);
                    $theme_zip_assets->close();
                }
                else
                {
                    $this->clearThemeTempDir();

                    Session::flash('flash_message', __('theme_directory_hub.alert.theme-install-failed-open-package'));
                    Session::flash('flash_type', 'danger');

                    return redirect()->route('admin.themes.create');
                }

                // extra theme_views.zip to theme_views/frontend_views folder
                $theme_zip_views = new ZipArchive();
                $res_theme_zip_views = $theme_zip_views->open(storage_path('app/theme_temp/') . 'theme_views.zip');
                if($res_theme_zip_views === TRUE)
                {
                    // Extract file
                    $theme_zip_views->extractTo(base_path() . DIRECTORY_SEPARATOR . Theme::THEME_VIEWS . DIRECTORY_SEPARATOR . Theme::THEME_VIEWS_FRONTEND . DIRECTORY_SEPARATOR);
                    $theme_zip_views->close();
                }
                else
                {
                    $this->clearThemeTempDir();

                    Session::flash('flash_message', __('theme_directory_hub.alert.theme-install-failed-open-package'));
                    Session::flash('flash_type', 'danger');

                    return redirect()->route('admin.themes.create');
                }

                $this->clearThemeTempDir();

                Session::flash('flash_message', __('theme_directory_hub.alert.theme-installed'));
                Session::flash('flash_type', 'success');

                return redirect()->route('admin.themes.index');

            }
            else
            {
                $this->clearThemeTempDir();

                Session::flash('flash_message', __('theme_directory_hub.alert.theme-install-failed-open-package'));
                Session::flash('flash_type', 'danger');

                return redirect()->route('admin.themes.create');
            }
        }
        catch (Exception $e) {

            Log::error($e);

            $this->clearThemeTempDir();

            Session::flash('flash_message', $e->getMessage());
            Session::flash('flash_type', 'danger');

            return redirect()->route('admin.themes.create');
        }
    }

    private function clearThemeTempDir()
    {
        $file_system = new Filesystem();
        $file_system->cleanDirectory(storage_path('app/theme_temp/'));
    }

    public function activeTheme(Request $request, Theme $theme)
    {
        $settings = app('site_global_settings');

        $theme->activeTheme($settings);

        \Session::flash('flash_message', __('theme_directory_hub.alert.theme-activated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.themes.index');
    }

    public function destroyTheme(Request $request, Theme $theme)
    {
        if(env("THEME_DEBUG", true))
        {
            \Session::flash('flash_message', __('theme_directory_hub.alert.theme-debug'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.themes.index');
        }
        elseif($theme->theme_system_default == Theme::THEME_SYSTEM_DEFAULT_YES)
        {
            \Session::flash('flash_message', __('theme_directory_hub.alert.theme-delete-default-theme'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.themes.index');
        }
        elseif($theme->theme_status == Theme::THEME_STATUS_ACTIVE)
        {
            \Session::flash('flash_message', __('theme_directory_hub.alert.theme-delete-active-theme'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.themes.index');
        }
        else
        {
            $theme->deleteTheme();

            \Session::flash('flash_message', __('theme_directory_hub.alert.theme-deleted'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.themes.index');
        }
    }

    public function editThemeColor(Request $request, Theme $theme)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('theme_directory_hub.seo.color-edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $customization_keys = array(
            Customization::SITE_PRIMARY_COLOR,
            Customization::SITE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_FOOTER_BACKGROUND_COLOR,
            Customization::SITE_HEADER_FONT_COLOR,
            Customization::SITE_FOOTER_FONT_COLOR,
            );

        $customizations = $theme->customizations()
            ->whereIn('customization_key', $customization_keys)
            ->get();

        $site_primary_color = '';
        $site_header_background_color = '';
        $site_footer_background_color = '';
        $site_header_font_color = '';
        $site_footer_font_color = '';

        foreach($customizations as $customizations_key => $customization)
        {
            if($customization->customization_key == Customization::SITE_PRIMARY_COLOR)
            {
                $site_primary_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_HEADER_BACKGROUND_COLOR)
            {
                $site_header_background_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_FOOTER_BACKGROUND_COLOR)
            {
                $site_footer_background_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_HEADER_FONT_COLOR)
            {
                $site_header_font_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_FOOTER_FONT_COLOR)
            {
                $site_footer_font_color = $customization->customization_value;
            }
        }

        return response()->view('backend.admin.theme.customization.color.edit',
            compact('theme', 'site_primary_color', 'site_header_background_color',
                'site_footer_background_color', 'site_header_font_color', 'site_footer_font_color'));

    }

    public function updateThemeColor(Request $request, Theme $theme)
    {
        $request->validate([
            'site_primary_color' => 'required|max:255',
            'site_header_background_color' => 'required|max:255',
            'site_header_font_color' => 'required|max:255',
            'site_footer_background_color' => 'required|max:255',
            'site_footer_font_color' => 'required|max:255',
        ]);

        $site_primary_color = $request->site_primary_color;
        $site_header_background_color = $request->site_header_background_color;
        $site_header_font_color = $request->site_header_font_color;
        $site_footer_background_color = $request->site_footer_background_color;
        $site_footer_font_color = $request->site_footer_font_color;

        $customization_keys = array(
            Customization::SITE_PRIMARY_COLOR,
            Customization::SITE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_FOOTER_BACKGROUND_COLOR,
            Customization::SITE_HEADER_FONT_COLOR,
            Customization::SITE_FOOTER_FONT_COLOR,
        );

        $customizations = $theme->customizations()
            ->whereIn('customization_key', $customization_keys)
            ->get();

        foreach($customizations as $customizations_key => $customization)
        {
            if($customization->customization_key == Customization::SITE_PRIMARY_COLOR)
            {
                $customization->customization_value = $site_primary_color;
            }
            elseif($customization->customization_key == Customization::SITE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $site_header_background_color;
            }
            elseif($customization->customization_key == Customization::SITE_FOOTER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $site_footer_background_color;
            }
            elseif($customization->customization_key == Customization::SITE_HEADER_FONT_COLOR)
            {
                $customization->customization_value = $site_header_font_color;
            }
            elseif($customization->customization_key == Customization::SITE_FOOTER_FONT_COLOR)
            {
                $customization->customization_value = $site_footer_font_color;
            }
            $customization->save();
        }

        \Session::flash('flash_message', __('theme_directory_hub.alert.theme-color-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.themes.customization.color.edit',
            ['theme' => $theme]);
    }

    public function restoreThemeColor(Request $request, Theme $theme)
    {
        $customization_keys = array(
            Customization::SITE_PRIMARY_COLOR,
            Customization::SITE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_FOOTER_BACKGROUND_COLOR,
            Customization::SITE_HEADER_FONT_COLOR,
            Customization::SITE_FOOTER_FONT_COLOR,
        );

        $customizations = $theme->customizations()
            ->whereIn('customization_key', $customization_keys)
            ->get();

        foreach($customizations as $customizations_key => $customization)
        {
            if($customization->customization_key == Customization::SITE_PRIMARY_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_FOOTER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_HEADER_FONT_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_FOOTER_FONT_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            $customization->save();
        }

        \Session::flash('flash_message', __('theme_directory_hub.alert.theme-color-restored'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.themes.customization.color.edit',
            ['theme' => $theme]);
    }

    public function editThemeHeader(Request $request, Theme $theme)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('theme_directory_hub.seo.header-edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $customization_keys = array(
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
            Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR,
            Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR,
            Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR,
            Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR,
        );

        $customizations = $theme->customizations()
            ->whereIn('customization_key', $customization_keys)
            ->get();

        $site_homepage_header_background_type = '';
        $site_homepage_header_background_color = '';
        $site_homepage_header_background_image = '';
        $site_homepage_header_background_image_recommend_width = '';
        $site_homepage_header_background_image_recommend_height = '';
        $site_homepage_header_background_youtube_video = '';
        $site_innerpage_header_background_type = '';
        $site_innerpage_header_background_color = '';
        $site_innerpage_header_background_image = '';
        $site_innerpage_header_background_image_recommend_width = '';
        $site_innerpage_header_background_image_recommend_height = '';
        $site_innerpage_header_background_youtube_video = '';
        $site_homepage_header_title_font_color = '';
        $site_homepage_header_paragraph_font_color = '';
        $site_innerpage_header_title_font_color = '';
        $site_innerpage_header_paragraph_font_color = '';

        foreach($customizations as $customizations_key => $customization)
        {
            if($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE)
            {
                $site_homepage_header_background_type = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR)
            {
                $site_homepage_header_background_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            {
                $site_homepage_header_background_image = $customization->customization_value;
                $site_homepage_header_background_image_recommend_width = $customization->customization_recommend_width_px;
                $site_homepage_header_background_image_recommend_height = $customization->customization_recommend_height_px;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $site_homepage_header_background_youtube_video = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            {
                $site_innerpage_header_background_type = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            {
                $site_innerpage_header_background_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            {
                $site_innerpage_header_background_image = $customization->customization_value;
                $site_innerpage_header_background_image_recommend_width = $customization->customization_recommend_width_px;
                $site_innerpage_header_background_image_recommend_height = $customization->customization_recommend_height_px;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $site_innerpage_header_background_youtube_video = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $site_homepage_header_title_font_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $site_homepage_header_paragraph_font_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $site_innerpage_header_title_font_color = $customization->customization_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $site_innerpage_header_paragraph_font_color = $customization->customization_value;
            }
        }

        return response()->view('backend.admin.theme.customization.header.edit',
            compact('theme', 'site_homepage_header_background_type', 'site_homepage_header_background_color',
                'site_homepage_header_background_image', 'site_homepage_header_background_youtube_video',
                'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                'site_homepage_header_title_font_color', 'site_homepage_header_paragraph_font_color',
                'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color',
                'site_homepage_header_background_image_recommend_width', 'site_homepage_header_background_image_recommend_height',
                'site_innerpage_header_background_image_recommend_width', 'site_innerpage_header_background_image_recommend_height'));
    }

    public function updateThemeHeader(Request $request, Theme $theme)
    {
        /**
         * Start validation
         */
        $request->validate([
            'site_homepage_header_background_type' => 'required|in:default_background,color_background,image_background,youtube_video_background',
            'site_homepage_header_background_color' => 'nullable|max:255',
            'site_homepage_header_background_image' => 'nullable',
            'site_homepage_header_background_youtube_video' => 'nullable|url',
            'site_homepage_header_title_font_color' => 'required|max:255',
            'site_homepage_header_paragraph_font_color' => 'required|max:255',

            'site_innerpage_header_background_type' => 'required|in:default_background,color_background,image_background,youtube_video_background',
            'site_innerpage_header_background_color' => 'nullable|max:255',
            'site_innerpage_header_background_image' => 'nullable',
            'site_innerpage_header_background_youtube_video' => 'nullable|url',
            'site_innerpage_header_title_font_color' => 'required|max:255',
            'site_innerpage_header_paragraph_font_color' => 'required|max:255',
        ]);

        $site_homepage_header_background_type = $request->site_homepage_header_background_type;
        $site_homepage_header_background_color = empty($request->site_homepage_header_background_color) ? null : $request->site_homepage_header_background_color;
        $site_homepage_header_background_image = empty($request->site_homepage_header_background_image) ? null : $request->site_homepage_header_background_image;
        $site_homepage_header_background_youtube_video = empty($request->site_homepage_header_background_youtube_video) ? null : $request->site_homepage_header_background_youtube_video;
        $site_homepage_header_title_font_color = $request->site_homepage_header_title_font_color;
        $site_homepage_header_paragraph_font_color = $request->site_homepage_header_paragraph_font_color;

        $site_innerpage_header_background_type = $request->site_innerpage_header_background_type;
        $site_innerpage_header_background_color = empty($request->site_innerpage_header_background_color) ? null : $request->site_innerpage_header_background_color;
        $site_innerpage_header_background_image = empty($request->site_innerpage_header_background_image) ? null : $request->site_innerpage_header_background_image;
        $site_innerpage_header_background_youtube_video = empty($request->site_innerpage_header_background_youtube_video) ? null : $request->site_innerpage_header_background_youtube_video;
        $site_innerpage_header_title_font_color = $request->site_innerpage_header_title_font_color;
        $site_innerpage_header_paragraph_font_color = $request->site_innerpage_header_paragraph_font_color;

        $site_homepage_header_background_image_old = $theme->customizations()
            ->where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            ->first()->customization_value;

        $site_innerpage_header_background_image_old = $theme->customizations()
            ->where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->first()->customization_value;

        $validate_error = array();
        if($site_homepage_header_background_type == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR
            && empty($site_homepage_header_background_color))
        {
            $validate_error['site_homepage_header_background_color'] = __('customization.homepage-header-background-color-require');
        }

        if($site_homepage_header_background_type == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE
            && empty($site_homepage_header_background_image))
        {
            if(empty($site_homepage_header_background_image_old))
            {
                $validate_error['site_homepage_header_background_image'] = __('customization.homepage-header-background-image-require');
            }
        }

        if($site_homepage_header_background_type == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO
            && empty($site_homepage_header_background_youtube_video))
        {
            $validate_error['site_homepage_header_background_youtube_video'] = __('customization.homepage-header-background-video-require');
        }

        if($site_innerpage_header_background_type == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR
            && empty($site_innerpage_header_background_color))
        {
            $validate_error['site_innerpage_header_background_color'] = __('customization.innerpage-header-background-color-require');
        }

        if($site_innerpage_header_background_type == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE
            && empty($site_innerpage_header_background_image))
        {
            if(empty($site_innerpage_header_background_image_old))
            {
                $validate_error['site_innerpage_header_background_image'] = __('customization.innerpage-header-background-image-require');
            }
        }

        if($site_innerpage_header_background_type == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO
            && empty($site_innerpage_header_background_youtube_video))
        {
            $validate_error['site_innerpage_header_background_youtube_video'] = __('customization.innerpage-header-background-video-require');
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        /**
         * End validation
         */

        /**
         * Start saving all values
         */
        $site_homepage_header_background_image_name = $site_homepage_header_background_image_old;
        if(!empty($site_homepage_header_background_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $site_homepage_header_background_image_name = 'homepage-header-'.$currentDate.'-'.uniqid(). $theme->id . '.jpg';

            if(!Storage::disk('public')->exists('customization')){
                Storage::disk('public')->makeDirectory('customization');
            }

            if(Storage::disk('public')->exists('customization/' . $site_homepage_header_background_image_old)){
                Storage::disk('public')->delete('customization/' . $site_homepage_header_background_image_old);
            }

            $site_homepage_header_background_image_file = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$site_homepage_header_background_image)))
                ->stream('jpg', 100);

            Storage::disk('public')->put('customization/'.$site_homepage_header_background_image_name, $site_homepage_header_background_image_file);
        }

        $site_innerpage_header_background_image_name = $site_innerpage_header_background_image_old;
        if(!empty($site_innerpage_header_background_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $site_innerpage_header_background_image_name = 'innerpage-header-'.$currentDate.'-'.uniqid(). $theme->id . '.jpg';

            if(!Storage::disk('public')->exists('customization')){
                Storage::disk('public')->makeDirectory('customization');
            }

            if(Storage::disk('public')->exists('customization/' . $site_innerpage_header_background_image_old)){
                Storage::disk('public')->delete('customization/' . $site_innerpage_header_background_image_old);
            }

            $site_innerpage_header_background_image_file = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$site_innerpage_header_background_image)))
                ->stream('jpg', 100);
            Storage::disk('public')->put('customization/'.$site_innerpage_header_background_image_name, $site_innerpage_header_background_image_file);
        }

        $customization_keys = array(
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
            Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR,
            Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR,
            Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR,
            Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR,
        );

        $customizations = $theme->customizations()
            ->whereIn('customization_key', $customization_keys)
            ->get();

        foreach($customizations as $customizations_key => $customization)
        {
            if($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE)
            {
                $customization->customization_value = $site_homepage_header_background_type;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $site_homepage_header_background_color;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            {
                $customization->customization_value = $site_homepage_header_background_image_name;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $customization->customization_value = $site_homepage_header_background_youtube_video;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            {
                $customization->customization_value = $site_innerpage_header_background_type;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $site_innerpage_header_background_color;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            {
                $customization->customization_value = $site_innerpage_header_background_image_name;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $customization->customization_value = $site_innerpage_header_background_youtube_video;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $customization->customization_value = $site_homepage_header_title_font_color;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $customization->customization_value = $site_homepage_header_paragraph_font_color;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $customization->customization_value = $site_innerpage_header_title_font_color;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $customization->customization_value = $site_innerpage_header_paragraph_font_color;
            }

            $customization->save();
        }
        /**
         * End saving all values
         */

        \Session::flash('flash_message', __('theme_directory_hub.alert.theme-header-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.themes.customization.header.edit',
            ['theme' => $theme]);
    }

    public function restoreThemeHeader(Request $request, Theme $theme)
    {
        $customization_keys = array(
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE,
            Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE,
            Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO,
            Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR,
            Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR,
            Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR,
            Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR,
        );

        $customizations = $theme->customizations()
            ->whereIn('customization_key', $customization_keys)
            ->get();

        foreach($customizations as $customizations_key => $customization)
        {
            if($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            {
                if(!empty($customization->customization_value))
                {
                    if(Storage::disk('public')->exists('customization/' . $customization->customization_value)){
                        Storage::disk('public')->delete('customization/' . $customization->customization_value);
                    }
                }
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            {
                if(!empty($customization->customization_value))
                {
                    if(Storage::disk('public')->exists('customization/' . $customization->customization_value)){
                        Storage::disk('public')->delete('customization/' . $customization->customization_value);
                    }
                }
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            elseif($customization->customization_key == Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            {
                $customization->customization_value = $customization->customization_default_value;
            }
            $customization->save();
        }

        \Session::flash('flash_message', __('theme_directory_hub.alert.theme-header-restored'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.themes.customization.header.edit',
            ['theme' => $theme]);
    }
}
