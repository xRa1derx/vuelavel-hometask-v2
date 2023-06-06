<?php

namespace App\Http\Controllers\Admin\Message;

use App\Events\MarkAsReadEvent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkAsReadController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $data = $this->validate($request, [
            'new' => 'required|integer',
        ]);
        $message = Message::findOrFail($id);
        $message->update($data);
        $roomId = $message->to === 1 ? $message->from : $message->to;
        broadcast(new MarkAsReadEvent($message, $roomId))->toOthers();
        return $message;
    }
}
