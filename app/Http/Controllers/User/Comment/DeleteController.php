<?php

namespace App\Http\Controllers\User\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    public function __invoke($id)
    {
        $comment = Comment::find($id);
        if (Auth::user()->id !== $comment->author->id) {
            return 'access denied!';
        }
        $comment->delete();
    }
}
