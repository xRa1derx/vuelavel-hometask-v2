<?php

namespace App\Http\Controllers\Admin\Message;

use App\Events\DeleteMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    public function __invoke($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();
        $roomId = Auth::user()->is_admin ? $message->receiver->id : $message->sender->id;
        broadcast(new DeleteMessageEvent($message, $roomId))->toOthers();
        return $message->uuid;
    }
}
