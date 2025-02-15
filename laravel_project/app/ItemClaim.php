<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ItemClaim extends Model
{

    const ITEM_CLAIM_STATUS_REQUESTED = 1;
    const ITEM_CLAIM_STATUS_DISAPPROVED = 2;
    const ITEM_CLAIM_STATUS_APPROVED = 3;

    const ITEM_CLAIM_FILTER_REQUESTED = 1;
    const ITEM_CLAIM_FILTER_DISAPPROVED = 2;
    const ITEM_CLAIM_FILTER_APPROVED = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'item_claims';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'item_id',
        'item_claim_full_name',
        'item_claim_phone',
        'item_claim_email',
        'item_claim_additional_proof',
        'item_claim_additional_upload',
        'item_claim_status',
        'item_claim_reply',
    ];

    /**
     * Get the item of this claim
     * @return BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }

    /**
     * Get the user of this claim
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function deleteItemClaim()
    {
        // delete the uploaded doc first
        if (!empty($this->item_claim_additional_upload)
            && Storage::disk('local')->exists('item_claim_doc/' . $this->item_claim_additional_upload)) {
            Storage::disk('local')->delete('item_claim_doc/' . $this->item_claim_additional_upload);
        }

        // then delete the record in item_claim table
        $this->delete();
    }
}
