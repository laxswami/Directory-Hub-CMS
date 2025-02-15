<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingBankTransfer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting_bank_transfers';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_bank_transfer_bank_name',
        'setting_bank_transfer_bank_account_info',
        'setting_bank_transfer_status',
    ];
}
