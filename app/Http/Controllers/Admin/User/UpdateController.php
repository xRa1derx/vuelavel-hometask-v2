<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user->edit($request->all());
        $user->updateAvatar($request->file('avatar'));
        return $user;
    }
}
