<?php

namespace Canvas;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostsTags extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'canvas_posts_tags';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the posts relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function posts()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the tags relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tags()
    {
        return $this->belongsTo(Tag::class);
    }
}
