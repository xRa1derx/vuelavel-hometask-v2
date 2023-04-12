<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'category' => $this->category,
            'comments' => $this->verifiedComments,
            'images' => ImageResource::collection($this->images),
            'tags' => $this->tags,
            'all_tags' => Tag::all(),
            'all_categories' => Category::all()
        ];
    }
}
