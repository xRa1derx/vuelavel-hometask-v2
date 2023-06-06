<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $this->validate($request, [
            'uuid' => 'required',
            'files' => 'required|array'
        ]);
        $currentFiles = new File();
        return FileResource::collection($currentFiles->uploadFile($data))->resolve();
    }
}
