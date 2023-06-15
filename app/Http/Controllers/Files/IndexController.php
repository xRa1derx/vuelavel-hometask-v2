<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\MessageFile;

class IndexController extends Controller
{
    public function __invoke($id)
    {
        $file = MessageFile::findOrFail($id);
        $path = public_path() . "/" . $file->path;

        return response()->download(
            $path,
            $file->name,
            [],
            'attachment'
        );
    }
}
