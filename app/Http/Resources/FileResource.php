<?php

namespace App\Http\Resources;

use App\Models\File;
use App\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
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
            'message_uuid' => $this->message_uuid,
            'name' => $this->name,
            'path' => $this->path,
            'sender' => Message::findOrFail($this->message_uuid)->sender()->get()
            // 'path' => Storage::download($this->path, 'test.pdf', ['Content-Type' => 'application/pdf'])
        ];
    }
}
