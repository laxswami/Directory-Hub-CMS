<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customization extends Model
{
    const SITE_PRIMARY_COLOR = 'site_primary_color';
    const SITE_HEADER_BACKGROUND_COLOR = 'site_header_background_color';
    const SITE_FOOTER_BACKGROUND_COLOR = 'site_footer_background_color';
    const SITE_HEADER_FONT_COLOR = 'site_header_font_color';
    const SITE_FOOTER_FONT_COLOR = 'site_footer_font_color';

    const SITE_PRIMARY_COLOR_DEFAULT = '#30e3ca';
    const SITE_HEADER_BACKGROUND_COLOR_DEFAULT = '#fff';
    const SITE_FOOTER_BACKGROUND_COLOR_DEFAULT = '#333333';
    const SITE_HEADER_FONT_COLOR_DEFAULT = '#000';
    const SITE_FOOTER_FONT_COLOR_DEFAULT = '#fff';

    const SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE = 'site_homepage_header_background_type';
    const SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT = 'default_background';
    const SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR = 'color_background';
    const SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE = 'image_background';
    const SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO = 'youtube_video_background';

    const SITE_HOMEPAGE_HEADER_BACKGROUND_COLOR = 'site_homepage_header_background_color';
    const SITE_HOMEPAGE_HEADER_BACKGROUND_IMAGE = 'site_homepage_header_background_image';
    const SITE_HOMEPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO = 'site_homepage_header_background_youtube_video';

    const SITE_INNERPAGE_HEADER_BACKGROUND_TYPE = 'site_innerpage_header_background_type';
    const SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT = 'default_background';
    const SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR = 'color_background';
    const SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE = 'image_background';
    const SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO = 'youtube_video_background';

    const SITE_INNERPAGE_HEADER_BACKGROUND_COLOR = 'site_innerpage_header_background_color';
    const SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE = 'site_innerpage_header_background_image';
    const SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO = 'site_innerpage_header_background_youtube_video';

    const SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR = 'site_homepage_header_title_font_color';
    const SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR = 'site_homepage_header_paragraph_font_color';
    const SITE_HOMEPAGE_HEADER_TITLE_FONT_COLOR_DEFAULT = '#fff';
    const SITE_HOMEPAGE_HEADER_PARAGRAPH_FONT_COLOR_DEFAULT = 'rgba(255, 255, 255, 0.5)';

    const SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR = 'site_innerpage_header_title_font_color';
    const SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR = 'site_innerpage_header_paragraph_font_color';
    const SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR_DEFAULT = '#fff';
    const SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR_DEFAULT = 'rgba(255, 255, 255, 0.5)';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customizations';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customization_key',
        'customization_value',
        'customization_default_value',
        'theme_id',
        'customization_recommend_width_px',
        'customization_recommend_height_px',
    ];

    public function theme()
    {
        return $this->belongsTo('App\Theme');
    }
}
