<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'post_id', 'user_id', 'parent_id', 'depth', 'verified', 'new'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies.author', 'parent')->where('verified', '=', 1);
    }

    public function parent()
    {
        return $this->hasOne(Comment::class, 'id', 'parent_id');
    }
}
