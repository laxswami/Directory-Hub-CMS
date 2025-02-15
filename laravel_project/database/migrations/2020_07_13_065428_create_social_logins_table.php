<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_logins', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('social_login_provider_name')->nullable();
            $table->string('social_login_provider_client_id')->nullable();
            $table->string('social_login_provider_client_secret')->nullable();
            $table->integer('social_login_enabled')->default(0)->comment('0: disabled 1: enabled');

            $table->timestamps();
        });

        /**
         * Insert pre-defined social login
         */
        DB::table('social_logins')->insert([
            [
                'social_login_provider_name' => \App\SocialLogin::SOCIAL_LOGIN_FACEBOOK,
                'social_login_provider_client_id' => '',
                'social_login_provider_client_secret' => '',
                'social_login_enabled' => \App\SocialLogin::SOCIAL_LOGIN_DISABLED,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'social_login_provider_name' => \App\SocialLogin::SOCIAL_LOGIN_GOOGLE,
                'social_login_provider_client_id' => '',
                'social_login_provider_client_secret' => '',
                'social_login_enabled' => \App\SocialLogin::SOCIAL_LOGIN_DISABLED,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'social_login_provider_name' => \App\SocialLogin::SOCIAL_LOGIN_TWITTER,
                'social_login_provider_client_id' => '',
                'social_login_provider_client_secret' => '',
                'social_login_enabled' => \App\SocialLogin::SOCIAL_LOGIN_DISABLED,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'social_login_provider_name' => \App\SocialLogin::SOCIAL_LOGIN_LINKEDIN,
                'social_login_provider_client_id' => '',
                'social_login_provider_client_secret' => '',
                'social_login_enabled' => \App\SocialLogin::SOCIAL_LOGIN_DISABLED,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
            [
                'social_login_provider_name' => \App\SocialLogin::SOCIAL_LOGIN_GITHUB,
                'social_login_provider_client_id' => '',
                'social_login_provider_client_secret' => '',
                'social_login_enabled' => \App\SocialLogin::SOCIAL_LOGIN_DISABLED,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ],
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_logins');
    }
}
