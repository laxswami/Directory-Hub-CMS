<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RazorpayWebhookLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'razorpay_webhook_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'razorpay_webhook_log_value'
    ];
}
