<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $data = $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'tags' => 'required',
            'category_id' => 'required',
            'images' => 'nullable|array',
            'image_ids_for_delete' => 'nullable|array'
        ]);

        $post = Post::find($id);
        $post->edit($request->all());


        $images = isset($data['images']) ? $data['images'] : null;

        $imageForDelete = isset($data['image_ids_for_delete']) ? $data['image_ids_for_delete'] : null;
        // unset($data['images'], $data['image_ids_for_delete']);

        $currentImages = $post->images;
        if ($imageForDelete) {
            foreach ($currentImages as $currentImage) {
                if (in_array($currentImage->id, $imageForDelete)) {
                    // Storage::disk('public')->delete($currentImage->path);
                    Storage::delete('images/posts/' . $currentImage->name);
                    Storage::delete('images/posts/' . $currentImage->preview);
                    $currentImage->delete();
                }
            }
        }
        if ($images) {
            foreach ($images as $image) {
                $post->uploadImage($image);
            }
        }
        $post->setCategory($request->get('category_id'));
        $post->setTags($request->get('tags'));
        $post->save();
        return $post;
    }
}
