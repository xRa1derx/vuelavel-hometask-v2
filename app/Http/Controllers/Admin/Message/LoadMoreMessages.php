<?php

namespace App\Http\Controllers\Admin\Message;

use App\Http\Controllers\Controller;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class LoadMoreMessages extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $data = $this->validate($request, [
            'uuid' => 'required',
        ]);
        $sender = Message::with('sender')->where(['from' => auth()->id(), 'to' => $id])->get();
        $receiver = Message::with('sender')->where(['to' => auth()->id(), 'from' => $id])->get();
        $messages = $sender->merge($receiver)->sortBy('id')->reverse()->values();
        $index = $messages->search(function ($message) use ($data) {
            return $message->uuid == $data['uuid'];
        });
        // if ($index < 10) {
        //     $startIndex = 0;
        // } else {
        //     $startIndex = $index - 10;
        //     $index = 10;
        // }
        $nextTenMessages = $messages->splice($index + 1, 10);
        $indexOfLastMessage = MessageResource::collection($nextTenMessages->values()->all())->resolve();

        return $indexOfLastMessage;
    }
}
