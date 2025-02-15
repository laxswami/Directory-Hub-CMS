<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Canvas\Post as Post;
use Laravelista\Comments\Commentable;

class BlogPost extends Post
{
    use Commentable;
}
