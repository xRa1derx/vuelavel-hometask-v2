<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $post = Post::with('tags', 'images', 'category')->find($id);
        $categories = Category::all();
        $tags = Tag::all();
        return response()->json(['post' => $post, 'categories' => $categories, 'tags' => $tags]);
    }
}
