<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Theme extends Model
{
    const THEME_TYPE_FRONTEND = 1;
    const THEME_TYPE_ADMIN = 2;
    const THEME_TYPE_USER = 3;

    const THEME_STATUS_ACTIVE = 1;
    const THEME_STATUS_INACTIVE = 2;

    const THEME_SYSTEM_DEFAULT_YES = 1;
    const THEME_SYSTEM_DEFAULT_NO = 2;

    const THEME_ASSETS = 'theme_assets';
    const THEME_ASSETS_FRONTEND = 'frontend_assets';

    const THEME_VIEWS = 'theme_views';
    const THEME_VIEWS_FRONTEND = 'frontend_views';

    const THEME_VIEWS_FRONTEND_SYSTEM_DEFAULT = 'frontend';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'themes';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'theme_identifier',
        'theme_name',
        'theme_preview_image',
        'theme_author',
        'theme_description',
        'theme_type',
        'theme_status',
        'theme_system_default',
    ];

    public function customizations()
    {
        return $this->hasMany('App\Customization');
    }

    public function getViewPath()
    {
        $theme_views_path = '';

        if($this->theme_type == self::THEME_TYPE_FRONTEND)
        {
            if($this->theme_system_default == self::THEME_SYSTEM_DEFAULT_YES)
            {
                $theme_views_path = self::THEME_VIEWS_FRONTEND_SYSTEM_DEFAULT . '.';
            }
            else
            {
                $theme_views_path = self::THEME_VIEWS_FRONTEND . '.' . $this->theme_identifier . '.';
            }
        }

        return $theme_views_path;
    }

    public function activeTheme(Setting $settings)
    {
        $active_themes = Theme::where('theme_status', Theme::THEME_STATUS_ACTIVE)
            ->get();

        foreach($active_themes as $theme_status_key => $active_theme)
        {
            $active_theme->theme_status = Theme::THEME_STATUS_INACTIVE;
            $active_theme->save();
        }

        $this->theme_status = Theme::THEME_STATUS_ACTIVE;
        $this->save();

        $settings->setting_site_active_theme_id = $this->id;
        $settings->save();
    }

    public function deleteTheme()
    {
        // only delete non-system theme
        if($this->theme_system_default == self::THEME_SYSTEM_DEFAULT_NO
            && $this->theme_status == self::THEME_STATUS_INACTIVE)
        {
            // for frontend theme
            if($this->theme_type == self::THEME_TYPE_FRONTEND)
            {
                // #1 - remove the theme assets folder and theme views folder
                File::deleteDirectory(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS . DIRECTORY_SEPARATOR . \App\Theme::THEME_ASSETS_FRONTEND . DIRECTORY_SEPARATOR . $this->theme_identifier);
                File::deleteDirectory(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS . DIRECTORY_SEPARATOR . \App\Theme::THEME_VIEWS_FRONTEND . DIRECTORY_SEPARATOR . $this->theme_identifier);

                // #2 - remove the theme record
                $this->delete();
            }
        }
    }
}
