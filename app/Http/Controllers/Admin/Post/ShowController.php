<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        return new PostResource(Post::with('tags', 'images', 'category', 'comments.author', 'comments.replies.author', 'comments.parent')->findOrFail($id));
    }
}
