<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeWebhookLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stripe_webhook_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stripe_webhook_log_value'
    ];
}
