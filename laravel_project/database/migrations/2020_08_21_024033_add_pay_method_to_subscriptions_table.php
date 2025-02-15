<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayMethodToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('subscription_razorpay_plan_id')->nullable();
            $table->string('subscription_razorpay_subscription_id')->nullable();
            $table->string('subscription_pay_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('subscription_pay_method');
            $table->dropColumn('subscription_razorpay_subscription_id');
            $table->dropColumn('subscription_razorpay_plan_id');
        });
    }
}
