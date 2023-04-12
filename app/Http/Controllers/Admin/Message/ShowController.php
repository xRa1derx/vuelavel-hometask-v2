<?php

namespace App\Http\Controllers\Admin\Message;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Resources\Message\MessageResource;
use Illuminate\Http\Request;

use function GuzzleHttp\Promise\all;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $sender = Message::with('sender')->where(['from' => auth()->id(), 'to' => $id])->get();
        $receiver = Message::with('sender')->where(['to' => auth()->id(), 'from' => $id])->get();
        $sorted = $sender->merge($receiver)->sortBy('id');
        return MessageResource::collection($sorted->values()->all())->resolve();
    }
}
