<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialiteAccount extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'socialite_accounts';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'socialite_account_provider_id',
        'socialite_account_provider_name',
    ];

    /**
     * Relation with user table
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
