<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalIpnLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paypal_ipn_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paypal_ipn_log_value'
    ];
}
