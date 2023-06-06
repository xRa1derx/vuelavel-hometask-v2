<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->hasMany(File::class);
    }

    public function uploadFile($file)
    {
        $fileName =  $file->getClientOriginalName();
        $file->storeAs('files/messages/', md5(Carbon::now()) . '_' . $fileName);
        // $path = public_path('files/messages/' . $fileName);
        $filePath = 'files/messages/' . md5(Carbon::now()) . '_' . $fileName;
        File::create([
            'name' => $fileName,
            'message_uuid' => $this->uuid,
            'path' => $filePath
        ]);
    }
}
