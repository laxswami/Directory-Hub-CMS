<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_languages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('setting_id');

            $table->string('setting_language_default_language');

            $table->integer('setting_language_af_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_sq_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ar_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_hy_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_az_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_be_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_bn_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_bs_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_bg_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ca_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_zh_cn_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_zh_tw_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_hr_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_cs_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_da_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_nl_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_en_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_et_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_fi_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_fr_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_gl_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ka_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_de_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_el_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ht_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_he_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_hi_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_hu_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_is_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_id_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ga_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_it_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ja_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ko_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ky_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_lv_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_lt_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_lb_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_mk_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ms_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_mn_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_my_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ne_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_no_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_fa_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_pl_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_pt_br_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ro_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_ru_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_sr_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_sk_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_sl_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_so_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_es_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_su_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_sv_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_th_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_tr_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_tk_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_uk_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_uz_language')->default(1)->comment('0:disable 1:enable');
            $table->integer('setting_language_vi_language')->default(1)->comment('0:disable 1:enable');

            $table->timestamps();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('setting_site_language')->nullable()->comment('ABANDONED')->change();
        });

        $setting_site_language = 'en';

        $settings = \App\Setting::find(1);
        if($settings)
        {
            if(!empty($settings->setting_site_language))
            {
                $setting_site_language = $settings->setting_site_language;
            }
        }
        $settings_languages = new \App\SettingLanguage(array(
            'setting_id' => 1,
            'setting_language_default_language' => $setting_site_language,
        ));
        $settings_languages->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_languages');
    }
}
