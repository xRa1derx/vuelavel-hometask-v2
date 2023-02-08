<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteController extends Controller
{
    public function __invoke($id)
    {
        $post = Post::find($id);
        foreach ($post->images as $image) {
            Storage::delete('images/posts/' . $image->name);
            Storage::delete('images/posts/' . $image->preview);
        }
        $post->images()->delete();
        $post->delete();
    }
}
