<?php

namespace App\Http\Controllers\Files;

use App\Events\AdditionalFileEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Models\MessageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $this->validate($request, [
            'uuid' => 'required',
            'files' => 'required|array',
            'from' => 'required|integer',
            'to' => 'required|integer',
        ]);
        $roomId = Auth::user()->is_admin ? $data['to'] : $data['from'];
        $currentFiles = new MessageFile();
        $savedFiles = FileResource::collection($currentFiles->uploadFile($data))->resolve();
        broadcast(new AdditionalFileEvent($data['uuid'], $roomId, $savedFiles))->toOthers();
        return $savedFiles;
    }
}
