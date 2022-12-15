<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $user = User::add($request->all());
        $user->uploadAvatar($request->file('avatar'));
    }
}
