<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(Request $request, Category $category)
    {
        $data =  $this->validate($request, [
            'title' => 'required'
        ]);
        $category->update($data);
        return $category;
    }
}
