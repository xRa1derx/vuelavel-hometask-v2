<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function PHPSTORM_META\map;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request->all());
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
