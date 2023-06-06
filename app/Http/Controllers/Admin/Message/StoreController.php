<?php

namespace App\Http\Controllers\Admin\Message;

use App\Events\StoreMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(User $user, StoreRequest $request)
    {
        $data = $request->validated();
        if (Auth::user()->id != $request->from) {
            return 'ERROR';
        }

        if (!array_key_exists('files', $data)) {
            $message = Message::create($data);
        } else {
            $files = $data['files'];
            unset($data['files']);
            $message = Message::create($data);
            foreach ($files as $file) {
                $message->uploadFile($file);
            }
            
        }
        broadcast(new StoreMessageEvent($message, $user->id))->toOthers();
        return MessageResource::make($message)->resolve();
    }
}
