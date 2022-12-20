<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function add($fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->password = bcrypt($fields['password']);
        $user->save();

        return $user;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        if ($fields['password'] != 'null') {
            $this->password = bcrypt($fields['password']);
        }
        $this->save();
    }

    public function uploadAvatar($image)
    {
        if ($image == null) {
            return;
        }

        if ($this->avatar != null) {
            Storage::delete('uploads/avatars/' . $this->avatar);
        }

        $fileName = Str::random(10) . '.' . $image->extension();
        $image->storeAs('uploads/avatars', $fileName);
        $this->avatar = $fileName;
        $this->save();
    }


    public function updateAvatar($image)
    {
      
        if ($image == null) {
            Storage::delete('uploads/avatars/' . $this->avatar);
            $fileName = null;
            $this->avatar = $fileName;
            $this->save();
            return;
        }

        if ($this->avatar != null) {
            Storage::delete('uploads/avatars/' . $this->avatar);
        }

        $fileName = Str::random(10) . '.' . $image->extension();
        $image->storeAs('uploads/avatars', $fileName);
        $this->avatar = $fileName;
        $this->save();
    }

    // public function getImage()
    // {
    //     if ($this->avatar == null) {
    //         return '/uploads/no-user-image.png';
    //     }
    //     return '/uploads/' . $this->avatar;
    // }
}
