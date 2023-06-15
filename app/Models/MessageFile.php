<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageFile extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function uploadFile($data)
    {
        $files = $data['files'];
        unset($data['files']);
        $arrayOfFiles = [];
        foreach ($files as $file) {
            $fileName =  $file->getClientOriginalName();
            $file->storeAs('files/messages/', md5(Carbon::now()) . '_' . $fileName);
            $filePath = 'files/messages/' . md5(Carbon::now()) . '_' . $fileName;
            $newFile = MessageFile::create([
                'name' => $fileName,
                'message_uuid' => $data['uuid'],
                'path' => $filePath
            ]);
            array_push($arrayOfFiles, $newFile);
        }
       return $arrayOfFiles;
    }
}
