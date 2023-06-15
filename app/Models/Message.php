<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Intervention\Image\Facades\Image as ImageIntervention;

class Message extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'uuid';

    // protected $keyType = 'string';

    public $incrementing = false;
    protected $guarded = false;

    // protected $casts = [
    //     'files' => 'array'
    // ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to');
    }

    public function files()
    {
        return $this->hasMany(MessageFile::class);
    }

    public function images()
    {
        return $this->hasMany(MessageImage::class);
    }

    public function uploadFile($file)
    {
        $fileName =  $file->getClientOriginalName();
        $file->storeAs('messages/files/', md5(Carbon::now()) . '_' . $fileName);
        // $path = public_path('files/messages/' . $fileName);
        $filePath = 'messages/files/' . md5(Carbon::now()) . '_' . $fileName;
        MessageFile::create([
            'name' => $fileName,
            'message_uuid' => $this->uuid,
            'path' => $filePath
        ]);
    }

    public function uploadImage($image)
    {
        $imageName = md5(Carbon::now()) . '_' . $image->getClientOriginalName();
        $imagePath = 'messages/images/' . $imageName;
        $image->storeAs('messages/images/', $imageName);
        // $preview_save = public_path('messages/images/' . $prev_name .  '_' . md5(Carbon::now()));
        $preview_path = 'messages/images/prev_' . $imageName;
        MessageImage::create([
            'name' => $imageName,
            'message_uuid' => $this->uuid,
            'preview' => $preview_path,
            'path' => $imagePath
        ]);
        ImageIntervention::make($image)->orientate()->heighten(500, function ($image) {
            $image->upsize();
        })->save($preview_path);
        // $canvas = ImageIntervention::canvas(245, 245);
        // $image  = ImageIntervention::make($image->getRealPath())->resize(245, 245, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        // $canvas->insert($image, 'center');
        // $canvas->save($preview_path);
    }

    public function deleteFile($id)
    {
        $file = MessageFile::findOrFail($id);
        
        if (file_exists(public_path() . '/' . $file->path)) {
            unlink(public_path() . '/' . $file->path);
        }
        $file->delete();
        return $file;
    }
}
