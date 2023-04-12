<?php

namespace App\Http\Controllers\User\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateController extends Controller
{
    public function __invoke(Comment $id, Request $request)
    {

        $comment = $this->validate($request, [
            'text' => 'required|string'
        ]);
        if (Auth::user()->id !== $request->user_id) {
            return;
        }
        $id->update($comment);
        return $id;
    }
}
