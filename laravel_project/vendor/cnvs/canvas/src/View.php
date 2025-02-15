<?php

namespace Canvas;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'canvas_views';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the post relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Scope a query to include a range of views for a given array of posts.
     *
     * @param $query
     * @param $postIDs
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function scopeForPostsInRange($query, $postIDs, $startDate, $endDate)
    {
        return $query->whereIn('post_id', $postIDs)
                     ->whereBetween('created_at', [
                         $startDate,
                         $endDate,
                     ]);
    }
}
