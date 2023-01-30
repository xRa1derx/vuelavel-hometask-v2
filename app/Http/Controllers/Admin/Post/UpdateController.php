<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'tags' => 'required',
            'category_id' => 'required',
            'images' => 'nullable|array'
        ]);

        $post = Post::add($request->all());
        $images = $data['images'];
        foreach ($images as $image) {
            $post->uploadImage($image);
        }

        $post->setCategory($request->get('category_id'));
        $post->setTags($request->get('tags'));
        $post->save();
        return $post;
    }
}
