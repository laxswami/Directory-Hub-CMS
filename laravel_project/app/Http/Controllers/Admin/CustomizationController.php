<?php

namespace App\Http\Controllers\Admin;

use App\Customization;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class CustomizationController extends Controller
{

    public function colorEdit()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('customization.seo.edit-color', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $site_primary_color = Customization::where('customization_key', Customization::SITE_PRIMARY_COLOR)
            ->first()->customization_value;

        $site_header_background_color = Customization::where('customization_key', Customization::SITE_HEADER_BACKGROUND_COLOR)
            ->first()->customization_value;

        $site_footer_background_color = Customization::where('customization_key', Customization::SITE_FOOTER_BACKGROUND_COLOR)
            ->first()->customization_value;

        $site_header_font_color = Customization::where('customization_key', Customization::SITE_HEADER_FONT_COLOR)
            ->first()->customization_value;

        $site_footer_font_color = Customization::where('customization_key', Customization::SITE_FOOTER_FONT_COLOR)
            ->first()->customization_value;

        return response()->view('backend.admin.customization.color.edit',
            compact('site_primary_color', 'site_header_background_color',
                'site_footer_background_color', 'site_header_font_color', 'site_footer_font_color'));
    }

    public function colorUpdate(Request $request)
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

        $site_primary_color_db = Customization::where('customization_key', Customization::SITE_PRIMARY_COLOR)
            ->first();
        $site_primary_color_db->customization_value = $site_primary_color;
        $site_primary_color_db->save();

        $site_header_background_color_db = Customization::where('customization_key', Customization::SITE_HEADER_BACKGROUND_COLOR)
            ->first();
        $site_header_background_color_db->customization_value = $site_header_background_color;
        $site_header_background_color_db->save();

        $site_header_font_color_db = Customization::where('customization_key', Customization::SITE_HEADER_FONT_COLOR)
            ->first();
        $site_header_font_color_db->customization_value = $site_header_font_color;
        $site_header_font_color_db->save();

        $site_footer_background_color_db = Customization::where('customization_key', Customization::SITE_FOOTER_BACKGROUND_COLOR)
            ->first();
        $site_footer_background_color_db->customization_value = $site_footer_background_color;
        $site_footer_background_color_db->save();

        $site_footer_font_color_db = Customization::where('customization_key', Customization::SITE_FOOTER_FONT_COLOR)
            ->first();
        $site_footer_font_color_db->customization_value = $site_footer_font_color;
        $site_footer_font_color_db->save();

        \Session::flash('flash_message', __('customization.alert.color-edit-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.customization.color.edit');

    }

    public function colorRestore(Request $request)
    {
        $site_primary_color_db = Customization::where('customization_key', Customization::SITE_PRIMARY_COLOR)
            ->first();
        $site_primary_color_db->customization_value = Customization::SITE_PRIMARY_COLOR_DEFAULT;
        $site_primary_color_db->save();

        $site_header_background_color_db = Customization::where('customization_key', Customization::SITE_HEADER_BACKGROUND_COLOR)
            ->first();
        $site_header_background_color_db->customization_value = Customization::SITE_HEADER_BACKGROUND_COLOR_DEFAULT;
        $site_header_background_color_db->save();

        $site_header_font_color_db = Customization::where('customization_key', Customization::SITE_HEADER_FONT_COLOR)
            ->first();
        $site_header_font_color_db->customization_value = Customization::SITE_HEADER_FONT_COLOR_DEFAULT;
        $site_header_font_color_db->save();

        $site_footer_background_color_db = Customization::where('customization_key', Customization::SITE_FOOTER_BACKGROUND_COLOR)
            ->first();
        $site_footer_background_color_db->customization_value = Customization::SITE_FOOTER_BACKGROUND_COLOR_DEFAULT;
        $site_footer_background_color_db->save();

        $site_footer_font_color_db = Customization::where('customization_key', Customization::SITE_FOOTER_FONT_COLOR)
            ->first();
        $site_footer_font_color_db->customization_value = Customization::SITE_FOOTER_FONT_COLOR_DEFAULT;
        $site_footer_font_color_db->save();

        \Session::flash('flash_message', __('customization.alert.color-restore-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.customization.color.edit');
    }

    public function headerEdit()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('customization.seo.edit-header', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $site_homepage_header_background_type= Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE)
            ->first()->customization_value;

        $site_homepage_header_background_color = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR)
            ->first()->customization_value;

        $site_homepage_header_background_image = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            ->first()->customization_value;

        $site_homepage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->first()->customization_value;

        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->first()->customization_value;

        $site_homepage_header_title_font_color = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR)
            ->first()->customization_value;

        $site_homepage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->first()->customization_value;

        return response()->view('backend.admin.customization.header.edit',
            compact('site_homepage_header_background_type', 'site_homepage_header_background_color',
            'site_homepage_header_background_image', 'site_homepage_header_background_youtube_video',
            'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
            'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
            'site_homepage_header_title_font_color', 'site_homepage_header_paragraph_font_color',
            'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
    }

    public function headerUpdate(Request $request)
    {
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


        $site_homepage_header_background_image_old = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            ->first()->customization_value;

        $site_innerpage_header_background_image_old = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
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
         * Start saving all values
         */

        $site_homepage_header_title_font_color_db = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR)
            ->first();
        $site_homepage_header_title_font_color_db->customization_value = $site_homepage_header_title_font_color;
        $site_homepage_header_title_font_color_db->save();

        $site_homepage_header_paragraph_font_color_db = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->first();
        $site_homepage_header_paragraph_font_color_db->customization_value = $site_homepage_header_paragraph_font_color;
        $site_homepage_header_paragraph_font_color_db->save();

        $site_homepage_header_background_type_db = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE)
            ->first();
        $site_homepage_header_background_type_db->customization_value = $site_homepage_header_background_type;
        $site_homepage_header_background_type_db->save();

        $site_homepage_header_background_color_db = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR)
            ->first();
        $site_homepage_header_background_color_db->customization_value = $site_homepage_header_background_color;
        $site_homepage_header_background_color_db->save();

        // save homepage header image
        $site_homepage_header_background_image_name = $site_homepage_header_background_image_old;
        if(!empty($site_homepage_header_background_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $site_homepage_header_background_image_name = 'homepage-header-'.$currentDate.'-'.uniqid().'.jpg';

            if(!Storage::disk('public')->exists('customization')){
                Storage::disk('public')->makeDirectory('customization');
            }

            if(Storage::disk('public')->exists('customization/' . $site_homepage_header_background_image_old)){
                Storage::disk('public')->delete('customization/' . $site_homepage_header_background_image_old);
            }

            $site_homepage_header_background_image_file = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$site_homepage_header_background_image)))->stream('jpg', 100);
            Storage::disk('public')->put('customization/'.$site_homepage_header_background_image_name, $site_homepage_header_background_image_file);

        }
        $site_homepage_header_background_image_db = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE)
            ->first();
        $site_homepage_header_background_image_db->customization_value = $site_homepage_header_background_image_name;
        $site_homepage_header_background_image_db->save();


        $site_homepage_header_background_youtube_video_db = Customization::where('customization_key', Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->first();
        $site_homepage_header_background_youtube_video_db->customization_value = $site_homepage_header_background_youtube_video;
        $site_homepage_header_background_youtube_video_db->save();


        $site_innerpage_header_title_font_color_db = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->first();
        $site_innerpage_header_title_font_color_db->customization_value = $site_innerpage_header_title_font_color;
        $site_innerpage_header_title_font_color_db->save();

        $site_innerpage_header_paragraph_font_color_db = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->first();
        $site_innerpage_header_paragraph_font_color_db->customization_value = $site_innerpage_header_paragraph_font_color;
        $site_innerpage_header_paragraph_font_color_db->save();

        $site_innerpage_header_background_type_db = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->first();
        $site_innerpage_header_background_type_db->customization_value = $site_innerpage_header_background_type;
        $site_innerpage_header_background_type_db->save();

        $site_innerpage_header_background_color_db = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->first();
        $site_innerpage_header_background_color_db->customization_value = $site_innerpage_header_background_color;
        $site_innerpage_header_background_color_db->save();

        // save innerpage header image
        $site_innerpage_header_background_image_name = $site_innerpage_header_background_image_old;
        if(!empty($site_innerpage_header_background_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $site_innerpage_header_background_image_name = 'innerpage-header-'.$currentDate.'-'.uniqid().'.jpg';

            if(!Storage::disk('public')->exists('customization')){
                Storage::disk('public')->makeDirectory('customization');
            }

            if(Storage::disk('public')->exists('customization/' . $site_innerpage_header_background_image_old)){
                Storage::disk('public')->delete('customization/' . $site_innerpage_header_background_image_old);
            }

            $site_innerpage_header_background_image_file = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$site_innerpage_header_background_image)))->stream('jpg', 100);
            Storage::disk('public')->put('customization/'.$site_innerpage_header_background_image_name, $site_innerpage_header_background_image_file);

        }
        $site_innerpage_header_background_image_db = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->first();
        $site_innerpage_header_background_image_db->customization_value = $site_innerpage_header_background_image_name;
        $site_innerpage_header_background_image_db->save();

        $site_innerpage_header_background_youtube_video_db = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->first();
        $site_innerpage_header_background_youtube_video_db->customization_value = $site_innerpage_header_background_youtube_video;
        $site_innerpage_header_background_youtube_video_db->save();
        /**
         * End saving all values
         */

        \Session::flash('flash_message', __('customization.alert.header-edit-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.customization.header.edit');
    }
}
