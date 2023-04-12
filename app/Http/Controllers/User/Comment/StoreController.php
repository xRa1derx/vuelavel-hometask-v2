<?php

namespace App\Http\Controllers\User\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $this->validate($request, [
            'post_id' => 'required',
            'user_id' => 'required',
            'text' => 'required',
            'parent_id' => 'nullable',
            'depth' => 'required|integer',
        ]);
        $data = array_merge($request->all(), ['verified' => Auth::check() ? 1 : 0]);
        Comment::create($data);
    }
}
