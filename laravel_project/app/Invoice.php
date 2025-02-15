<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const INVOICE_STATUS_PENDING = 'Pending';
    const INVOICE_STATUS_PAID = 'Paid';
    const INVOICE_STATUS_REJECT = 'Reject';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_id', 'invoice_num', 'invoice_item_title',
        'invoice_item_description', 'invoice_amount', 'invoice_status', 'subscription_paypal_profile_id',
        'invoice_razorpay_payment_id', 'invoice_razorpay_signature', 'invoice_pay_method', 'invoice_stripe_invoice_id',
        'invoice_payumoney_transaction_id',
    ];

    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }

    /**
     * @return bool
     */
//    public function getPaidAttribute()
//    {
//        if ($this->payment_status == 'Invalid')
//        {
//            return false;
//        }
//        return true;
//    }
    public function paid()
    {
        if ($this->payment_status == 'Invalid')
        {
            return false;
        }
        return true;
    }
}
