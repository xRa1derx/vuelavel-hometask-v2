<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;

class IndexController extends Controller
{
    public function __invoke()
    {
        $posts = Post::latest()->with('tags', 'category', 'images', 'comments.author')->get();
        return $posts;
    }
}
