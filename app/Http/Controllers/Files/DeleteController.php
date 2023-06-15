<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageFile;

class DeleteController extends Controller
{
    public function __invoke($id)
    {
        $message = new Message();
        $deletedFile = $message->deleteFile($id);
        return $deletedFile;
        // $file = MessageFile::findOrFail($id);
        // if (file_exists(public_path() . '/' . $file->path)) {
        //     unlink(public_path() . '/' . $file->path);
        // }
        // $file->delete();
        // return $file;
    }
}
