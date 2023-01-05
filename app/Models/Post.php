<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageIntervention;

class Post extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['title', 'content'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id');;
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->save();
        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        Storage::delete('uploads/' . $this->image);
        $this->delete();
    }

    public function uploadImage($image)
    {
        if ($image == null) {
            return;
        }

        $fileName = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
        $prev_name = 'prev_' . $fileName;
        $image->storeAs('images/posts/' . $this->title . '/', $fileName);
        Image::create([
            'name' => $fileName,
            'preview' => $prev_name,
            'post_id' => $this->id
        ]);

        $path = public_path('images/posts/' . $this->title . '/' . $prev_name);

        ImageIntervention::make($image)->fit(100, 100)->save($path);
    }

    public function getImage()
    {
        if ($this->image == null) {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->image;
    }

    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if ($ids == null) {
            return;
        }
        $this->tags()->sync($ids);
    }
}
