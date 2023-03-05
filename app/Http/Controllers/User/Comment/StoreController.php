<?php

namespace App\Http\Controllers\User\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $this->validate($request, [
            'post_id' => 'required',
            'user_id' => 'required',
            'text' => 'required',
            'parent_id' => 'nullable'
        ]);
        Comment::create($data);
    }
}
