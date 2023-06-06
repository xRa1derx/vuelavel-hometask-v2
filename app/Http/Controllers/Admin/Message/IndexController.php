<?php

namespace App\Http\Controllers\Admin\Message;

use App\Http\Controllers\Controller;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $message = Message::with('sender')->where('from', auth()->id())->get();
        return MessageResource::collection($message)->resolve();
    }
}
