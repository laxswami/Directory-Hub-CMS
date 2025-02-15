<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingLicense extends Model
{
    const LICENSE_API_SECRET = "80W284485P519543T";
    const LICENSE_API_HOST = "http://license.alphastir.com";

    const LICENSE_API_ROUTE_VERIFY = "/post";
    const LICENSE_API_ROUTE_SHOW = "/show";
    const LICENSE_API_ROUTE_REVOKE = "/revoke";

    const LICENSE_API_ROUTE_DOMAIN_VERIFY_TOKEN = "/domain/token";
    const LICENSE_API_ROUTE_DOMAIN_VERIFY_DO = "/domain/verify";

    const LICENSE_API_STATUS_CODE_SUCCESS = 200;
    const LICENSE_API_STATUS_CODE_ERROR = 500;

    const LICENSE_API_LICENSE_VALID = 1;
    const LICENSE_API_LICENSE_INVALID = 0;

    const LICENSE_API_DOMAIN_VERIFIED = 1;
    const LICENSE_API_DOMAIN_UNVERIFY = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings_license';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_id',
        'settings_license_purchase_code',
        'settings_license_codecanyon_username',
        'settings_license_domain_verify_token',
    ];

    public function setting()
    {
        return $this->belongsTo('App\Setting');
    }
}
