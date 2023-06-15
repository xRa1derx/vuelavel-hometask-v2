<?php

namespace App\Http\Resources\Message;

use App\Http\Resources\FileResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'uuid' => $this->uuid,
            'message' => $this->message,
            'new' => $this->new,
            'replyMessage' => $this->replyMessage,
            'sender' => UserResource::make(User::findOrFail($this->from)),
            'created_at' => $this->created_at,
            'created_at_for_humans' => $this->created_at->diffForHumans(),
            'files' => FileResource::collection($this->files),
            'images' => ImageResource::collection($this->images)
        ];
    }
}
