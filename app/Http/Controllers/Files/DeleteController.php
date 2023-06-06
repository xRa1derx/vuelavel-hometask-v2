<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\File;

class DeleteController extends Controller
{
    public function __invoke($id)
    {
        $file = File::findOrFail($id);
        if (file_exists(public_path() . '/' . $file->path)) {
            unlink(public_path() . '/' . $file->path);
        }
        $file->delete();
        return $file;
    }
}
