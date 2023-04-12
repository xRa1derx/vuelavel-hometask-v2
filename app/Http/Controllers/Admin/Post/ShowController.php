<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $post = new PostResource(Post::with('tags', 'images', 'category', 'verifiedComments.author', 'verifiedComments.replies.author')->findOrFail($id));
        return $post;

        // 'verifiedComments.parent'
    }
}
