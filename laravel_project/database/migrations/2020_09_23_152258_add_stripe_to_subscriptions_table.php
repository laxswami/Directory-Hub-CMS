<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('subscription_stripe_customer_id')->nullable();
            $table->string('subscription_stripe_subscription_id')->nullable();
            $table->integer('subscription_stripe_future_plan_id')->nullable();
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
            $table->dropColumn('subscription_stripe_customer_id');
            $table->dropColumn('subscription_stripe_subscription_id');
            $table->dropColumn('subscription_stripe_future_plan_id');
        });
    }
}
