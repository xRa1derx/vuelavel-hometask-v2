<?php

namespace App\Http\Controllers\Admin\Message;

use App\Events\ConnectingStatusEvent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Resources\Message\MessageResource;
use Illuminate\Support\Facades\Auth;

class ShowController extends Controller
{
    public function __invoke($id)
    {
        $sender = Message::with('sender')->where(['from' => auth()->id(), 'to' => $id])->get();
        $receiver = Message::with('sender')->where(['to' => auth()->id(), 'from' => $id])->get();
        $sortedLastTenMessages = $sender->merge($receiver)->sortBy('id')->slice(-10);
        if (Auth::user()->is_admin) {
            broadcast(new ConnectingStatusEvent($id));
        } else {
            broadcast(new ConnectingStatusEvent(Auth::user()->id));
        }

        return MessageResource::collection($sortedLastTenMessages->values()->all())->resolve();
    }
}
