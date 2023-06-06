<?php

namespace App\Http\Controllers\Admin\Message;

use App\Events\UpdateMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $message = Message::with('files')->findOrFail($id);
        $countFiles = count($message->files);
        $data = $this->validate($request, [
            'message' => [$countFiles === 0 ? 'required' : 'nullable'],
        ]);

        $message->update($data);
        $roomId = Auth::user()->is_admin ? $message->receiver->id : $message->sender->id;
        broadcast(new UpdateMessageEvent($message, $roomId))->toOthers();
        return $id;
    }
}
