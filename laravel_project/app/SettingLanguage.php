<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingLanguage extends Model
{
    const LANGUAGE_ENABLE = 1;
    const LANGUAGE_DISABLE = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings_languages';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_id',
        'setting_language_default_language',
        'setting_language_af_language',
        'setting_language_sq_language',
        'setting_language_ar_language',
        'setting_language_hy_language',
        'setting_language_az_language',
        'setting_language_be_language',
        'setting_language_bn_language',
        'setting_language_bs_language',
        'setting_language_bg_language',
        'setting_language_ca_language',
        'setting_language_zh_cn_language',
        'setting_language_zh_tw_language',
        'setting_language_hr_language',
        'setting_language_cs_language',
        'setting_language_da_language',
        'setting_language_nl_language',
        'setting_language_en_language',
        'setting_language_et_language',
        'setting_language_fi_language',
        'setting_language_fr_language',
        'setting_language_gl_language',
        'setting_language_ka_language',
        'setting_language_de_language',
        'setting_language_el_language',
        'setting_language_ht_language',
        'setting_language_he_language',
        'setting_language_hi_language',
        'setting_language_hu_language',
        'setting_language_is_language',
        'setting_language_id_language',
        'setting_language_ga_language',
        'setting_language_it_language',
        'setting_language_ja_language',
        'setting_language_ko_language',
        'setting_language_ky_language',
        'setting_language_lv_language',
        'setting_language_lt_language',
        'setting_language_lb_language',
        'setting_language_mk_language',
        'setting_language_ms_language',
        'setting_language_mn_language',
        'setting_language_my_language',
        'setting_language_ne_language',
        'setting_language_no_language',
        'setting_language_fa_language',
        'setting_language_pl_language',
        'setting_language_pt_br_language',
        'setting_language_ro_language',
        'setting_language_ru_language',
        'setting_language_sr_language',
        'setting_language_sk_language',
        'setting_language_sl_language',
        'setting_language_so_language',
        'setting_language_es_language',
        'setting_language_su_language',
        'setting_language_sv_language',
        'setting_language_th_language',
        'setting_language_tr_language',
        'setting_language_tk_language',
        'setting_language_uk_language',
        'setting_language_uz_language',
        'setting_language_vi_language',
    ];

    /**
     * Get the user that owns the language setting.
     */
    public function setting()
    {
        return $this->belongsTo('App\Setting');
    }
}
