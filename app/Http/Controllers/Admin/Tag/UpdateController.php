<?php

namespace App\Http\Controllers\Admin\Tag;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Tag $tag)
    {
        $data =  $this->validate($request, [
            'title' => 'required'
        ]);
        $tag->update($data);
        return $tag;
    }
}
