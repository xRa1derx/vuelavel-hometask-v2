<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;

class IndexController extends Controller
{
    public function __invoke()
    {
        $posts = Post::with('tags', 'category', 'images', 'allComments')->latest()->paginate(3);
        return $posts;
    }
}
